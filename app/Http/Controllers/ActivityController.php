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

    private function handleFileUpload(Request $request): array
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $json = file_get_contents($request->file('file')->getRealPath());

        return json_decode($json, true);
    }

    private function storeActivities(array $activities): void
    {
        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }

    public function importActivities(Request $request)
    {
        $activities = $this->handleFileUpload($request);

        $this->storeActivities($activities);

        return response()->json(['message' => 'Actividades importadas con éxito']);
    }

}
