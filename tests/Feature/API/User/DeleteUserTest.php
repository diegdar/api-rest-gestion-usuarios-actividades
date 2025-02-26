<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createUser(string $role = 'User'): User
    {
        return User::factory()->create()->assignRole($role);
    }

    private function getUserToken(User $user)
    {
        return $user->createToken('authToken')->accessToken;
    }

    private function requestDeleteUser(int $userId = null, string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('user.delete', $userId));
    }

    public function testCanDeleteUserSuccessfully(): void
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        $response = $this->requestDeleteUser($user->id, $token);

        $response->assertStatus(200);
        $this->assertNull(User::find($user->id));
    }

    public function testCannotDeleteUserWhenNotAuthenticated(): void
    {
        $user = $this->createUser();

        $response = $this->requestDeleteUser($user->id, null);

        $response->assertStatus(401);
    }

    public function testCannotDeleteNonExistentUser(): void
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        $response = $this->requestDeleteUser(999999, $token);

        $response->assertStatus(404);
    }

    public function testCannotDeleteUserThatIsNotTheOwner(): void
    {
        $user1 = $this->createUser();
        $user2 = $this->createUser();
        $tokenUser1 = $this->getUserToken($user1);

        $response = $this->requestDeleteUser($user2->id, $tokenUser1);

        $response->assertStatus(403);
    }
}
