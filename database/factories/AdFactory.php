<?php

namespace Database\Factories;

use App\Models\Ad;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ad>
 */
class AdFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title_en' => fake()->sentence(3),
            'title_ar' => fake()->sentence(3),
            'body_en' => fake()->sentence(10),
            'body_ar' => fake()->sentence(10),
            'type' => 'banner',
            'image_path' => null,
            'url' => fake()->url(),
            'starts_at' => null,
            'ends_at' => null,
            'status' => 'active',
            'user_id' => User::factory(),
        ];
    }

    public function live(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addWeek(),
        ]);
    }

    public function paused(): static
    {
        return $this->state(fn (array $attributes) => ['status' => 'paused']);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'starts_at' => now()->subMonth(),
            'ends_at' => now()->subWeek(),
        ]);
    }
}
