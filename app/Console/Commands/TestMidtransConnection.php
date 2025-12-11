<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Services\Midtrans\SnapService;
use Illuminate\Console\Command;

class TestMidtransConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:test-connection {--cleanup : Delete test order after testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Midtrans Snap connection with current configuration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing Midtrans Snap Connection...');
        $this->line('');

        try {
            // Get test data
            $service = Service::first();
            if (!$service) {
                $this->error('❌ No services found. Run database seeding first.');
                return 1;
            }

            $user = User::where('email', 'client@deadlineku.test')->first();
            if (!$user) {
                $this->error('❌ Test user not found. Run database seeding first.');
                return 1;
            }

            $this->line('📦 <comment>Creating test order...</comment>');

            // Create test order
            $order = Order::factory()->create([
                'service_id' => $service->id,
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?? '+6281234567890',
                'amount' => $service->price,
                'status' => 'pending',
            ]);

            $this->line("✅ Test order created: <info>{$order->order_number}</info>");

            // Test Midtrans Snap
            $this->line('🔗 <comment>Testing Midtrans Snap connection...</comment>');

            $snapService = app(SnapService::class);
            $result = $snapService->getOrCreateTransaction($order);

            if (isset($result['token']) && isset($result['redirect_url'])) {
                $this->info('✅ Midtrans Snap connection SUCCESSFUL!');
                $this->line('');
                $this->line('📋 <comment>Test Results:</comment>');
                $this->line("   Token: <info>{$result['token']}</info>");
                $this->line("   Redirect URL: <info>{$result['redirect_url']}</info>");
                $this->line("   Order Amount: <info>Rp " . number_format($order->amount, 0, ',', '.') . "</info>");

                // Cleanup if requested
                if ($this->option('cleanup')) {
                    $order->delete();
                    $this->line('');
                    $this->line('🧹 <comment>Test order cleaned up.</comment>');
                } else {
                    $this->line('');
                    $this->warn('💡 Test order not cleaned up. Use --cleanup to remove it.');
                    $this->line("   Order ID: {$order->id}");
                }

                return 0;
            } else {
                $this->error('❌ Midtrans response missing required fields');
                $this->line('Response: ' . json_encode($result, JSON_PRETTY_PRINT));

                if ($this->option('cleanup')) {
                    $order->delete();
                }
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Midtrans test FAILED: ' . $e->getMessage());

            // Try to cleanup on error
            if (isset($order) && $this->option('cleanup')) {
                $order->delete();
                $this->line('🧹 Test order cleaned up after error.');
            }

            return 1;
        }
    }
}
