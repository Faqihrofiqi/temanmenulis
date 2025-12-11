<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMidtransConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:check-config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Midtrans configuration and validate credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking Midtrans Configuration...');
        $this->newLine();

        // Check environment variables
        $serverKey = config('midtrans.server_key');
        $clientKey = config('midtrans.client_key');
        $merchantId = config('midtrans.merchant_id');
        $isProduction = config('midtrans.is_production');
        $baseUrl = config('midtrans.base_url');
        $snapUrl = config('midtrans.snap_url');

        $this->line("📋 Environment: <comment>" . ($isProduction ? 'PRODUCTION' : 'SANDBOX') . "</comment>");
        $this->line("🏪 Merchant ID: <comment>" . ($merchantId ?: 'Not set') . "</comment>");
        $this->line("🔑 Server Key: <comment>" . (filled($serverKey) ? substr($serverKey, 0, 10) . '...' : 'Not set') . "</comment>");
        $this->line("🔑 Client Key: <comment>" . (filled($clientKey) ? substr($clientKey, 0, 10) . '...' : 'Not set') . "</comment>");
        $this->line("🌐 Base URL: <comment>$baseUrl</comment>");
        $this->line("⚡ Snap URL: <comment>$snapUrl</comment>");
        $this->newLine();

        // Validate configuration
        $errors = [];

        if (blank($serverKey)) {
            $errors[] = 'Server Key is not configured';
        }

        if (blank($clientKey)) {
            $errors[] = 'Client Key is not configured';
        }

        if (blank($merchantId)) {
            $errors[] = 'Merchant ID is not configured';
        }

        // Check for obvious placeholder keys
        if ($serverKey === 'Mid-server-EXAMPLE' || str_contains($serverKey, 'EXAMPLE')) {
            $errors[] = 'Server Key appears to be a placeholder/example value';
        }

        if ($clientKey === 'Mid-client-EXAMPLE' || str_contains($clientKey, 'EXAMPLE')) {
            $errors[] = 'Client Key appears to be a placeholder/example value';
        }

        if (empty($errors)) {
            $this->info('✅ Configuration looks good!');
            $this->newLine();
            $this->comment('💡 Note: To fully test the integration, create a test order and check if payment works.');
            $this->comment('   If you get 401 errors, your keys might be invalid or expired.');
        } else {
            $this->error('❌ Configuration issues found:');
            foreach ($errors as $error) {
                $this->error("   • $error");
            }
            $this->newLine();
            $this->comment('🔧 To fix this:');
            $this->comment('   1. Go to https://dashboard.midtrans.com/');
            $this->comment('   2. Get your Server Key and Client Key from Settings > Access Keys');
            $this->comment('   3. Update your .env file with the real keys');
            $this->comment('   4. Run: php artisan config:cache');
        }

        return empty($errors) ? 0 : 1;
    }
}
