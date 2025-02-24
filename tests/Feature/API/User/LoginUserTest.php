<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use DatabaseTransactions;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->assignRole('User');
    }

    private function postLogin(array $data): TestResponse
    {
        return $this->postJson(route('user.login'), $data);
    }

    public function testCanLoginSuccessfully(): void
    {
        $userData = $this->user->toArray();

        $response = $this->postLogin([
            'email' => $userData['email'],
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
        $response->assertJsonPath('data.id', User::where('email', $userData['email'])->first()->id);
    }

    public function testCanShow_401WhithIncorrectPassword()
    {
        $userData = $this->user->toArray();

        $response = $this->postLogin([
            'email' => $userData['email'],
            'password' => 'wrongPassword',
        ]);

        $response->assertStatus(401);
    }

    public function testCanShow_401WhithIncorrectEmail(): void
    {
        $response = $this->postLogin([
            'email' => 'wrongEmail@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }
}