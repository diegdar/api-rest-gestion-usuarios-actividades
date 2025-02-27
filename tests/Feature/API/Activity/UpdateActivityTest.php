<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Helpers\ActivityTestHelper;
use App\Helpers\UserTestHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateActivityTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper, ActivityTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    } 

    private function requestUpdateActivity(int $activityId = null, string $token = null, array $dataToUpdate ): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('activity.update', $activityId), $dataToUpdate);
    }

    public function testCanUpdateActivity(): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();
        $activityData = $activity->toArray();
        $dataToUpdate = [
            'name' => 'Updated Activity',
            'description' => 'This is an updated activity.',
            'max_capacity' => 50,
        ];

        $response = $this->requestUpdateActivity($activity->id, $token, $dataToUpdate);

        $updatedData = array_merge($activityData, $dataToUpdate);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activities', $updatedData);
    }

    public function testCannotUpdateNonExistentActivity(): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $this->getUserToken($user);
        $dataToUpdate = [
            'name' => 'Updated Activity',
            'description' => 'This is an updated activity.',
            'max_capacity' => 50,
        ];  

        $response = $this->requestUpdateActivity(9999, $token, $dataToUpdate);        

        $response->assertStatus(404);
    }

    public function testCannotUpadteActivityWhenUserIsNotAuthenticated():void
    {
        $activity = $this->CreateActivity();
        $dataToUpdate = [
            'name' => 'Updated Activity',
            'description' => 'This is an updated activity.',
            'max_capacity' => 50,
        ];  

        $response = $this->requestUpdateActivity($activity->id, null, $dataToUpdate); 

        $response->assertStatus(401);
    }

    public function testCannotUpdateActivityWhenUserIsNotAnAdmin():void
    {
        $user = $this->createUser(role: 'User');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();
        $dataToUpdate = [
            'name' => 'Updated Activity',
            'description' => 'This is an updated activity.',
            'max_capacity' => 50,
        ];

        $response = $this->requestUpdateActivity($activity->id, $token, $dataToUpdate);

        $response->assertStatus(403);
    }

    #[DataProvider('activityValidationProvider')]
    public function testCanValidateActivityFields(array $invalidDataToUpdate, array $field): void
    {
        $user = $this->createUser(role: 'Admin');
        $token = $this->getUserToken($user);
        $activity = $this->CreateActivity();
    
        $response = $this->requestUpdateActivity($activity->id, $token, $invalidDataToUpdate);
    
        $response->assertStatus(422);
        $response->assertJsonValidationErrors($field);
    }
    
    public static function activityValidationProvider(): array
    {
        return [
            // NAME
            'invalid name (not a string)' => [
                ['name' => 123],
                ['name'],
            ],
            'invalid name (too long)' => [
                ['name' => self::generateLongText(256)],
                ['name'],
            ],
            'invalid name (empty)' => [
                ['name' => ''],
                ['name'],
            ],
            'invalid name (null)' => [
                ['name' => null],
                ['name'],
            ],            
    
            // DESCRIPTION
            'invalid description (not a string)' => [
                ['description' => 12345],
                ['description'],
            ],
            'invalid description (too long)' => [
                ['description' => self::generateLongText(256)],
                ['description'],
            ],
            'invalid description (empty)' => [
                ['description' => ''],
                ['description'],
            ],
            'invalid description (null)' => [
                ['description' => null],
                ['description'],
            ],            
    
            // MAX_CAPACITY
            'invalid max_capacity (not integer)' => [
                ['max_capacity' => 'fifty'],
                ['max_capacity'],
            ],
            'invalid max_capacity (not greater than 0)' => [
                ['max_capacity' => 0],
                ['max_capacity'],
            ],
            'invalid max_capacity (negative)' => [
                ['max_capacity' => -5],
                ['max_capacity'],
            ],            
            'invalid max_capacity (too large)' => [
                ['max_capacity' => 80],
                ['max_capacity'],
            ],
    
            // START_DATE
            'invalid start_date (not a date)' => [
                ['start_date' => 'invalid-date'],
                ['start_date'],
            ],
            'invalid start_date (empty)' => [
                ['start_date' => ''],
                ['start_date'],
            ],
            'invalid start_date (null)' => [
                ['start_date' => null],
                ['start_date'],
            ],
            'invalid start_date (past date)' => [
                ['start_date' => now()->subDay()->toDateString()],
                ['start_date'],
            ],
        ];
    }    
    
    /**
     * Generates a string of the exact length specified by the `$length` parameter.
     */
    private static function generateLongText(int $length): string
    {
        $faker = \Faker\Factory::create();
        return $faker->regexify("[a-zA-Z0-9]{{$length}}");
    }
    
}
