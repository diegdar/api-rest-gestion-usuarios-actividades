<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Helpers\UserTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginUserTest extends TestCase
{
    use DatabaseTransactions, UserTestHelper;

    protected function setUp(): void
    {
        parent::setUp();
    }

    private function requestLoginUser(string $email = 'test@example.com', string $password = 'Password&123'): TestResponse
    {
        return $this->postJson(route('user.login'), [
            'email' => $email,
            'password' => $password,
        ]);
    }

    public function testCanLoginSuccessfully(): void
    {
        $this->createUser();

        $response = $this->requestLoginUser();

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'token',
            ]
        ]);
        $response->assertJsonPath('data.id', User::where('email', 'test@example.com')->first()->id);
    }

    public function testCanShow_401WhithIncorrectPassword()
    {
        $this->createUser();

        $response = $this->requestLoginUser(password: 'wrong%Password432');

        $response->assertStatus(401);
    }

    public function testCanShow_401WhithIncorrectEmail(): void
    {
        $this->createUser();

        $response = $this->requestLoginUser(email: 'wrongEmail@example.com', password: 'Password&123');

        $response->assertStatus(401);
    }

    #[DataProvider('loginValidationProvider')]
    public function testCanValidateUserFields(array $invalidData, array $expectedErrors): void
    {
        $this->createUser();

        $response = $this->requestLoginUser(password: $invalidData['password'] ?? 'Password&123', email: $invalidData['email'] ?? 'test@example.com');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function loginValidationProvider(): array
    {
        return [
            'invalid email (it is required)' => [['email' => ''], ['email']],
            'invalid email (not a valid email format)' => [['email' => 'invalid-email'], ['email']],
            'invalid password (it is required)' => [['password' => ''], ['password']],
            'missing email and password' => [['email' => '', 'password' => ''], ['email', 'password']],
            'valid email, invalid password (no uppercase)' => [['email' => 'test@example.com', 'password' => 'password123!'], ['password']],
            'valid email, invalid password (no number)' => [['email' => 'test@example.com', 'password' => 'Password!'], ['password']],
            'valid email, invalid password (no special char)' => [['email' => 'test@example.com', 'password' => 'Password123'], ['password']],
            'invalid password (too long)' => [['password' => str_repeat('a', 21)], ['password']],
            'valid email, invalid password (too short)' => [['email' => 'test@example.com', 'password' => 'Pass1!'], ['password']],
            'invalid email, valid password' => [['email' => 'invalid-email', 'password' => 'Password&123'], ['email']],
        ];
    }
}