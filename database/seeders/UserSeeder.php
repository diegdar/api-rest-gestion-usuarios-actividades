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
        $user = User::factory()->create([
            'name' => 'Joe',
            'surname' => 'Doe',
            'age' => 30,
            'email' => 'Joe@example.com',
            'password' => 'password',
        ]);
        $user->save();

        User::factory(10)->create();
    }
}
