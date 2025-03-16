<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class LoginController extends Controller
{
    use HasRoles;

    /**
     * Login user.
     * @unauthenticated
     */
    public function login(LoginFormRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError('User not found.', ['error' => 'User not found']);
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->sendError('Incorrect password.', ['error' => 'Incorrect password']);
        }

        $success['id'] = $user->id;
        $success['name'] = $user->name;
        $success['surname'] = $user->surname;
        $success['role'] = $user->getRoleNames();
        $success['token'] = $user->createToken('MyApp')->accessToken;

        return $this->sendResponse($success, 'User login successfully');
    }

}
