<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\OrderReview;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Console\Command;

class ShowDatabaseSummary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:summary {--detailed : Show detailed information}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show database summary and login credentials';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Deadlineku Database Summary');
        $this->line('================================');

        // Basic counts
        $this->line('📊 <comment>Data Counts:</comment>');
        $this->line('   Users: <info>' . User::count() . '</info>');
        $this->line('   Services: <info>' . Service::count() . '</info>');
        $this->line('   Orders: <info>' . Order::count() . '</info>');
        $this->line('   Tickets: <info>' . Ticket::count() . '</info>');
        $this->line('   Reviews: <info>' . OrderReview::count() . '</info>');
        $this->newLine();

        // Login credentials
        $this->line('🔑 <comment>Login Credentials:</comment>');
        $this->line('   Admin: <info>admin@deadlineku.test</info> (password: password)');
        $this->line('   Client: <info>client@deadlineku.test</info> (password: password)');
        $this->newLine();

        // Email bypass status
        $bypass = config('app.bypass_email', false);
        $this->line('📧 <comment>Email Verification:</comment> ' . ($bypass ? '<error>BYPASSED</error>' : '<info>ENABLED</info>'));
        if ($bypass) {
            $this->line('   💡 All users are auto-verified for testing');
        }
        $this->newLine();

        if ($this->option('detailed')) {
            $this->showDetailedInfo();
        }

        $this->info('✅ Database ready for development!');
    }

    private function showDetailedInfo()
    {
        $this->line('📋 <comment>Detailed Information:</comment>');
        $this->newLine();

        // Admin info
        $admin = User::where('email', 'admin@deadlineku.test')->first();
        if ($admin) {
            $this->line('👑 <comment>Admin Account:</comment>');
            $this->line('   Name: <info>' . $admin->name . '</info>');
            $this->line('   Email: <info>' . $admin->email . '</info>');
            $this->line('   Role: <info>' . $admin->role . '</info>');
            $this->newLine();
        }

        // Services
        $this->line('🛍️  <comment>Available Services:</comment>');
        Service::all()->each(function ($service) {
            $this->line('   • <info>' . $service->name . '</info> - Rp ' . number_format($service->price, 0, ',', '.'));
        });
        $this->newLine();

        // Order statuses
        $this->line('📦 <comment>Order Status Distribution:</comment>');
        $statuses = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        foreach ($statuses as $status) {
            $this->line('   ' . ucfirst($status->status) . ': <info>' . $status->count . '</info>');
        }
        $this->newLine();

        // Recent orders
        $this->line('🆕 <comment>Recent Orders:</comment>');
        Order::with('service')->latest()->take(3)->each(function ($order) {
            $this->line('   • <info>' . $order->order_number . '</info> - ' . $order->service->name . ' (<comment>' . $order->status . '</comment>)');
        });
        $this->newLine();

        // Tickets
        $this->line('🎫 <comment>Support Tickets:</comment>');
        Ticket::with(['user', 'order'])->latest()->take(3)->each(function ($ticket) {
            $this->line('   • <info>' . $ticket->code . '</info> - ' . $ticket->subject . ' (<comment>' . $ticket->status . '</comment>)');
        });
    }
}
