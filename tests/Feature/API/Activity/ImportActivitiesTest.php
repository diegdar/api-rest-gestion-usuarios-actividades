<?php

declare(strict_types=1);

namespace Tests\Feature\API\Activity;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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

    public function testAuthenticatedUserCanImportActivitiesSuccessfully(): void
    {
        $file = $this->createJsonFile();
        $response = $this->postImportActivities($file);

        $response->assertStatus(200);
        $this->assertDatabaseHas('activities', [
            'name' => 'Sesión de Yoga',
            'description' => 'Clase de yoga para relajarse y estirar los músculos.',
            'max_capacity' => 20,
            'start_date' => '2024-10-15',
        ]);

        $this->assertDatabaseHas('activities', [
            'name' => 'Taller de cocina',
            'description' => 'Aprender a cocinar platos mediterráneos.',
            'max_capacity' => 15,
            'start_date' => '2024-11-01',
        ]);
    }

    public function testCannotImportActivitiesWhenNotAuthenticated(): void
    {
        $file = $this->createJsonFile();
        $response = $this->postJson(route('activities.import'), [
            'file' => new \Illuminate\Http\UploadedFile($file, 'activities.json', 'application/json', null, true),
        ]);

        $response->assertStatus(401);
    }

    public function testCannotImportInvalidFileFormat(): void
    {
        $file = $this->createInvalidFile();
        $response = $this->postImportActivities($file);

        $response->assertStatus(422);
    }

    private function postImportActivities($file): \Illuminate\Testing\TestResponse
    {
        $token = $this->user->createToken('authToken')->accessToken;

        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->postJson(route('activities.import'), [
            'file' => new \Illuminate\Http\UploadedFile($file, 'activities.json', 'application/json', null, true),
        ]);
    }

    private function createJsonFile(): string
    {
        $jsonContent = [
            [
                'name' => 'Sesión de Yoga',
                'description' => 'Clase de yoga para relajarse y estirar los músculos.',
                'max_capacity' => 20,
                'start_date' => '2024-10-15',
            ],
            [
                'name' => 'Taller de cocina',
                'description' => 'Aprender a cocinar platos mediterráneos.',
                'max_capacity' => 15,
                'start_date' => '2024-11-01',
            ],
        ];

        $filePath = tempnam(sys_get_temp_dir(), 'activities_');
        file_put_contents($filePath, json_encode($jsonContent));
        return $filePath;
    }

    private function createInvalidFile(): string
    {
        $filePath = tempnam(sys_get_temp_dir(), 'invalid_');
        file_put_contents($filePath, "Invalid file content");
        return $filePath;
    }

}
