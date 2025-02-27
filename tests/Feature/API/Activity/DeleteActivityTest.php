<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\ActivityTestHelper;
use App\Helpers\UserTestHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteActivityTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper, ActivityTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function requestDeleteActivity(int $activityId = null, string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('activity.delete', $activityId));
    }
    
    public function testItCanDeleteActivitySuccessfully(): void
    {
        $user = $this->createUser(role:'Admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, $token);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    public function testItCannotDeleteActivityWhenUserIsNotAnAdmin():void
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, $token);

        $response->assertStatus(403);
    }

    public function testCannotDeleteActivityWhenUserIsNotAuthenticated():void
    {
        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, null);

        $response->assertStatus(401);
    }

    public function testCannotDeleteNonExistentActivity(): void
    {
        $user = $this->createUser();
        $token = $this->getUserToken($user);

        $response = $this->requestDeleteActivity(9999, $token);

        $response->assertStatus(404);
    }

    public function testCannotDeleteActivityWhenUserIsNotAdmin(): void
    {
        $user = $this->createUser(role: 'User');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, $token);

        $response->assertStatus(403);
    }
}
