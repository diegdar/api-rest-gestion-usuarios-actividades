<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->sentence(1),
            'description' => fake()->sentence(10),
            'max_capacity' => fake()->numberBetween(15, 50),
            'start_date' => fake()->dateTimeBetween('-1 year', 'now'),

        ];
    }
}
