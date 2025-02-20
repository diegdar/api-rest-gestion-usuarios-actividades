<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserActivitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            "name" => "Joe",
            "surname" => "Doe",
            "age" => 25,
            "email"=> "joedoe@gmail.com",
            "password"=> bcrypt("1234%pasword"),
        ])->assignRole('User');
        $activity = Activity::where('name', 'cycling')->first();
        $user->activities()->attach($activity);

        $users = User::all();
        foreach ($users as $user) {
            $activities = Activity::inRandomOrder()->take(rand(1, 4))->get();
            $user->activities()->attach($activities);
        }
    }
}
