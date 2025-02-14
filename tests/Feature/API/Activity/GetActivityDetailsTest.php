<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\User;
use App\tests\Mothers\ActivityMother;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetActivityDetailsTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function testCanGetActivityDetailsSuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        ['activity' => $activity, 'data' => $activityData] = ActivityMother::random();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('activity.details', $activity->id));

        $response->assertStatus(200);
        $response->assertJson(['activityData' => $activityData]);
    }

    public function testCannotGetActivityDetailsWhenNotAuthenticated(): void
    {
        ['activity' => $activity, ] = ActivityMother::random();

        $response = $this->getJson(route('activity.details', $activity->id));

        $response->assertStatus(401);
    }

    public function testCannotGetActivityDetailsOfNonExistentActivity(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('activity.details', 999999));

        $response->assertStatus(404);

    }

}
