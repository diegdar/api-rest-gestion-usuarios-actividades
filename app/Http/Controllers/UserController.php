<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'userData' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'age' => $user->age,
                'email' => $user->email,
            ],
        ]);
    }
}
