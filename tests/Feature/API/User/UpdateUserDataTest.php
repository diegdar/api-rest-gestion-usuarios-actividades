<?php

declare(strict_types=1);

namespace Tests\Feature\API\User;

use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
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
    public function testCanInstanciateUser(): void
    {
        $this->assertInstanceOf(User::class, new User());
    }

    public function testCanInstanciateUserController(): void
    {
        $this->assertInstanceOf(UserController::class, new UserController());
    }

    public function testCanUpdateUserSuccessfully(): void
    {
        $updatedData = [
            'name' => 'updated_name',
            'surname' => 'updated_surname',
            'age' => 35,
            'email' => 'updated@example.com',
            'password' => 'NewPassworTest$983',
        ];

        $token = $this->user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('user.update', $this->user), $updatedData);

        $response->assertStatus(200);
        $response->assertJson(['message' => 'updated successfully']);

        $this->user->refresh(); // Reload the user data
        $this->assertEquals($updatedData['name'], $this->user->name);
        $this->assertEquals($updatedData['surname'], $this->user->surname);
        $this->assertEquals($updatedData['age'], $this->user->age);
        $this->assertEquals($updatedData['email'], $this->user->email);
    }

    public function testCannotUpdateUserWhenNotAuthenticated(): void
    {
        $updatedData = [
            'name' => 'new_name',
            'surname' => 'new_surname',
            'age' => 25,
            'email' => 'new@example.com',
            'password' => 'ValidPassword123!',
        ];

        $response = $this->putJson(route('user.update', $this->user), $updatedData);

        $response->assertStatus(401);
    }

    public function testCannotUpdateNonExistentUser(): void
    {
        $updatedData = [
            'name' => 'new_name',
            'surname' => 'new_surname',
            'age' => 25,
            'email' => 'new@example.com',
            'password' => 'ValidPassword123!',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('authToken')->accessToken,
            'Accept' => 'application/json',
        ])->putJson(route('user.update', 999999), $updatedData);

        $response->assertStatus(404);
    }


    #[\PHPUnit\Framework\Attributes\DataProvider('userValidationProvider')]
    public function testCanValidateUserFields(array $invalidData, array $expectedErrors): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('authToken')->accessToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->putJson(route('user.update', $user->id), $invalidData);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors($expectedErrors);
    }

    public static function userValidationProvider(): array
    {
        return [
            'invalid name (not a string)' => [
                [
                    'name'     => 123,
                    'surname'  => 'updated_surname',
                    'email'    => 'email@example.com',
                    'age'      => 31,
                ],
                ['name'],
            ],
            'invalid surname (not a string)' => [
                [
                    'name'     => 'updated_name',
                    'surname'  => 456,
                    'email'    => 'email@example.com',
                    'age'      => 31,
                ],
                ['surname'],
            ],
            'invalid email (wrong format)' => [
                [
                    'name'     => 'updated_name',
                    'surname'  => 'updated_surname',
                    'email'    => 'not-an-email',
                    'age'      => 31,
                ],
                ['email'],
            ],
            'invalid age (not an integer)' => [
                [
                    'name'     => 'updated_name',
                    'surname'  => 'updated_surname',
                    'email'    => 'email@example.com',
                    'age'      => 'thirty',
                ],
                ['age'],
            ],
        ];
    }
}
