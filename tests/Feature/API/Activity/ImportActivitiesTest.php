<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\UserTestHelper;
use App\Models\Activity;
use PHPUnit\Framework\Attributes\DataProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ImportActivitiesTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function createActivitiesData(int $count = 2): array
    {
        $activities = [];
        for ($i = 0; $i < $count; $i++) {
            $activities[] = Activity::factory()->make()->toArray();
        }
        return $activities;
    }

    private function requestImportActivities(array $data): TestResponse
    {
        $user = $this->createUser(role: 'admin');
        $token = $this->getUserToken($user);

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activities.import'), $data);
    }

    public function testCanImportActivitiesSuccessfully()
    {
        $activities = $this->createActivitiesData();
        $response = $this->requestImportActivities($activities);

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

        $response = $this->requestImportActivities($activities);

        $response->assertStatus(422);
    }

    public static function invalidActivityDataProvider(): array
    {
        return [
            // name
                'missing_name' => [['name' => null], ['name']],
                'invalid_name' => [['name' => 123], ['name']],
            // description
                'missing_description' => [['description' => null], ['description']],
                'invalid_description' => [['description' => 123], ['description']],
            // max_capacity
                'missing_max_capacity' => [['max_capacity' => null], ['max_capacity']],
                'invalid_max_capacity_zero' => [['max_capacity' => 0], ['max_capacity']],
                'invalid_max_capacity_string' => [['max_capacity' => 'abc'], ['max_capacity']],
            // start_date
                'missing_start_date' => [['start_date' => null], ['start_date']],
                'invalid_start_date' => [['start_date' => 'invalid date'], ['start_date']],
            ];        
    }
}