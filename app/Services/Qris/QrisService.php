<?php

namespace App\Services\Qris;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Str;

class QrisService
{
    public function generatePayload(Order $order): array
    {
        $amount = number_format($order->amount, 0, '', '');

        $qrString = sprintf(
            'PAY:%s:%s:%s:%s',
            rawurlencode(config('qris.name')),
            config('qris.number'),
            $amount,
            $order->order_number
        );

        return [
            'qr_string' => $qrString,
            'number' => config('qris.number'),
            'name' => config('qris.name'),
            'bank' => config('qris.bank'),
        ];
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
}
