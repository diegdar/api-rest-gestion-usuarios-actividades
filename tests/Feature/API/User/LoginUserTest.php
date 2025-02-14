<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Builders\UserBuilder;
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

    public function testCanLoginSuccessfully(): void
    {
        $userData = (new UserBuilder())->toArray();
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
        $userData = (new UserBuilder())->toArray();
        User::create($userData);

        $userData['password'] = 'wrongPassword';
        $response = $this->postJson(route('user.login'), $userData);

        $response->assertStatus(401);

    }

    public function testCanShow_401WhithIncorrectEmail(): void
    {
        $userData = (new UserBuilder())->toArray();
        User::create($userData);

        $userData['email'] = 'wrongEmail@test.com';
        $response = $this->postJson(route('user.login'), $userData);

        $response->assertStatus(401);
    }

}
