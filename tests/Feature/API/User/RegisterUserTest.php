<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
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
        $userData = User::factory()->raw();
        // $userData = User::factory()->make()->toArray();
        $userData['password'] = 'Password&1234';

        return $userData;
    }

    public function requestRegisterUser(array $data): TestResponse
    {
        return $this->postJson(route('user.store'), $data);        
    }

    public function testCanRegisterAnUser(): void
    {
        $response = $this->requestRegisterUser($this->getUserData());

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'registered successfully',
        ]);
    }

    #[DataProvider('invalidUserDataProvider')]
    public function testCannotImportActivitiesWithInvalidData(array $invalidData, array $field): void
    {
        $data = $this->getUserData();
        $data = array_merge($data, $invalidData);
        $response = $this->requestRegisterUser($data);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($field);
    }

    public static function invalidUserDataProvider(): array
    {
        return [
        // name
            'missing name' => [['name' => null], ['name']],
            'invalid name' => [['name' => 123], ['name']],
        // surname
            'missing surname' => [['surname' => null], ['surname']],
            'invalid surname' => [['surname' => 456], ['surname']],
        // age
            'missing age' => [['age' => null], ['age']],
            'invalid age (not a number)' => [['age' => 'thirty'], ['age']],
            'invalid age (too young)' => [['age' => 9], ['age']],
            'invalid age (too old)' => [['age' => 91], ['age']],
        // email
            'missing email' => [['email' => null], ['email']],
            'invalid email (wrong format)' => [['email' => 'not-an-email'], ['email']],
        // password
            'missing password' => [['password' => null], ['password']],
            'invalid password (no special character)' => [['password' => 'PassworTest983'], ['password']],
            'invalid password (too short)' => [['password' => 'P@ss1'], ['password']],
            'invalid password (too long)' => [['password' => str_repeat('a', 21)], ['password']],
            'invalid password (only letters)' => [['password' => 'Password'], ['password']],
            'invalid password (no uppercase)' => [['password' => 'password$123'], ['password']],
            'invalid password (no number)' => [['password' => 'Password!'], ['password']],
            
        // all
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
}
