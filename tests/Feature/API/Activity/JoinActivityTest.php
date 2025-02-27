<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\ActivityTestHelper;
use App\Helpers\UserTestHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class JoinActivityTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper, ActivityTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function requestJoinActivity(int $userId = null, int $activityId = null, string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('user.activity.join', [
            'user' => $userId,
            'activity' => $activityId,
        ]));
    }

    public function testAuthenticatedUserCanJoinActivitySuccessfully(): void
    {
        $user = $this->createUser(role: 'admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();

        $response = $this->requestJoinActivity($user->id, $activity->id, $token);

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_user', [
            'user_id' => $user->id,
            'activity_id' => $activity->id,
        ]);
    }

    public function testCannotJoinActivityWhenNotAuthenticated(): void
    {
        $user = $this->createUser(role: 'admin');
        $activity = $this->CreateActivity();        
        $response = $this->postJson(route('user.activity.join', [
            'user' => $user->id,
            'activity' => $activity->id,
        ]));

        $response->assertStatus(401);
    }

    public function testCannotJoinTheSameActivityTwice(): void
    {
        $user = $this->createUser(role: 'admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();

        $this->requestJoinActivity($user->id, $activity->id, $token);

        $response = $this->requestJoinActivity($user->id, $activity->id, $token);

        $response->assertStatus(409);
    }
}
