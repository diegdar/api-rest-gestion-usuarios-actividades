<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Http\Controllers\RegisterController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCanInstanciateUser(): void
    {
        $this->assertInstanceOf(User::class, new User());
    }

    public function testCanInstanciateRegisterController(): void
    {
        $this->assertInstanceOf(RegisterController::class, new RegisterController());
    }

    public function testCanRegisterAnUser(): void
    {
        $userData = [
            'name' => 'test_name',
            'surname' => 'test_surname',
            'age' => 30,
            'email' => 'test@example.com',
            'password' => 'PassworTest$983',
        ];

        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(200);

        $response->assertJson([
            'success' => true,
            'message' => 'registered successfully',
            'data' => [
                'name' => $userData['name'],
                'surname' => $userData['surname'],
                'token' => $response->json('data.token'),
            ],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('invalidUserDataProvider')]
    public function testCanShow_422AndErrorMessageWithInvalidData(array $userData, string $expectedErrorMessage): void
    {
        $response = $this->postJson(route('user.register'), $userData);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'errors' => [
                $expectedErrorMessage
            ],
        ]);
    }

    public static function invalidUserDataProvider(): array
    {
        return [
            'missing name' => [
                [
                    'surname' => 'test_surname',
                    'age' => 30,
                    'email' => 'test@example.com',
                    'password' => 'PassworTest$983',
                ],
                'name',
            ],
            'missing surname' => [
                [
                    'name' => 'test_name',
                    'age' => 30,
                    'email' => 'test@example.com',
                    'password' => 'PassworTest$983',
                ],
                'surname',
            ],
            'invalid age' => [
                [
                    'name' => 'test_name',
                    'surname' => 'test_surname',
                    'age' => 'not-a-number',
                    'email' => 'test@example.com',
                    'password' => 'PassworTest$983',
                ],
                'age',
            ],
            'missing email' => [
                [
                    'name' => 'test_name',
                    'surname' => 'test_surname',
                    'age' => 30,
                    'password' => 'PassworTest$983',
                ],
                'email',
            ],
            'invalid email' => [
                [
                    'name' => 'test_name',
                    'surname' => 'test_surname',
                    'age' => 30,
                    'email' => 'not-an-email',
                    'password' => 'PassworTest$983',
                ],
                'email',
            ],
            'short password' => [
                [
                    'name' => 'test_name',
                    'surname' => 'test_surname',
                    'age' => 30,
                    'email' => 'test@example.com',
                    'password' => 'short',
                ],
                'password',
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('missingFieldsProvider')]
    public function testCanRequireFieldsWhenTheyAreMissing(array $invalidData, array $expectedErrors): void
    {
        $response = $this->postJson(route('user.register'), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function missingFieldsProvider(): array
    {
        return [
            'missing name' => [
                [
                    'name'     => '',
                    'surname'  => 'test_name',
                    'age'      => 30,
                    'email'    => 'test@example.com',
                    'password' => 'PasswordTest%123',
                ],
                ['name'],
            ],
            'missing surname' => [
                [
                    'name'     => 'test_name',
                    'surname'  => '',
                    'age'      => 30,
                    'email'    => 'test@example.com',
                    'password' => 'PasswordTest%123',
                ],
                ['surname'],
            ],
            'missing age' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_name',
                    'age'      => '',
                    'email'    => 'test@example.com',
                    'password' => 'PasswordTest%123',
                ],
                ['age'],
            ],
            'missing email' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_name',
                    'age'      => 30,
                    'email'    => '',
                    'password' => 'PasswordTest%123',
                ],
                ['email'],
            ],
            'missing password' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_name',
                    'age'      => 30,
                    'email'    => 'test@example.com',
                    'password' => '',
                ],
                ['password'],
            ],
            'missing all' => [
                [
                    'name'     => '',
                    'surname'  => '',
                    'age'      => '',
                    'email'    => '',
                    'password' => '',
                ],
                ['name', 'surname', 'age', 'email', 'password'],
            ],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('userValidationProvider')]
    public function testCanValidateUserFields(array $invalidData, array $expectedErrors): void
    {
        $response = $this->postJson(route('user.register'), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function userValidationProvider(): array
    {

        return [
            'invalid name (not a string)' => [
                [
                    'name'     => 123,
                    'surname'  => 'test_name',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                    'age'      => 30,
                ],
                ['name'],
            ],
            'invalid surname (not a string)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 456,
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                    'age'      => 30,
                ],
                ['surname'],
            ],
            'invalid email (wrong format)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'not-an-email',
                    'password' => 'PassworTest$983',
                    'age'      => 30,
                ],
                ['email'],
            ],
            'invalid password (no special character)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest983',
                    'age'      => 30,
                ],
                ['password'],
            ],
            'invalid password (too short)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'P@ss1',
                    'age'      => 30,
                ],
                ['password'],
            ],
            'invalid password (too long)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => str_repeat('a', 21),
                    'age'      => 30,
                ],
                ['password'],
            ],
            'invalid password (only letters)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'Password',
                    'age'      => 30,
                ],
                ['password'],
            ],
            'invalid age (too young)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                    'age'      => 9,
                ],
                ['age'],
            ],
            'invalid age (too old)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                    'age'      => 91,
                ],
                ['age'],
            ],
            'invalid age (not an integer)' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                    'age'      => 'thirty',
                ],
                ['age'],
            ],
            'missing age' => [
                [
                    'name'     => 'test_name',
                    'surname'  => 'test_surname',
                    'email'    => 'email@example.com',
                    'password' => 'PassworTest$983',
                ],
                ['age'],
            ],
        ];
    }
}
