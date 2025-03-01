<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityFormRequest;
use App\Http\Requests\UpdateActivityFormRequest;
use App\Models\Activity;
use App\Models\User;
use App\Services\ActivityService;
use App\Services\JoinUserActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private ActivityService $activityService;

    public function __construct()
    {
        $this->activityService = new activityService();
    }

    public function store(StoreActivityFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->activityService->create($validated);

        return response()->json(['message' => 'La actividad se ha creado correctamente']);
    }

    public function update(UpdateActivityFormRequest $request, Activity $activity): JsonResponse
    {
        $validated = $request->validated();
        $this->activityService->update($validated, $activity);

        return response()->json(['message' => 'La actividad se ha actualizado correctamente']);
    }

    public function destroy(Activity $activity): JsonResponse
    {
        $this->activityService->destroy($activity);

        return response()->json(['message' => 'La actividad se ha eliminado correctamente']);
    }

    public function show($id): JsonResponse
    {
        $activity = $this->activityService->getActivityById((int) $id);

        return response()->json([
            'activityData' => [
                'id' => $activity->id,
                'name' => $activity->name,
                'description' => $activity->description,
                'max_capacity' => $activity->max_capacity,
                'start_date' => $activity->start_date,
            ],
        ]);
    }

    public function joinActivity(
        JoinUserActivityService $joinUserActivity, User $user, Activity $activity
    ): JsonResponse
    {
        return $joinUserActivity($user, $activity);
    }    

    public function exportActivities(): JsonResponse
    {
        return response()->json($this->activityService->getAllActivities());
    }

    public function importActivities(Request $request): JsonResponse
    {
        // Validate JSON format
        if (!$this->isValidJson($request)) {
            return $this->invalidJsonResponse();
        }

        // Store activities and get errors if any
        $errors = $this->activityService->storeActivities($request->all());

        if ($errors) {
            return $this->validationErrorResponse($errors);
        }

        return response()->json(['message' => 'Actividades importadas correctamente'], 201);
    }

    // Method to check if JSON is valid
    private function isValidJson(Request $request): bool
    {
        json_decode($request->getContent());
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function invalidJsonResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Formato JSON inválido.',
            'errors' => ['format' => ['El JSON proporcionado está mal formado.']],
        ], 400);
    }

    // Response for validation error case
    private function validationErrorResponse(array $errors): JsonResponse
    {
        return response()->json([
            'message' => 'Algunas actividades fallaron la validación',
            'errors' => $errors,
        ], 422);
    }
}