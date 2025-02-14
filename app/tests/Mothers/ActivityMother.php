<?php

declare(strict_types=1);

namespace App\tests\Mothers;

use App\Models\Activity;
use Faker\Factory;

class ActivityMother
{
    public static function random(array $overrides = []): array
    {
        $data = self::toArray($overrides);
        $activity = Activity::factory()->create($data);
    
        return ['activity' => $activity, 'data' => $data];
    }

    public static function toArray(array $overrides = []): array
    {
        $faker = Factory::create();

        return array_merge([
            'name' => $faker->sentence(8),
            'description' => $faker->text(50),
            'max_capacity' => $faker->numberBetween(5, 50),
            'start_date' => $faker->date('Y-m-d'),
        ], $overrides);
    }
}
