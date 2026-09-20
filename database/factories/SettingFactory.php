<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'key' => fake()->unique()->slug(2),
            'label_en' => fake()->words(2, true),
            'label_ar' => fake()->words(2, true),
            'group' => 'general',
            'type' => 'text',
            'value' => fake()->sentence(),
            'value_en' => null,
            'value_ar' => null,
            'is_public' => true,
            'updated_by' => null,
        ];
    }
}
