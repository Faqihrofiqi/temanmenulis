<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->sentence(3);

        $discount = fake()->boolean(30) ? fake()->randomElement([5, 10, 15, 20, 25]) : null;

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'category' => fake()->randomElement(['skripsi', 'thesis', 'disertasi', 'editing']),
            'short_description' => fake()->sentence(10),
            'description' => fake()->paragraph(4),
            'delivery_days' => fake()->numberBetween(7, 30),
            'price' => fake()->numberBetween(250000, 4000000),
            'discount_percentage' => $discount,
            'discount_label' => $discount ? fake()->randomElement(['Flash Sale', 'Promo Thesis Week', 'Closing Season']) : null,
            'discount_ends_at' => $discount ? now()->addDays(fake()->numberBetween(1, 10)) : null,
            'features' => fake()->randomElements([
                'Revisi sampai ACC',
                'Konsultasi Zoom',
                'Garansi Plagiarisme < 10%',
                'Pendampingan dosen',
            ], 3),
            'thumbnail_path' => 'assets/images/service-placeholder.jpg',
            'is_featured' => fake()->boolean(30),
            'is_active' => true,
        ];
    }
}
