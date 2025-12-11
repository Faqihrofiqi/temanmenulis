<?php

namespace App\Services\Qris;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;

class QrisService
{
    public function generatePayload(Order $order): array
    {
        $this->ensureCredentials();

        $order->loadMissing('service');

        if ($cached = $this->validCachedCharge($order)) {
            return $cached;
        }

        $charge = $this->requestCharge($order);
        $this->storeCharge($order, $charge);

        return $charge;
    }

    public function confirmPayment(Order $order, array $payload = []): Payment
    {
        $payment = $order->payments()->create([
            'reference' => $payload['reference'] ?? 'QRIS-' . strtoupper(Str::random(8)),
            'provider' => $payload['provider'] ?? 'manual_qris',
            'amount' => $payload['amount'] ?? $order->amount,
            'status' => $payload['status'] ?? 'settlement',
            'payload' => $payload,
            'paid_at' => $payload['paid_at'] ?? now(),
        ]);

        $order->update([
            'status' => 'paid',
            'payment_status' => 'settlement',
            'paid_at' => $payment->paid_at,
        ]);

        return $payment;
    }

    private function ensureCredentials(): void
    {
        if (blank(config('midtrans.server_key'))) {
            throw new RuntimeException('Midtrans server key is not configured.');
        }
    }

    private function validCachedCharge(Order $order): ?array
    {
        $cached = data_get($order->metadata, 'midtrans.qris');

        if (! $cached) {
            return null;
        }

        $expiry = data_get($cached, 'expiry_time');

        if (! $expiry) {
            return $cached;
        }

        try {
            $expiryTime = Carbon::parse($expiry);
        } catch (\Throwable $exception) {
            Log::warning('Invalid Midtrans expiry timestamp detected', [
                'order' => $order->order_number,
                'expiry' => $expiry,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        return $expiryTime->isPast() ? null : $cached;
    }

    private function requestCharge(Order $order): array
    {
        $payload = $this->buildChargePayload($order);

        try {
            $response = Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(config('midtrans.timeout', 15))
                ->acceptJson()
                ->baseUrl(config('midtrans.base_url'))
                ->post('/v2/charge', $payload)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            Log::error('Midtrans QRIS charge failed', [
                'order' => $order->order_number,
                'payload' => $payload,
                'response' => optional($exception->response)->json(),
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        return [
            'qr_string' => data_get($response, 'qr_string'),
            'qr_code_url' => $this->extractQrCodeUrl($response),
            'transaction_id' => data_get($response, 'transaction_id'),
            'order_id' => data_get($response, 'order_id'),
            'merchant_id' => data_get($response, 'merchant_id'),
            'gross_amount' => data_get($response, 'gross_amount'),
            'transaction_status' => data_get($response, 'transaction_status'),
            'fraud_status' => data_get($response, 'fraud_status'),
            'expiry_time' => data_get($response, 'expiry_time'),
            'raw_response' => $response,
        ];
    }

    private function extractQrCodeUrl(array $response): ?string
    {
        foreach (data_get($response, 'actions', []) as $action) {
            if (($action['name'] ?? null) === 'generate-qr-code') {
                return $action['url'] ?? null;
            }
        }

        return null;
    }

    private function buildChargePayload(Order $order): array
    {
        $grossAmount = (int) round($order->amount);

        return [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => (string) $order->service_id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => substr($order->service->name ?? 'Custom Service', 0, 50),
                ],
            ],
            'qris' => [
                'acquirer' => config('midtrans.qris_acquirer', 'gopay'),
            ],
            'custom_expiry' => [
                'expiry_duration' => 15,
                'unit' => 'minute',
            ],
        ];
    }

    private function storeCharge(Order $order, array $charge): void
    {
        $metadata = $order->metadata ?? [];
        data_set($metadata, 'midtrans.qris', $charge);

        $order->forceFill([
            'metadata' => $metadata,
            'payment_token' => $charge['transaction_id'] ?? $charge['qr_string'] ?? $order->payment_token,
        ])->save();
    }
}
