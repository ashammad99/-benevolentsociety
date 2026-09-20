<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name_en' => ucfirst($name),
            'name_ar' => fake()->unique()->words(2, true),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 100000),
            'user_id' => User::factory(),
        ];
    }
}
