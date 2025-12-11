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
        $this->call([
            ServiceSeeder::class,
            OAuthClientSeeder::class,
        ]);

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Deadlineku Admin',
            'email' => 'admin@deadlineku.test',
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create test users
        $users = User::factory(5)->create([
            'email_verified_at' => now(),
        ]);

        // Add one more specific test user
        $customer = User::factory()->create([
            'name' => 'John Doe Client',
            'email' => 'client@deadlineku.test',
            'email_verified_at' => now(),
        ]);

        // Create orders with different statuses
        $services = Service::all();

        // Create various orders for demo
        foreach ($users as $user) {
            // Each user gets 1-3 random orders
            $userServices = $services->random(rand(1, 3));

            foreach ($userServices as $service) {
                Order::factory()->create([
                    'service_id' => $service->id,
                    'user_id' => $user->id,
                    'customer_name' => $user->name,
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? fake()->phoneNumber(),
                    'amount' => $service->price,
                    'status' => fake()->randomElement(['pending', 'paid', 'processing', 'completed']),
                    'payment_status' => fake()->randomElement(['pending', 'settlement', 'cancel']),
                ]);
            }
        }

        // Create specific orders for the main customer
        foreach ($services->take(3) as $service) {
            Order::factory()->create([
                'service_id' => $service->id,
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? fake()->phoneNumber(),
                'amount' => $service->price,
                'status' => 'paid',
                'payment_status' => 'settlement',
            ]);
        }

        // Create tickets for some orders
        $orders = Order::all();

        foreach ($orders->take(5) as $order) {
            $ticket = Ticket::create([
                'code' => 'TIC-' . strtoupper(Str::random(8)),
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'assigned_to' => $admin->id,
                'subject' => fake()->randomElement([
                    'Update Progres Pesanan',
                    'Revisi Bab 2',
                    'Deadline Perlu Dipercepat',
                    'Ada Kesalahan Penulisan',
                    'Permintaan Konsultasi'
                ]),
                'priority' => fake()->randomElement(['low', 'normal', 'high', 'urgent']),
                'status' => fake()->randomElement(['open', 'in_progress', 'resolved', 'closed']),
                'channel' => 'support',
            ]);

            // Add initial message from customer
            $ticket->messages()->create([
                'user_id' => $order->user_id,
                'message' => fake()->randomElement([
                    'Halo admin, mohon update progres pesanan saya.',
                    'Ada beberapa bagian yang perlu direvisi.',
                    'Deadline nya bisa dipercepat tidak?',
                    'Ada kesalahan penulisan di halaman 15.',
                    'Bisa konsultasi via zoom?'
                ]),
            ]);

            // Add admin response for some tickets
            if (fake()->boolean(60)) {
                $ticket->messages()->create([
                    'user_id' => $admin->id,
                    'message' => fake()->randomElement([
                        'Baik, saya akan cek progresnya dan update segera.',
                        'Revisi akan dilakukan sesuai permintaan.',
                        'Deadline bisa dipercepat dengan biaya tambahan.',
                        'Kesalahan penulisan akan diperbaiki.',
                        'Konsultasi bisa dijadwalkan besok jam 2 siang.'
                    ]),
                ]);
            }
        }

        // Create detailed reviews for completed orders
        $completedOrders = Order::where('status', 'completed')->take(8)->get();

        $reviews = [
            // Academic reviews
            [
                'rating' => 5,
                'headline' => 'Skripsi ACC dengan nilai A!',
                'message' => 'Tim Deadlineku sangat profesional! Skripsi saya yang awalnya stuck di sempro akhirnya bisa lanjut ke semhas. Kualitas penulisan sangat bagus, plagiarism cuma 5%. Sangat recommended untuk mahasiswa yang deadline mendesak.'
            ],
            [
                'rating' => 5,
                'headline' => 'Thesis S3 Berhasil Defend!',
                'message' => 'Pengerjaan thesis sangat detail dan metodologinya kuat. Sudah 3x revisi tapi tetap sabar bimbing. Komunikasi via Zoom juga sangat membantu. Terima kasih Deadlineku!'
            ],
            [
                'rating' => 4,
                'headline' => 'Disertasi BAB 4-5 Selesai Tepat Waktu',
                'message' => 'Sebagai dosen, saya butuh partner yang paham research methodology. Deadlineku memberikan hasil yang sesuai standar akademik internasional. Good job!'
            ],

            // Design reviews
            [
                'rating' => 5,
                'headline' => 'Banner Promosi Viral!',
                'message' => 'Design banner untuk event kampus sangat eye-catching! Warna-warnanya vibrant dan typography-nya readable dari jauh. Sudah dicetak jadi poster dan banyak yang tanya-tanya. Worth every penny!'
            ],
            [
                'rating' => 5,
                'headline' => 'Logo Brand Perfect!',
                'message' => 'Logo yang dibuat sangat modern dan scalable. Sudah digunakan di website, merchandise, dan social media. File sumber juga included jadi mudah diedit. Excellent work!'
            ],

            // Web development reviews
            [
                'rating' => 5,
                'headline' => 'Website Company Profile Wow!',
                'message' => 'Website company profile sangat profesional dan responsive! Loading cepat, SEO-friendly, dan admin panel mudah digunakan. Client kami impressed dan langsung ada yang order service.'
            ],
            [
                'rating' => 5,
                'headline' => 'E-commerce Lancar Jaya!',
                'message' => 'Toko online yang dibuat sangat user-friendly. Payment gateway smooth, inventory management mudah, dan laporan penjualan real-time. Omzet naik 200% setelah launch!'
            ],

            // Android app reviews
            [
                'rating' => 5,
                'headline' => 'App E-learning Amazing!',
                'message' => 'Aplikasi untuk kursus online sangat intuitive. Video streaming lancar, quiz system lengkap, dan progress tracking akurat. Sudah di-download 1000+ user di Play Store!'
            ]
        ];

        foreach ($completedOrders as $index => $order) {
            $reviewData = $reviews[$index % count($reviews)] ?? $reviews[0];
            $order->review()->create([
                'user_id' => $order->user_id,
                'rating' => $reviewData['rating'],
                'headline' => $reviewData['headline'],
                'message' => $reviewData['message'],
                'submitted_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Create additional positive reviews for marketing
        $marketingReviews = [
            [
                'rating' => 5,
                'headline' => 'Service Laptop Repair Cepat & Berkualitas!',
                'message' => 'Laptop saya yang lemot jadi kenceng setelah upgrade RAM dan SSD. Teknisi datang langsung ke rumah, sangat recommended untuk yang butuh reparasi urgent!'
            ],
            [
                'rating' => 5,
                'headline' => 'Pengetikan Jurnal Professional',
                'message' => 'Jurnal akademik saya diketik dengan format yang sesuai standar Scopus. Sudah accepted di jurnal internasional tier 2. Terima kasih Deadlineku!'
            ],
            [
                'rating' => 4,
                'headline' => 'App Fitness Tracker Mantap!',
                'message' => 'Aplikasi tracking olahraga yang dibuat sangat lengkap. Fitur calorie counter, workout planner, dan progress chart sangat membantu diet saya. Rating 4 karena masih ada beberapa bug minor.'
            ]
        ];

        // Add these reviews to random completed orders
        $additionalOrders = Order::where('status', 'completed')->skip(8)->take(3)->get();
        foreach ($additionalOrders as $index => $order) {
            if (isset($marketingReviews[$index])) {
                $order->review()->create([
                    'user_id' => $order->user_id,
                    'rating' => $marketingReviews[$index]['rating'],
                    'headline' => $marketingReviews[$index]['headline'],
                    'message' => $marketingReviews[$index]['message'],
                    'submitted_at' => now()->subDays(rand(7, 60)),
                ]);
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin login: admin@deadlineku.test');
        $this->command->info('Client login: client@deadlineku.test');
        $this->command->info('Created ' . User::count() . ' users, ' . Service::count() . ' services, ' . Order::count() . ' orders, ' . Ticket::count() . ' tickets');
    }
}
