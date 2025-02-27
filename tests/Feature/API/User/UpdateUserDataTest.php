<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Helpers\UserTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UpdateUserDataTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function getUserData(User $user): array
    {
        $userData = $user->toArray();
        $userData['password'] = 'Password&1234';

        return $userData;
    }

    private function requestUpdateUser(int $user = null, string $token = null, array $dataToUpdate): TestResponse
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('user.update', $user), $dataToUpdate);
    }

    public function testCanUpdateUserSuccessfully(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('authToken')->accessToken;
        $updatedData = [
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ];

        $response = $this->requestUpdateUser($user->id, $token, $updatedData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('users', $updatedData);
    }

    public function testCannotUpdateUserWhenNotAuthenticated(): void
    {
        $user = $this->createUser();
        $updatedData = [
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ];

        $response = $this->requestUpdateUser($user->id, null, $updatedData);
        $response->assertStatus(401);
    }

    public function testCannotUpdateNonExistentUser(): void
    {
        $user = $this->createUser();
        $token = $user->createToken('authToken')->accessToken;
        $updatedData = [
            'name' => 'updated_name',
            'surname' => 'updated_surname',
        ];

        $response = $this->requestUpdateUser(9999, $token, $updatedData);
        $response->assertStatus(404);
    }

    public function testCanShowValidationErrorWhenThereIsADuplicateEmail(): void
    {
        $user1 = $this->createUser('user1@example.com');
        $user2 = $this->createUser('user2@example.com');
        $token = $user1->createToken('authToken')->accessToken;
        $data = $this->getUserData($user2);

        $response = $this->requestUpdateUser($user1->id, $token, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    #[DataProvider('userValidationProvider')]
    public function testCanValidateUserFields(array $invalidData, array $field): void
    {
        $user = $this->createUser();
        $data = $this->getUserData($user);
        $token = $user->createToken('authToken')->accessToken;
        $data = array_merge($data, $invalidData);

        $response = $this->requestUpdateUser($user->id, $token, $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($field);
    }

    public static function userValidationProvider(): array
    {
        return [
        // name
            'missing name (it is required)' => [['name' => null], ['name']],
            'invalid name (it is integer)' => [['name' => 123], ['name']],
            'invalid name (too long)' => [['name' => str_repeat('a', 256)], ['name']],
        // surname
            'missing surname (it is required)' => [['surname' => null], ['surname']],
            'invalid surname (it is integer)' => [['surname' => 456], ['surname']],
            'invalid surname (too long)' => [['surname' => str_repeat('a', 256)], ['surname']],
        // age
            'missing age (it is required)' => [['age' => null], ['age']],
            'invalid age (it is not a number)' => [['age' => 'not-a-number'], ['age']],
            'invalid age (too low)' => [['age' => 9], ['age']],
            'invalid age (too high)' => [['age' => 91], ['age']],
        // email
            'invalid email (it is not an email)' => [['email' => 'not-an-email'], ['email']],
            'invalid email (too long)' => [['email' => str_repeat('a', 256) . '@example.com'], ['email']],
        // password
            'invalid password (too short)' => [['password' => 'short'], ['password']],
            'invalid password (too long)' => [['password' => str_repeat('a', 21)], ['password']],
            'invalid password (no uppercase)' => [['password' => 'password123!'], ['password']],
            'invalid password (no number)' => [['password' => 'Password!'], ['password']],
            'invalid password (no special char)' => [['password' => 'Password123'], ['password']],
        ];
    }
}