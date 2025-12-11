<?php

namespace App\Providers;

use App\Channels\MailtrapChannel;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register custom notification channel
        Notification::extend('mailtrap', function ($app) {
            return new MailtrapChannel($app->make(\App\Services\Mailtrap\MailtrapService::class));
        });

        // Change mail driver to log when email bypass is active
        if (config('app.bypass_email', false)) {
            config(['mail.default' => 'log']);
            // Also override any SMTP configurations to prevent authentication errors
            config(['mail.mailers.smtp.transport' => 'log']);
        }
    }
}
