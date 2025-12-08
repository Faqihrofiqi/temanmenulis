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
        ];

        foreach ($services as $service) {
            $slug = Str::slug($service['name']);

            Service::updateOrCreate(
                ['slug' => $slug],
                array_merge($service, [
                    'slug' => $slug,
                    'description' => $service['short_description'],
                    'delivery_days' => 21,
                    'features' => [
                        'Revisi sampai ACC',
                        'Update progres mingguan',
                        'Garansi plagiarisme < 10%',
                    ],
                    'thumbnail_path' => 'assets/images/'.$service['category'].'.jpg',
                    'is_featured' => in_array($service['category'], ['skripsi', 'thesis']),
                    'is_active' => true,
                ])
            );
        }
    }
}
