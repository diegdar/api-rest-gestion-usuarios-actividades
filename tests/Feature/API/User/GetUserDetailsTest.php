<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Helpers\UserTestHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetUserDetailsTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;
    public function setUp(): void
    {
        parent::setUp();
    }

    private function requestGetUserDetails(int $userId = null, string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('user.details', $userId));
    }

    public function testCanGetAnUserDetails(): void
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        $response = $this->requestGetUserDetails($user->id, $token);

        $response->assertStatus(200);
        $response->assertJson(['userData' => $user->toArray()]);
    }

    public function testCanShow_401AndDeniedAccessWhenUserDoesNotExist(): void
    {
        $userNotExists = 99999999;
        $response = $this->requestGetUserDetails($userNotExists);

        $response->assertStatus(401);
    }

    public function testCanShow_401AndDeniedAccessWhenUserIsNotAuthenticated(): void
    {
        $user = $this->createUser();

        $response = $this->requestGetUserDetails($user->id);
        $response->assertStatus(401);
    }
}
