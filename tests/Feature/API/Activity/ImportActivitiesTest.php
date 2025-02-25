<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\Activity;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ImportActivitiesTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create()->assignRole('Admin');
    }

    private function createActivitiesData(int $count = 2): array
    {
        $activities = [];
        for ($i = 0; $i < $count; $i++) {
            $activities[] = Activity::factory()->make()->toArray();
        }
        return $activities;
    }

    private function postImportActivities(array $data): TestResponse
    {
        $token = $this->user->createToken('authToken')->accessToken;
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activities.import'), $data);
    }

    public function testCanImportActivitiesSuccessfully()
    {
        $activities = $this->createActivitiesData();
        $response = $this->postImportActivities($activities);

        $response->assertStatus(201);
        foreach ($activities as $activity) {
            $this->assertDatabaseHas('activities', $activity);
        }
    }

    public function testCannotImportActivitiesWhenNotAuthenticated(): void
    {
        $activities = $this->createActivitiesData();
        $response = $this->postJson(route('activities.import'), $activities);
        $response->assertStatus(401);
    }

    #[DataProvider('invalidActivityDataProvider')]
    public function testCannotImportActivitiesWithInvalidData(array $invalidData): void
    {
        $activities = $this->createActivitiesData();
        $activities[0] = array_merge($activities[0], $invalidData);
        $response = $this->postImportActivities($activities);
        $response->assertStatus(422);
    }

    public static function invalidActivityDataProvider(): array
    {
        return [
            'missing_name' => [['name' => null]],
            'invalid_name' => [['name' => 123]],
            'missing_description' => [['description' => null]],
            'invalid_description' => [['description' => 123]],
            'missing_max_capacity' => [['max_capacity' => null]],
            'invalid_max_capacity_zero' => [['max_capacity' => 0]],
            'invalid_max_capacity_string' => [['max_capacity' => 'abc']],
            'missing_start_date' => [['start_date' => null]],
            'invalid_start_date' => [['start_date' => 'invalid date']],
        ];
    }
}