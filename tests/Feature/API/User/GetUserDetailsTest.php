<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    use DatabaseTransactions;
    protected User $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanInstanciateUserController(): void
    {
        $this->assertInstanceOf(UserController::class, new UserController());
    }

    public function testCanGetAnUserDetails(): void
    {
        $user = $this->user;
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            ])->getJson(route('user.details', $user));

        $response->assertStatus(200);
        $response->assertJson([
            'userData' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'age' => $user->age,
                'email' => $user->email,
            ]
        ]);
    }

    public function testCanShow_401AndDeniedAccessWhenUserDoesNotExist(): void
    {
        $userNotExists = 99999999;
        $response = $this->getJson(route('user.details', 99999999));

        $response->assertStatus(401);
    }

    public function testCanShow_401AndDeniedAccessWhenUserIsNotAuthenticated(): void
    {
        $user = $this->user;

        $response = $this->getJson(route('user.details', $user));

        $response->assertStatus(401);
    }
}
