<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Activity;
use App\Http\Requests\ActivityFormRequest;
use Illuminate\Validation\ValidationException;

class ActivityService
{
    public function createOrUpdateActivity(array $data): void
    {
        Activity::updateOrCreate(
            ['name' => $data['name']],
            [
                'description' => $data['description'],
                'max_capacity' => $data['max_capacity'],
                'start_date' => $data['start_date'],
            ]
        );
    }

    public function getActivityById(int $id): Activity
    {
        return Activity::findOrFail($id);
    }

    public function joinUserToActivity(int $userId, Activity $activity): bool
    {
        if ($activity->users()->where('user_id', $userId)->exists()) {
            return false;
        }

        $activity->users()->attach($userId);
        return true;
    }

    public function getAllActivities()
    {
        return Activity::all();
    }

    public function storeActivities(array $activities): array
    {
        $errors = [];

        foreach ($activities as $index => $activity) {
            $request = new ActivityFormRequest();
            $request->merge($activity); // Pasar datos al FormRequest

            try {
                // Obtener los datos validados
                $validated = $request->validate($request->rules());
                $this->createOrUpdateActivity($validated);
            } catch (ValidationException $e) {
                $errors[$activity['name']] = $e->errors();
            }
        }

        return $errors;
    }


}
