<?php

declare(strict_types=1);

namespace App\tests\Mothers;

use App\Models\User;
use Faker\Factory;

class UserMother
{
    public static function random(array $overrides = []): User
    {
        return User::factory()->create(self::toArray($overrides));
    }

    public static function toArray(array $overrides = []): array
    {
        $faker = Factory::create();

        return array_merge([
            'name' => $faker->firstName,
            'surname' => $faker->lastName,
            'age' => $faker->numberBetween(18, 70),
            'email' => $faker->unique()->safeEmail,
            'password' => 'Password&1234',
        ], $overrides);
    }
}

