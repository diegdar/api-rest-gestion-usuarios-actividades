<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    use DatabaseTransactions;
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    private function getUserData(): array
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'Password&1234';

        return $userData;
    }    

    private function putUpdateUser(int $user = null, string $token = null, array $dataToUpdate ): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('user.update', $user), $dataToUpdate);
    }            

    public function testCanUpdateUserSuccessfully(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;
        $updatedData = User::factory()->make([
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ])->toArray();

        $response = $this->putUpdateUser($this->user->id, $token, $updatedData);
        $response->assertStatus(200);

        $this->user->refresh(); // Reload the user data
        $this->assertEquals($updatedData['name'], $this->user->name);
        $this->assertEquals($updatedData['surname'], $this->user->surname);
        $this->assertEquals($updatedData['age'], $this->user->age);
        $this->assertEquals($updatedData['email'], $this->user->email);
    }

    public function testCannotUpdateUserWhenNotAuthenticated(): void
    {
        $updatedData = User::factory()->make([
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ])->toArray();

        $response = $this->putUpdateUser($this->user->id, null, $updatedData);
        $response->assertStatus(401);
    }

    public function testCannotUpdateNonExistentUser(): void
    {
        $token = $this->user->createToken('authToken')->accessToken;
        $updatedData = User::factory()->make([
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ])->toArray();

        $response = $this->putUpdateUser(9999, $token, $updatedData);
        $response->assertStatus(404);
    }


    #[DataProvider('userValidationProvider')]
    public function testCanValidateUserFields(array $invalidData): void
    {
        $data = $this->getUserData();
        $token = $this->user->createToken('authToken')->accessToken;
        $data = array_merge($data, $invalidData);

        $response = $this->putUpdateUser($this->user->id, $token, $data);
        $response->assertStatus(422);
    }

    public static function userValidationProvider(): array
    {
        return [
            'missing_name' => [['name' => null]],
            'invalid_name' => [['name' => 123]],
            'missing_surname' => [['surname' => null]],
            'invalid_surname' => [['surname' => 456]],
            'missing_age' => [['age' => null]],
            'invalid_age' => [['age' => 'not-a-number']],
            'invalid_email' => [['email' => 'not-an-email']],
            'invalid_password' => [['password' => 'PassworTest983']],
        ];
    }
}
