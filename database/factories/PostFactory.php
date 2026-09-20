<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title_en' => fake()->sentence(4),
            'title_ar' => fake()->sentence(4),
            'excerpt_en' => fake()->sentence(10),
            'excerpt_ar' => fake()->sentence(10),
            'content_en' => fake()->paragraphs(3, true),
            'content_ar' => fake()->paragraphs(3, true),
            'image_path' => null,
            'category_id' => Category::factory(),
            'user_id' => User::factory(),
            'status' => 'draft',
            'is_featured' => false,
            'published_at' => null,
            'views_count' => 0,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
}
