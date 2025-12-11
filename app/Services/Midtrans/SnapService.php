<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class SnapService
{
    public function getOrCreateTransaction(Order $order, array $options = []): array
    {
        $this->ensureConfigured();

        $order->loadMissing(['service', 'user']);

        if ($cached = $this->validCachedSnapshot($order)) {
            return $cached;
        }

        $payload = $this->buildPayload($order, $options);
        $response = $this->requestSnapTransaction($payload);

        $snapshot = [
            'token' => data_get($response, 'token'),
            'redirect_url' => data_get($response, 'redirect_url'),
            'enabled_payments' => $payload['enabled_payments'] ?? null,
            'payload' => $response,
            'requested_at' => now()->toIso8601String(),
            'expires_at' => now()->addMinutes(60)->toIso8601String(),
        ];

        $this->storeSnapshot($order, $snapshot);

        return $snapshot;
    }

    private function ensureConfigured(): void
    {
        if (blank(config('midtrans.server_key')) || blank(config('midtrans.client_key'))) {
            throw new RuntimeException('Midtrans credentials are not configured.');
        }

        // Check for obvious placeholder keys
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');

        if ($serverKey === 'Mid-server-EXAMPLE' || $clientKey === 'Mid-client-EXAMPLE' ||
            str_contains($serverKey, 'EXAMPLE') || str_contains($clientKey, 'EXAMPLE')) {
            throw new RuntimeException('Midtrans credentials appear to be example/placeholder keys. Please get real keys from your Midtrans dashboard.');
        }
    }

    private function validCachedSnapshot(Order $order): ?array
    {
        $snapshot = data_get($order->metadata, 'midtrans.snap');

        if (! $snapshot || blank(data_get($snapshot, 'token'))) {
            return null;
        }

        $expiresAt = data_get($snapshot, 'expires_at');

        if (! $expiresAt) {
            return $snapshot;
        }

        try {
            if (Carbon::parse($expiresAt)->isPast()) {
                return null;
            }
        } catch (\Throwable $exception) {
            Log::warning('Cannot parse Midtrans snap expiry', [
                'order' => $order->order_number,
                'expiry' => $expiresAt,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }

        return $snapshot;
    }

    private function buildPayload(Order $order, array $options = []): array
    {
        $paymentChannel = $options['payment_channel'] ?? null;
        $grossAmount = (int) round($order->amount);

        return array_filter([
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => [
                [
                    'id' => (string) $order->service_id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => substr($order->service->name ?? 'Custom Service', 0, 50),
                ],
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'credit_card' => [
                'secure' => true,
            ],
            'enabled_payments' => $this->mapEnabledPayments($paymentChannel),
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minutes',
                'duration' => 60,
            ],
        ]);
    }

    private function mapEnabledPayments(?string $channel): ?array
    {
        return match ($channel) {
            'qris' => ['qris'],
            'bank_transfer' => ['permata_va', 'bca_va', 'bni_va', 'bri_va', 'other_va', 'echannel'],
            default => null,
        };
    }

    private function requestSnapTransaction(array $payload): array
    {
        try {
            return Http::withBasicAuth(config('midtrans.server_key'), '')
                ->timeout(config('midtrans.timeout', 15))
                ->acceptJson()
                ->post(config('midtrans.snap_url'), $payload)
                ->throw()
                ->json();
        } catch (RequestException $exception) {
            $response = $exception->response;
            $statusCode = $response ? $response->status() : null;
            $responseData = $response ? $response->json() : null;

            Log::error('Midtrans snap request failed', [
                'payload' => $payload,
                'status_code' => $statusCode,
                'response' => $responseData,
                'message' => $exception->getMessage(),
            ]);

            // Provide user-friendly error messages
            if ($statusCode === 401) {
                throw new RuntimeException(
                    'Midtrans authentication failed. Please check your Server Key and Client Key in the .env file. ' .
                    'Make sure you are using valid keys from your Midtrans dashboard.'
                );
            }

            if ($statusCode === 400) {
                $errorMessages = data_get($responseData, 'error_messages', []);
                if (is_array($errorMessages) && !empty($errorMessages)) {
                    throw new RuntimeException('Midtrans API error: ' . implode(', ', $errorMessages));
                }
            }

            throw new RuntimeException('Failed to create Midtrans payment. Please try again or contact support.');
        }
    }

    private function storeSnapshot(Order $order, array $snapshot): void
    {
        $metadata = $order->metadata ?? [];
        data_set($metadata, 'midtrans.snap', $snapshot);

        $order->forceFill([
            'metadata' => $metadata,
            'payment_token' => $snapshot['token'] ?? $order->payment_token,
        ])->save();
    }
}
