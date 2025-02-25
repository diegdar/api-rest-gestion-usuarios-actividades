<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class CreateActivityTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->assignRole('Admin');
    }

    private function CreateActivityData(): array
    {
        return Activity::factory()->make()->toArray();
    }

    public function testCanCreateActivitySuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $activityData = $this->CreateActivityData();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activity.create'), $activityData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'La actividad se ha creado correctamente']);

        $this->assertDatabaseHas('activities', $activityData);
    }

    public function testCannotCreateActivityWhenNotAuthenticated(): void
    {
        $activityData = $this->CreateActivityData();

        $response = $this->postJson(route('activity.create'), $activityData);

        $response->assertStatus(401);
    }

    #[DataProvider('activityValidationProvider')]
    public function testCannotCreateActivityWithInvalidData(array $invalidData, array $expectedErrors): void
    {
        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activity.create'), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function activityValidationProvider(): array
    {
        return [
            'missing name' => [
                [
                    'description' => 'This is a new activity.',
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['name'],
            ],
            'missing description' => [
                [
                    'name' => 'New Activity',
                    'max_capacity' => 50,
                    'start_date' => '2024-10-15',
                ],
                ['description'],
            ],
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
