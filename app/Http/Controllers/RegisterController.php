<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function store(RegisterFormRequest $request): JsonResponse
    {
        $input = $request->validated();

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input)->assignRole('User');
        $sucess['name'] = $user->name;
        $sucess['surname'] = $user->surname;
        $sucess['token'] = $user->createToken('MyApp')->accessToken;

        return response()->json([
            'success' => true,
            'message' => 'registered successfully',
        ], 200);
    }

}
