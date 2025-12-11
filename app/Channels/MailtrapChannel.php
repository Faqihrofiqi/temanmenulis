<?php

namespace App\Channels;

use App\Services\Mailtrap\MailtrapService;
use Illuminate\Notifications\Notification;

class MailtrapChannel
{
    protected MailtrapService $mailtrapService;

    public function __construct(MailtrapService $mailtrapService)
    {
        $this->mailtrapService = $mailtrapService;
    }

    /**
     * Send the given notification.
     */
    public function send($notifiable, Notification $notification): void
    {
        if (method_exists($notification, "toMailtrap")) {
            $notification->toMailtrap($notifiable);
        }
    }
}
