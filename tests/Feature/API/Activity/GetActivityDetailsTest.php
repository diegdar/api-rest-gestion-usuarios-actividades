<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetActivityDetailsTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected Activity $activity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->activity = Activity::factory()->create();
    }

    public function testCanGetActivityDetailsSuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('activity.details', $this->activity->id));

        $response->assertStatus(200);
        $response->assertJson(['activityData' => $this->activity->toArray()]);
    }

    public function testCannotGetActivityDetailsWhenNotAuthenticated(): void
    {
        $response = $this->getJson(route('activity.details', $this->activity->id));

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
