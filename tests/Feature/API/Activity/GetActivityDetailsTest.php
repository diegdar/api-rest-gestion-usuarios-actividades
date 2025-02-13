<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Builders\ActivityBuilder;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class GetActivityDetailsTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected ActivityBuilder $activity;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->activity = new ActivityBuilder();
    }

    public function testCanGetActivityDetailsSuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $activity = $this->activity->build(); // Crea la actividad en la base de datos

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->getJson(route('activity.details', $activity->id));

        $response->assertStatus(200);
        $response->assertJson(['activityData' => $this->activity->toArray()]);
    }

    public function testCannotGetActivityDetailsWhenNotAuthenticated(): void
    {
        $activity = $this->activity->build();

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
// class GetActivityDetailsTest extends TestCase
// {
//     use DatabaseTransactions;

//     protected User $user;
//     protected Activity $activity;

//     protected function setUp(): void
//     {
//         parent::setUp();
//         $this->user = User::factory()->create();
//         $this->activity = Activity::factory()->create();
//     }

//     public function testCanGetActivityDetailsSuccessfully(): void
//     {
//         $token = $this->user->createToken('authToken')->accessToken;

//         $response = $this->withHeaders([
//             'Authorization' => 'Bearer ' . $token,
//             'Accept' => 'application/json',
//         ])->getJson(route('activity.details', [$this->activity]));

//         $response->assertStatus(200);
//         $response->assertJson([
//             'activityData' => [
//                 'id' => $this->activity->id,
//                 'name' => $this->activity->name,
//                 'description' => $this->activity->description,
//                 'max_capacity' => $this->activity->max_capacity,
//                 'start_date' => $this->activity->start_date->format('Y-m-d')
//             ],
//         ]);
//     }

//     public function testCannotGetActivityDetailsWhenNotAuthenticated(): void
//     {
//         $response = $this->getJson(route('activity.details', [$this->activity]));

//         $response->assertStatus(401);
//     }

//     public function testCannotGetActivityDetailsOfNonExistentActivity(): void
//     {
//         $token = $this->user->createToken('authToken')->accessToken;

//         $response = $this->withHeaders([
//             'Authorization' => 'Bearer ' . $token,
//             'Accept' => 'application/json',
//         ])->getJson(route('activity.details', 999999));

//         $response->assertStatus(404);
//         // $response->assertJson([
//         //     'message' => 'Activity not found',
//         // ]);
//     }

// }
