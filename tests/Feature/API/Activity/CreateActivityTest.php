<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\UserTestHelper;
use App\Models\Activity;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateActivityTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function CreateActivityData(): array
    {
        return Activity::factory()->make()->toArray();
    }

    private function requestCreateActivity(array $activityData, string $token): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
            ])->postJson(route('activity.create'), $activityData);
    }

    public function testCanCreateActivitySuccessfully(): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $user->createToken('authToken')->accessToken;

        $activityData = $this->CreateActivityData();

        $response = $this->requestCreateActivity($activityData, $token);

        $response->assertStatus(200);

        $this->assertDatabaseHas('activities', $activityData);
    }

    public function testCannotCreateActivityWhenNotAuthenticated(): void
    {
        $activityData = $this->CreateActivityData();

        $response = $this->postJson(route('activity.create'), $activityData);

        $response->assertStatus(401);
    }

    public function testCanShowValidationErrorWhenActivityIsDuplicated(): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $user->createToken('authToken')->accessToken;
        $activityData = $this->CreateActivityData();

        $this->requestCreateActivity($activityData, $token);

        $response = $this->requestCreateActivity($activityData, $token);

        $response->assertStatus(422);
    }

    #[DataProvider('activityValidationProvider')]
    public function testCanShowValidationErrorWithInvalidData(array $invalidData, array $fieldName): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->requestCreateActivity($invalidData, $token);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($fieldName);
    }

    public static function activityValidationProvider(): array
    {
        return [
        // name
            'missing name' => [
                [
                    'description' => 'This is a new activity.',
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['name'],
            ],
            'invalid name (not a string)' => [
                [
                    'name' => 123,
                    'description' => 'This is a new activity.',
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['name'],
            ],
        // description
            'missing description' => [
                [
                    'name' => 'New Activity',
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['description'],
            ],
            'invalid description (not a string)' => [
                [
                    'name' => 'New Activity',
                    'description' => 123,
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['description'],
            ],
            // max_capacity
            'missing max_capacity' => [
                [
                    'name' => 'New Activity',
                    'description' => 'This is a new activity.',
                    'start_date' => '2024-10-15',
                ],
                ['max_capacity'],
            ],
            'invalid max_capacity (not integer)' => [
                [
                    'name' => 'New Activity',
                    'description' => 'This is a new activity.',
                    'max_capacity' => 'fifty',
                    'start_date' => '2024-10-15',
                ],
                ['max_capacity'],
            ],
            'invalid max_capacity (not greater than 0)' => [
                [
                    'name' => 'New Activity',
                    'description' => 'This is a new activity.',
                    'max_capacity' => 0,
                    'start_date' => '2024-10-15',
                ],
                ['max_capacity'],
            ],
        // start_date
            'missing start_date' => [
                [
                    'name' => 'New Activity',
                    'description' => 'This is a new activity.',
                    'max_capacity' => 50,
                ],
                ['start_date'],
            ],
            'invalid start_date (not a date)' => [
                [
                    'name' => 'New Activity',
                    'description' => 'This is a new activity.',
                    'max_capacity' => 50,
                    'start_date' => 'invalid-date',
                ],
                ['start_date'],
            ],
        ];
    }
}
