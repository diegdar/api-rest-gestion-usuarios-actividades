<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\ActivityTestHelper;
use App\Helpers\UserTestHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class GetActivityDetailsTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper, ActivityTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function requestGetActivityDetails(?int $activityId = null, ?string $token = null): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('activity.details', $activityId));
    }

    public function testCanGetActivityDetailsSuccessfully(): void
    {
        $user = $this->createUser(role: 'admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();
        $activityData = $activity->toArray();
        $activityData['start_date'] = $activity->start_date->format('Y-m-d');

        $response = $this->requestGetActivityDetails($activity->id, $token);

        $response->assertStatus(200);
        $response->assertJson([$activityData]);
    }

    public function testCannotGetActivityDetailsWhenNotAuthenticated(): void
    {
        $activity = $this->CreateActivity();

        $response = $this->getJson(route('activity.details', $activity->id));

        $response->assertStatus(401);
    }

    public function testCannotGetActivityDetailsOfNonExistentActivity(): void
    {
        $user = $this->createUser(role: 'admin');
        $token = $this->getUserToken($user);

        $response = $this->requestGetActivityDetails(9999, $token);
        $response->assertStatus(404);

    }

}
