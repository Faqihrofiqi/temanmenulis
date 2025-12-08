<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_number' => 'DL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
            'service_id' => Service::factory(),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->unique()->safeEmail(),
            'customer_phone' => fake()->phoneNumber(),
            'order_details' => fake()->paragraph(),
            'amount' => fake()->numberBetween(500000, 5000000),
            'status' => fake()->randomElement(['pending', 'paid', 'processing', 'completed']),
            'payment_status' => fake()->randomElement(['pending', 'settlement']),
            'requires_followup' => fake()->boolean(20),
            'metadata' => ['source' => fake()->randomElement(['website', 'api'])],
        ];
    }
}
