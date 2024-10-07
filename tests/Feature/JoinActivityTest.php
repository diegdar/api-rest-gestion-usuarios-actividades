<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class JoinActivityTest extends TestCase
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

    public function testAuthenticatedUserCanJoinActivitySuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('user.activity.join', [
            'user' => $this->user->id,
            'activity' => $this->activity->id,
        ]));

        $response->assertStatus(200);

        $this->assertDatabaseHas('activity_user', [
            'user_id' => $this->user->id,
            'activity_id' => $this->activity->id,
        ]);
    }

    public function testCannotJoinActivityWhenNotAuthenticated(): void
    {
        $response = $this->postJson(route('user.activity.join', [
            'user' => $this->user->id,
            'activity' => $this->activity->id,
        ]));

        $response->assertStatus(401);
    }

    public function testCannotJoinTheSameActivityTwice(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('user.activity.join', [
            'user' => $this->user->id,
            'activity' => $this->activity->id,
        ]));

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('user.activity.join', [
            'user' => $this->user->id,
            'activity' => $this->activity->id,
        ]));

        $response->assertStatus(409);
    }
}
