<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterFormRequest $request): JsonResponse
    {
        $input = $request->validated();

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $sucess['name'] = $user->name;
        $sucess['surname'] = $user->surname;
        $sucess['token'] = $user->createToken('MyApp')->accessToken;

        $user->delete();

        $response = $this->sendResponse($sucess, 'registered successfully');

        return $response;
    }

}
