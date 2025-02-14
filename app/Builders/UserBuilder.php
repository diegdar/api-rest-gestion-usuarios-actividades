<?php

declare(strict_types=1);

namespace App\Builders;

use Faker\Factory;
use App\Models\User;

class UserBuilder
{
    private string $name;
    private string $surname;
    private int $age;
    private string $email;
    private string $password;

    public function __construct()
    {
        $faker = Factory::create();

        $this->name = $faker->text(15);
        $this->surname = $faker->text(15);
        $this->age = $faker->numberBetween(18, 70);
        $this->email = $faker->email;
        $this->password = "Passwor&d1234";
    }

    public function withName(string $name): UserBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function withSurname(string $surname): UserBuilder
    {
        $this->surname = $surname;
        return $this;
    }

    public function withAge(int $age): UserBuilder
    {
        $this->age = $age;
        return $this;
    }

    public function withEmail(string $email): UserBuilder
    {
        $this->email = $email;
        return $this;
    }

    public function withPassword(string $password): UserBuilder
    {
        $this->password = $password;
        return $this;
    }

    public function build(): User
    {
        return User::factory()->create([
            'name' => $this->name,
            'surname' => $this->surname,
            'age' => $this->age,
            'email' => $this->email,
            'password' => $this->password,
        ]);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'surname' => $this->surname,
            'age' => $this->age,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }
}
