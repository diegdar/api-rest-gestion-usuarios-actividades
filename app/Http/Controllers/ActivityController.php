<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActivityFormRequest;
use App\Models\Activity;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivityController extends Controller
{
    public function store(ActivityFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Activity::create($validated);

        return response()->json(['message' => 'The activity has been created successfully']);
    }

    public function show($id)
    {
        try {
            $activity = Activity::findOrFail($id);
            return response()->json([
            'activityData' => [
                'id' => $activity->id,
                'name' => $activity->name,
                'description' => $activity->description,
                'max_capacity' => $activity->max_capacity,
                'start_date' => $activity->start_date,
            ],
            ]);
        } catch (ModelNotFoundException $e) {
            throw new NotFoundHttpException('Activity not found');
        }
    }

}
