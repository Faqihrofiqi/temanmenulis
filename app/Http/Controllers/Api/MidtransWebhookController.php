<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PaymentStatusUpdated;

class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = $request->all();
        $signature = $request->header('X-Signature');
        $orderId = data_get($payload, 'order_id');
        $transactionStatus = data_get($payload, 'transaction_status');
        $fraudStatus = data_get($payload, 'fraud_status');

        if (! $this->isSignatureValid($payload, $signature)) {
            Log::warning('Midtrans signature invalid', ['payload' => $payload]);

            return response()->noContent(401);
        }

        $order = Order::where('order_number', $orderId)->first();

        if (! $order) {
            Log::warning('Midtrans order not found', ['order_id' => $orderId]);

            return response()->noContent(404);
        }

        $previousPaymentStatus = $order->payment_status;
        $this->updateOrderStatus($order, $transactionStatus, $fraudStatus, $payload);

        // Send notification if payment status changed
        if ($previousPaymentStatus !== $order->payment_status && $order->payment_status !== 'pending') {
            try {
                Notification::route('mail', $order->customer_email)
                    ->notify(new PaymentStatusUpdated($order, $previousPaymentStatus, $order->payment_status));
            } catch (\Throwable $exception) {
                Log::error('Failed to send payment notification', [
                    'order_id' => $order->order_number,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return response()->noContent();
    }

    private function isSignatureValid(array $payload, ?string $signature): bool
    {
        if (! $signature) {
            return false;
        }

        $serverKey = config('midtrans.server_key');
        $raw = data_get($payload, 'order_id') . data_get($payload, 'status_code') . data_get($payload, 'gross_amount') . $serverKey;
        $expectedSignature = hash('sha512', $raw);

        return hash_equals($expectedSignature, $signature);
    }

    private function updateOrderStatus(Order $order, ?string $status, ?string $fraudStatus, array $payload): void
    {
        $status = strtolower($status ?? '');
        $fraudStatus = strtolower($fraudStatus ?? '');

        $paymentStatus = match ($status) {
            'capture' => $fraudStatus === 'challenge' ? 'challenge' : 'settlement',
            'settlement' => 'settlement',
            'pending' => 'pending',
            'cancel', 'deny', 'expire', 'failure' => 'cancelled',
            'refund', 'partial_refund' => 'refunded',
            default => $order->payment_status,
        };

        $orderStatus = match ($paymentStatus) {
            'settlement' => 'paid',
            'pending' => 'pending',
            'challenge' => 'processing',
            'cancelled' => 'cancelled',
            'refunded' => 'refunded',
            default => $order->status,
        };

        // Create payment record if not exists or update existing
        $payment = $order->payments()->updateOrCreate(
            [
                'reference' => data_get($payload, 'transaction_id'),
            ],
            [
                'provider' => data_get($payload, 'payment_type', 'midtrans_snap'),
                'amount' => data_get($payload, 'gross_amount', $order->amount),
                'status' => $paymentStatus,
                'payload' => $payload,
                'paid_at' => in_array($paymentStatus, ['settlement', 'capture']) ? now() : null,
            ]
        );

        // Update order metadata with webhook data
        $metadata = $order->metadata ?? [];
        data_set($metadata, 'midtrans.webhook', [
            'received_at' => now()->toIso8601String(),
            'transaction_status' => $status,
            'fraud_status' => $fraudStatus,
            'payment_type' => data_get($payload, 'payment_type'),
            'transaction_id' => data_get($payload, 'transaction_id'),
        ]);

        $order->forceFill([
            'status' => $orderStatus,
            'payment_status' => $paymentStatus,
            'paid_at' => $paymentStatus === 'settlement' ? now() : $order->paid_at,
            'metadata' => $metadata,
        ])->save();

        // Log the webhook processing
        Log::info('Midtrans webhook processed', [
            'order_id' => $order->order_number,
            'transaction_status' => $status,
            'payment_status' => $paymentStatus,
            'order_status' => $orderStatus,
            'payment_type' => data_get($payload, 'payment_type'),
            'transaction_id' => data_get($payload, 'transaction_id'),
        ]);
    }
}
