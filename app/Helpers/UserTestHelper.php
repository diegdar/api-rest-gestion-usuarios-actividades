<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\User;

trait UserTestHelper
{
    private function createUser(string $email = 'test@example.com', string $role = 'User'): User
    {
        return User::factory()->create([
            'email' => $email,
            'password' => bcrypt('Password&123'),
        ])->assignRole($role);
    }    

    private function getUserToken(User $user)
    {
        return $user->createToken('authToken')->accessToken;
    }      
}
