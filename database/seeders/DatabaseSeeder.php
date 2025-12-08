<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Service;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ServiceSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Deadlineku Admin',
            'email' => 'admin@deadlineku.test',
            'role' => User::ROLE_ADMIN,
        ]);

        $customer = User::factory()->create([
            'name' => 'Deadlineku Client',
            'email' => 'client@deadlineku.test',
        ]);

        Service::all()->each(function (Service $service) use ($customer): void {
            Order::factory()->create([
                'service_id' => $service->id,
                'user_id' => $customer->id,
                'amount' => $service->price,
                'status' => 'paid',
                'payment_status' => 'settlement',
            ]);
        });

        $order = Order::first();

        if ($order) {
            $ticket = Ticket::create([
                'code' => 'TIC-' . strtoupper(Str::random(8)),
                'order_id' => $order->id,
                'user_id' => $customer->id,
                'assigned_to' => $admin->id,
                'subject' => 'Contoh Tiket Support',
                'priority' => 'high',
                'status' => 'open',
                'channel' => 'support',
            ]);

            $ticket->messages()->create([
                'user_id' => $customer->id,
                'message' => 'Halo admin, mohon update progres pesanan saya.',
            ]);
        }
    }
}
