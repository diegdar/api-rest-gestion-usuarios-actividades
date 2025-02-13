<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActivityFormRequest;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivityController extends Controller
{
    public function store(ActivityFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Activity::updateOrCreate(
            // field to check if the record exists
            ['name' => $validated['name']],
            // fields to update or create if the record does not exist
            [
                'description' => $validated['description'],
                'max_capacity' => $validated['max_capacity'],
                'start_date' => $validated['start_date'],
            ]
        );

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

    public function joinActivity(User $user, Activity $activity)
    {
        if ($activity->users()->where('user_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'User is already joined to this activity',
            ], 409);
        }

        $activity->users()->attach($user->id);

        return response()->json([
            'message' => 'User joined the activity successfully',
        ]);
    }

    public function exportActivities()
    {
        $activities = Activity::all();

        return response()->json($activities);
    }

    public function importActivities(Request $request): JsonResponse
    {
        $request->validate([
            '*.name' => 'required|string',
            '*.description' => 'nullable|string',
            '*.max_capacity' => 'required|integer|min:1',
            '*.start_date' => 'required|date_format:Y-m-d',
        ]);

        $this->storeActivities($request->all());

        return response()->json(['message' => 'Activities imported successfully'], 201);
    }

    private function storeActivities(array $activities)
    {
        foreach ($activities as $activity) {
            Activity::updateOrCreate([
                'name' => $activity['name'],
            ], [
                'description' => $activity['description'],
                'max_capacity' => $activity['max_capacity'],
                'start_date' => $activity['start_date'],
            ]);
        }
    }
}
