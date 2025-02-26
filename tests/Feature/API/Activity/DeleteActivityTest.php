<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\Activity;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class DeleteActivityTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createUserAndGetToken(string $role = 'Admin'): string
    {
        return User::factory()->create()->assignRole($role)->createToken('authToken')->accessToken;
    }

    private function CreateActivity(): Activity
    {
        return Activity::factory()->create();
    }

    private function requestDeleteActivity(int $activityId = null, string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->deleteJson(route('activity.delete', $activityId));
    }
    
    public function testCanrequestDeleteActivitySuccessfully(): void
    {
        $token = $this->createUserAndGetToken();
        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, $token);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('activities', ['id' => $activity->id]);
    }

    public function testCannotrequestDeleteActivityWhenUserIsNotAnAdmin():void
    {
        $token = $this->createUserAndGetToken('User');
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
        $token = $this->createUserAndGetToken();

        $response = $this->requestDeleteActivity(9999, $token);

        $response->assertStatus(404);
    }

    public function testCannotDeleteActivityWhenUserIsNotAdmin(): void
    {
        $token = $this->createUserAndGetToken('User');

        $activity = $this->CreateActivity();

        $response = $this->requestDeleteActivity($activity->id, $token);

        $response->assertStatus(403);
    }
}
