<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateMidtransKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:update-keys {--server-key= : Server Key} {--client-key= : Client Key} {--merchant-id= : Merchant ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Midtrans keys in .env file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔑 Updating Midtrans Keys...');
        $this->newLine();

        // Get keys from options or prompt
        $serverKey = $this->option('server-key') ?: $this->ask('Enter Server Key (from Midtrans dashboard)');
        $clientKey = $this->option('client-key') ?: $this->ask('Enter Client Key (from Midtrans dashboard)');
        $merchantId = $this->option('merchant-id') ?: $this->ask('Enter Merchant ID (from Midtrans dashboard)');

        if (!$this->confirm('Are you sure you want to update the Midtrans keys?', true)) {
            $this->info('Operation cancelled.');
            return;
        }

        // Read current .env file
        $envPath = base_path('.env');
        if (!File::exists($envPath)) {
            $this->error('❌ .env file not found!');
            return 1;
        }

        $envContent = File::get($envPath);

        // Update the keys
        $envContent = $this->updateEnvValue($envContent, 'MIDTRANS_SERVER_KEY', $serverKey);
        $envContent = $this->updateEnvValue($envContent, 'MIDTRANS_CLIENT_KEY', $clientKey);
        $envContent = $this->updateEnvValue($envContent, 'MIDTRANS_MERCHANT_ID', $merchantId);

        // Write back to .env file
        File::put($envPath, $envContent);

        $this->info('✅ Midtrans keys updated successfully!');
        $this->newLine();

        // Clear config cache
        $this->call('config:cache');
        $this->newLine();

        // Verify the configuration
        $this->call('midtrans:check-config');

        $this->newLine();
        $this->info('🎉 You can now test the payment functionality!');
        $this->comment('Try creating a new order and accessing the payment page.');
    }

    private function updateEnvValue(string $envContent, string $key, string $value): string
    {
        $pattern = "/^{$key}=.*$/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envContent)) {
            return preg_replace($pattern, $replacement, $envContent);
        } else {
            // Add new line if key doesn't exist
            return $envContent . PHP_EOL . $replacement;
        }
    }
}
