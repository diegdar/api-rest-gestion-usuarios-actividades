<?php

namespace Tests\Feature;

use App\Http\Controllers\LoginController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LoginUserTest extends TestCase
{

    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanInstanciateLoginController(): void
    {
        $this->assertInstanceOf(LoginController::class, new LoginController());
    }

    private function createUserData(): array
    {
        return [
            'name' => 'test_name',
            'surname' => 'test_surname',
            'age' => 30,
            'email' => 'test@example.com',
            'password' => 'PassworTest$983',
        ];
    }

    public function testCanLoginSuccessfully(): void
    {
        $userData = $this->createUserData();
        $user = User::create($userData);

        $response = $this->postJson(route('user.login'), [
            'email' => $userData['email'],
            'password' => $userData['password'],
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
        $response->assertJsonPath('data.id', $user->id);
    }

    public function testCanShow_401WhithIncorrectPassword()
    {
        $userData = $this->createUserData();
        User::create($userData);

        $userData['password'] = 'wrongPassword';
        $response = $this->postJson(route('user.login'), $userData);

        $response->assertStatus(401);

    }

    public function testCanShow_401WhithIncorrectEmail(): void
    {
        $userData = $this->createUserData();
        User::create($userData);

        $userData['email'] = 'wrongEmail@test.com';
        $response = $this->postJson(route('user.login'), $userData);

        $response->assertStatus(401);
    }

}
