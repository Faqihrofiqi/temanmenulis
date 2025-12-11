<?php

namespace App\Notifications;

use App\Services\Mailtrap\MailtrapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PaymentStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly \App\Models\Order $order,
        public readonly string $previousStatus,
        public readonly string $newStatus
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mailtrap'];
    }

    /**
     * Send notification via Mailtrap API
     */
    public function toMailtrap(object $notifiable): array
    {
        $order = $this->order;
        $status = $this->newStatus;

        $templateVariables = [
            'name' => $order->customer_name,
            'order_number' => $order->order_number,
            'service_name' => $order->service->name ?? 'Layanan Custom',
            'amount' => number_format($order->amount, 0, ',', '.'),
            'status' => ucfirst($status),
        ];

        // Add status-specific variables
        switch ($status) {
            case 'settlement':
                $templateVariables['message'] = "✅ Pembayaran berhasil diterima! Tim kami akan segera memproses pesanan Anda.";
                $templateVariables['action_url'] = route('orders.check') . "?order_number={$order->order_number}";
                break;

            case 'pending':
                $templateVariables['message'] = "⏳ Pembayaran sedang diproses. Mohon tunggu konfirmasi.";
                break;

            case 'cancelled':
                $templateVariables['message'] = "❌ Pembayaran dibatalkan. Silakan hubungi support jika ada kesalahan.";
                break;

            case 'challenge':
                $templateVariables['message'] = "⚠️ Pembayaran perlu verifikasi manual. Tim kami sedang memproses.";
                break;

            default:
                $templateVariables['message'] = "Status pembayaran telah diperbarui.";
                break;
        }

        $mailtrapService = app(MailtrapService::class);
        return $mailtrapService->sendTemplateEmail(
            toEmail: $notifiable->email,
            toName: $notifiable->name,
            templateVariables: $templateVariables
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_number' => $this->order->order_number,
            'status' => $this->newStatus,
            'previous_status' => $this->previousStatus,
        ];
    }
}
