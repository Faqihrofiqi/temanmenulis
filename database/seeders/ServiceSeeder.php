<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Akademik Services
            ['name' => 'SEMPRO SKRIPSI', 'category' => 'skripsi', 'price' => 799000, 'short_description' => 'Proposal BAB 1-3'],
            ['name' => 'SEMHAS SKRIPSI', 'category' => 'skripsi', 'price' => 1499000, 'short_description' => 'Pembahasan BAB 4-5'],
            ['name' => 'WISUDA SKRIPSI', 'category' => 'skripsi', 'price' => 2499000, 'short_description' => 'FullBab 1-5 Skripsi'],
            ['name' => 'WISUDA++ SKRIPSI', 'category' => 'skripsi', 'price' => 2699000, 'short_description' => 'FullBab 1-5 + Full Bimbingan'],
            ['name' => 'SEMPRO THESIS', 'category' => 'thesis', 'price' => 1499000, 'short_description' => 'Proposal BAB 1-3'],
            ['name' => 'SEMHAS THESIS', 'category' => 'thesis', 'price' => 1999000, 'short_description' => 'Pembahasan BAB 4-5'],
            ['name' => 'WISUDA++ THESIS', 'category' => 'thesis', 'price' => 2599000, 'short_description' => 'FullBab 1-6'],
            ['name' => 'WISUDA+++ THESIS', 'category' => 'thesis', 'price' => 3099000, 'short_description' => 'FullBab 1-6 + Full Bimbingan'],
            ['name' => 'TES MASUK DISERTASI', 'category' => 'disertasi', 'price' => 1199000, 'short_description' => 'Proposal BAB 1'],
            ['name' => 'SEMPRO DISERTASI', 'category' => 'disertasi', 'price' => 2899000, 'short_description' => 'Proposal BAB 1-3'],
            ['name' => 'SEMHAS DISERTASI', 'category' => 'disertasi', 'price' => 4099000, 'short_description' => 'Pembahasan BAB 4-5'],
            ['name' => 'WISUDA DISERTASI', 'category' => 'disertasi', 'price' => 6099000, 'short_description' => 'FullBab 1-5'],
            ['name' => 'PENGETIKAN JURNAL AKADEMIK', 'category' => 'pengetikan', 'price' => 250000, 'short_description' => 'Pengetikan Jurnal/Makalah Akademik'],

            // Design 2D Services
            ['name' => 'DESIGN BANNER PROMOSI', 'category' => 'design', 'price' => 150000, 'short_description' => 'Banner Promosi & Iklan'],
            ['name' => 'DESIGN POSTER EVENT', 'category' => 'design', 'price' => 200000, 'short_description' => 'Poster Acara & Event'],
            ['name' => 'DESIGN LOGO & BRANDING', 'category' => 'design', 'price' => 350000, 'short_description' => 'Logo & Identitas Brand'],
            ['name' => 'EDIT FOTO PHOTOSHOP', 'category' => 'design', 'price' => 75000, 'short_description' => 'Edit Foto Profesional'],
            ['name' => 'DESIGN BROCHURE & FLYER', 'category' => 'design', 'price' => 250000, 'short_description' => 'Brosur & Flyer Promosi'],
            ['name' => 'DESIGN SOCIAL MEDIA KIT', 'category' => 'design', 'price' => 400000, 'short_description' => 'Template Social Media'],

            // Web Development Services
            ['name' => 'WEBSITE COMPANY PROFILE', 'category' => 'web', 'price' => 2500000, 'short_description' => 'Website Profil Perusahaan'],
            ['name' => 'WEBSITE TOKO ONLINE', 'category' => 'web', 'price' => 3500000, 'short_description' => 'E-commerce dengan Laravel'],
            ['name' => 'WEBSITE PERSONAL BLOG', 'category' => 'web', 'price' => 1500000, 'short_description' => 'Blog Pribadi Modern'],
            ['name' => 'WEBSITE PORTFOLIO', 'category' => 'web', 'price' => 1200000, 'short_description' => 'Portfolio Creative'],
            ['name' => 'LANDING PAGE PROMOSI', 'category' => 'web', 'price' => 800000, 'short_description' => 'Halaman Landing Product'],
            ['name' => 'MAINTENANCE WEBSITE', 'category' => 'web', 'price' => 500000, 'short_description' => 'Update & Perbaikan Website'],

            // Android App Development
            ['name' => 'APLIKASI E-COMMERCE', 'category' => 'android', 'price' => 5000000, 'short_description' => 'App Toko Online Android'],
            ['name' => 'APLIKASI CHATTING', 'category' => 'android', 'price' => 3500000, 'short_description' => 'App Chat Real-time'],
            ['name' => 'APLIKASI NEWS PORTAL', 'category' => 'android', 'price' => 4000000, 'short_description' => 'App Berita & Portal'],
            ['name' => 'APLIKASI LEARNING', 'category' => 'android', 'price' => 3000000, 'short_description' => 'E-Learning App'],
            ['name' => 'APLIKASI FITNESS TRACKER', 'category' => 'android', 'price' => 2500000, 'short_description' => 'App Lacak Kesehatan'],
            ['name' => 'MAINTENANCE APLIKASI', 'category' => 'android', 'price' => 750000, 'short_description' => 'Update & Fix Bug App'],

            // Electronic Repair Services
            ['name' => 'SERVICE LAPTOP RINGAN', 'category' => 'elektronik', 'price' => 150000, 'short_description' => 'Cleaning & Maintenance'],
            ['name' => 'SERVICE LAPTOP BERAT', 'category' => 'elektronik', 'price' => 400000, 'short_description' => 'Hardware Repair'],
            ['name' => 'UPGRADE RAM LAPTOP', 'category' => 'elektronik', 'price' => 250000, 'short_description' => 'RAM Upgrade 4GB-16GB'],
            ['name' => 'UPGRADE SSD LAPTOP', 'category' => 'elektronik', 'price' => 350000, 'short_description' => 'SSD Upgrade 256GB-1TB'],
            ['name' => 'SERVICE PC DESKTOP', 'category' => 'elektronik', 'price' => 200000, 'short_description' => 'PC Repair & Upgrade'],
            ['name' => 'INSTAL WINDOWS & SOFTWARE', 'category' => 'elektronik', 'price' => 150000, 'short_description' => 'OS Install & Software Setup'],
        ];

        foreach ($services as $service) {
            $slug = Str::slug($service['name']);

            // Define features based on category
            $features = match($service['category']) {
                'skripsi', 'thesis', 'disertasi' => [
                    'Revisi sampai ACC',
                    'Update progres mingguan',
                    'Garansi plagiarisme < 10%',
                    'Konsultasi via WA/Zoom',
                    'File format lengkap'
                ],
                'pengetikan' => [
                    'Format sesuai jurnal',
                    'Proofreading included',
                    'File Word & PDF',
                    'Revisi sampai ACC',
                    'Fast delivery'
                ],
                'design' => [
                    'High resolution output',
                    'Format JPG/PNG/PDF',
                    'Revisi sampai ACC',
                    'File sumber included',
                    'Ready print'
                ],
                'web' => [
                    'Responsive design',
                    'Mobile friendly',
                    'SEO optimized',
                    'Admin panel included',
                    '6 bulan maintenance',
                    'Training penggunaan'
                ],
                'android' => [
                    'Native performance',
                    'Modern UI/UX',
                    'Google Play ready',
                    'Source code included',
                    '6 bulan support',
                    'App store submission'
                ],
                'elektronik' => [
                    'Garansi 30 hari',
                    'Part original',
                    'Free diagnostic',
                    'Pickup & delivery',
                    'Lifetime support',
                    'Money back guarantee'
                ],
                default => [
                    'Revisi sampai ACC',
                    'Fast delivery',
                    'Professional quality'
                ]
            };

            // Define delivery days based on category
            $deliveryDays = match($service['category']) {
                'skripsi', 'thesis', 'disertasi' => 21,
                'pengetikan' => 7,
                'design' => 5,
                'web' => 14,
                'android' => 21,
                'elektronik' => 3,
                default => 7
            };

            Service::updateOrCreate(
                ['slug' => $slug],
                array_merge($service, [
                    'slug' => $slug,
                    'description' => $service['short_description'],
                    'delivery_days' => $deliveryDays,
                    'features' => $features,
                    'thumbnail_path' => 'assets/images/'.$service['category'].'.jpg',
                    'is_featured' => in_array($service['category'], ['skripsi', 'thesis', 'design', 'web']),
                    'is_active' => true,
                ])
            );
        }
    }
}
