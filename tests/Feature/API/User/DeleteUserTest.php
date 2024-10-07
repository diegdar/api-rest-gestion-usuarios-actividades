<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanDeleteUserSuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('user.delete', $this->user));

        $response->assertStatus(200);
        $response->assertJson(['message' => 'The user has been deleted successfully']);

        $this->assertNull(User::find($this->user->id));
    }

    public function testCannotDeleteUserWhenNotAuthenticated(): void
    {
        $response = $this->deleteJson(route('user.delete', $this->user));

        $response->assertStatus(401);
    }

    public function testCannotDeleteNonExistentUser(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('user.delete', 999999));

        $response->assertStatus(404);
    }
}
