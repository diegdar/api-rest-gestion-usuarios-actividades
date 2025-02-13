<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActivityFormRequest;
use App\Models\Activity;
use App\Models\User;
use App\Services\ActivityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private ActivityService $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function store(ActivityFormRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->activityService->createOrUpdateActivity($validated);

        return response()->json(['message' => 'The activity has been created successfully']);
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

    public function joinActivity(User $user, Activity $activity): JsonResponse
    {
        if (!$this->activityService->joinUserToActivity($user->id, $activity)) {
            return response()->json([
                'message' => 'User is already joined to this activity',
            ], 409);
        }

        return response()->json([
            'message' => 'User joined the activity successfully',
        ]);
    }

    public function exportActivities(): JsonResponse
    {
        return response()->json($this->activityService->getAllActivities());
    }

    public function importActivities(Request $request): JsonResponse
    {
        // Validar el formato JSON
        if (!$this->isValidJson($request)) {
            return $this->invalidJsonResponse();
        }

        // Almacenar actividades y obtener errores si existen
        $errors = $this->activityService->storeActivities($request->all());

        if ($errors) {
            return $this->validationErrorResponse($errors);
        }

        return response()->json(['message' => 'Activities imported successfully'], 201);
    }

    // Método para verificar si el JSON es válido
    private function isValidJson(Request $request): bool
    {
        json_decode($request->getContent());
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function invalidJsonResponse(): JsonResponse
    {
        return response()->json([
            'message' => 'Invalid JSON format.',
            'errors' => ['format' => ['The JSON provided is malformed.']],
        ], 400);
    }

    // Respuesta para el caso de error de validación
    private function validationErrorResponse(array $errors): JsonResponse
    {
        return response()->json([
            'message' => 'Some activities failed validation',
            'errors' => $errors,
        ], 422);
    }
}
