<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function getUserData(): array
    {
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'Password&1234';

        return $userData;
    }

    public function testCanRegisterAnUser(): void
    {
        $response = $this->postJson(route('user.create'), $this->getUserData());

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'registered successfully',
        ]);
    }

    #[DataProvider('invalidUserDataProvider')]
    public function testCannotImportActivitiesWithInvalidData(array $invalidData): void
    {
        $data = $this->getUserData();
        $data = array_merge($data, $invalidData);
        $response = $this->postJson(route('user.create'), $data);

        $response->assertStatus(422);
    }

    public static function invalidUserDataProvider(): array
    {
        return [
            'missing name' => [['name' => null], ['name']],
            'invalid name' => [['name' => 123], ['name']],
            'missing surname' => [['surname' => null], ['surname']],
            'invalid surname' => [['surname' => 456], ['surname']],
            'missing age' => [['age' => null], ['age']],
            'invalid age (not a number)' => [['age' => 'thirty'], ['age']],
            'invalid age (too young)' => [['age' => 9], ['age']],
            'invalid age (too old)' => [['age' => 91], ['age']],
            'missing email' => [['email' => null], ['email']],
            'invalid email (wrong format)' => [['email' => 'not-an-email'], ['email']],
            'missing password' => [['password' => null], ['password']],
            'invalid password (no special character)' => [['password' => 'PassworTest983'], ['password']],
            'invalid password (too short)' => [['password' => 'P@ss1'], ['password']],
            'invalid password (too long)' => [['password' => str_repeat('a', 21)], ['password']],
            'invalid password (only letters)' => [['password' => 'Password'], ['password']],
        ];
    }

    #[DataProvider('missingFieldsProvider')]
    public function testCanRequireFieldsWhenTheyAreMissing(array $invalidData, array $expectedErrors): void
    {
        $response = $this->postJson(route('user.create'), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function missingFieldsProvider(): array
    {
        return [
            'missing name' => [['name' => ''], ['name']],
            'missing surname' => [['surname' => ''], ['surname']],
            'missing age' => [['age' => ''], ['age']],
            'missing email' => [['email' => ''], ['email']],
            'missing password' => [['password' => ''], ['password']],
            'missing all' => [
                [
                    'name' => '',
                    'surname' => '',
                    'age' => '',
                    'email' => '',
                    'password' => '',
                ],
                ['name', 'surname', 'age', 'email', 'password'],
            ],
        ];
    }

    #[DataProvider('userValidationProvider')]
    public function testCanValidateUserFields(array $invalidData, array $expectedErrors): void
    {
        $userData = $this->getUserData();
        $data = array_merge($userData, $invalidData);

        $response = $this->postJson(route('user.create'), $data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function userValidationProvider(): array
    {
        return [
            'invalid name (not a string)' => [['name' => 123], ['name']],
            'invalid surname (not a string)' => [['surname' => 456], ['surname']],
            'invalid email (wrong format)' => [['email' => 'not-an-email'], ['email']],
            'invalid password (no special character)' => [['password' => 'PassworTest983'], ['password']],
            'invalid password (too short)' => [['password' => 'P@ss1'], ['password']],
            'invalid password (too long)' => [['password' => str_repeat('a', 21)], ['password']],
            'invalid password (only letters)' => [['password' => 'Password'], ['password']],
            'invalid age (not an integer)' => [['age' => 'thirty'], ['age']],
        ];
    }
}
