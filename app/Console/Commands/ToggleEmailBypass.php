<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ToggleEmailBypass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:toggle-bypass {--on : Enable email bypass} {--off : Disable email bypass}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle email verification bypass for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            $this->error('❌ .env file not found!');
            return 1;
        }

        $currentValue = config('app.bypass_email', false);

        if ($this->option('on')) {
            $newValue = true;
            $action = 'enabled';
        } elseif ($this->option('off')) {
            $newValue = false;
            $action = 'disabled';
        } else {
            // Toggle current value
            $newValue = !$currentValue;
            $action = $newValue ? 'enabled' : 'disabled';
        }

        if ($currentValue === $newValue) {
            $this->info("ℹ️  Email verification bypass is already {$action}.");
            return 0;
        }

        // Update .env file
        $envContent = File::get($envPath);

        // Update BYPASS_EMAIL
        $newContent = preg_replace(
            '/^BYPASS_EMAIL=.*$/m',
            "BYPASS_EMAIL=" . ($newValue ? 'true' : 'false'),
            $envContent
        );

        // Also update MAIL_MAILER based on bypass setting
        if ($newValue) {
            // When bypass is active, use log driver to prevent email sending
            $newContent = preg_replace(
                '/^MAIL_MAILER=.*$/m',
                'MAIL_MAILER=log',
                $newContent
            );
        } else {
            // When bypass is disabled, use smtp for actual email sending
            $newContent = preg_replace(
                '/^MAIL_MAILER=.*$/m',
                'MAIL_MAILER=smtp',
                $newContent
            );
        }

        File::put($envPath, $newContent);

        // Clear config cache
        $this->call('config:cache');

        $this->info("✅ Email verification bypass {$action}!");
        $this->newLine();

        if ($newValue) {
            $this->comment('🚀 Users can now access protected routes without email verification.');
            $this->comment('💡 Useful for testing UI without needing to verify emails.');
        } else {
            $this->comment('🔒 Email verification is now required for protected routes.');
            $this->comment('📧 Users must verify their email before accessing the dashboard.');
        }

        $this->newLine();
        $this->comment('🔄 Changes will take effect immediately.');

        return 0;
    }
}
