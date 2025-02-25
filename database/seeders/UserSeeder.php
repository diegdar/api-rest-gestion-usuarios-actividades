<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Jane",
            "surname" => "Doe",
            "age" => 23,
            "email"=> "janedoe@gmail.com",
            "password"=> bcrypt("1234%pasword"),
        ])->assignRole('Admin');

        User::create([
            "name" => "Joe",
            "surname" => "Doe",
            "age" => 25,
            "email"=> "joedoe@gmail.com",
            "password"=> bcrypt("1234%pasword"),
        ])->assignRole('User');

        User::create([
            "name" => "Ana",
            "surname" => "Ruiz",
            "age" => 25,
            "email"=> "anaruiz@gmail.com",
            "password"=> bcrypt("1234%pasword"),
        ])->assignRole('User');

        User::factory(10)->create()->assignRole('User');
    }
}
