<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Builders\ActivityBuilder;
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
        $this->user = User::factory()->create();
    }

    public function testCanImportActivitiesSuccessfully()
    {
        $activity1 = (new ActivityBuilder())->toArray();
        $activity2 = (new ActivityBuilder())->toArray();

        $data = [$activity1, $activity2];

        $response = $this->postImportActivities($data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('activities', $activity1);
        $this->assertDatabaseHas('activities', $activity2);
    }

    public function testCannotImportActivitiesWhenNotAuthenticated(): void
    {
        $data = [
            (new ActivityBuilder())->toArray(),
            (new ActivityBuilder())->toArray()
        ];

        $response = $this->postJson(route('activities.import'), $data);

        $response->assertStatus(401);
    }

    private function postImportActivities(array $data): TestResponse
    {
        $token = $this->user->createToken('authToken')->accessToken;

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activities.import'), $data);
    }

    #[DataProvider('invalidActivityDataProvider')]
    public function testCannotImportActivitiesWithInvalidData(array $invalidData): void
    {
        $data = [(new ActivityBuilder())->toArray()];
        $data[0] = array_merge($data[0], $invalidData);

        $response = $this->postImportActivities($data);

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
