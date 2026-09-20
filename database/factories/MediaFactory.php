<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->word().'.jpg',
            'disk' => 'public',
            'path' => 'media/'.fake()->uuid().'.jpg',
            'mime_type' => 'image/jpeg',
            'size_bytes' => fake()->numberBetween(1000, 500000),
            'type' => 'image',
            'description_en' => fake()->sentence(),
            'description_ar' => fake()->sentence(),
            'mediable_id' => null,
            'mediable_type' => null,
            'sort_order' => 0,
            'user_id' => User::factory(),
            'is_active' => true,
        ];
    }
}
