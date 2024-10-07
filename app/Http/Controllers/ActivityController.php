<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActivityFormRequest;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ActivityController extends Controller
{
    public function store(ActivityFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Activity::create($validated);

        return response()->json(['message' => 'The activity has been created successfully']);
    }
}
