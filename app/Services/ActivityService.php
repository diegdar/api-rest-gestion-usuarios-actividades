<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Activity;
use App\Http\Requests\StoreActivityFormRequest;
use App\Http\Resources\ActivityResource;
use Illuminate\Validation\ValidationException;

class ActivityService
{
    public function create(array $data): void
    {
        Activity::create($data);
    }

    public function update(array $data, Activity $activity): void
    {
        $activity->update($data);
    }

    public function destroy(Activity $activity): void
    {
        $activity->delete();
    }

    public function getActivityById(int $id): Activity
    {
        return Activity::findOrFail($id);
    }

    public function getAllActivities()
    {
        return ActivityResource::collection(Activity::query()->OrderBy('start_date', 'desc'));
    }

    public function storeActivities(array $activities): array
    {
        $errors = [];

        foreach ($activities as $index => $activity) {
            $request = new StoreActivityFormRequest();
            $request->merge($activity); // Pasar datos al FormRequest

            try {
                // Obtener los datos validados
                $validated = $request->validate($request->rules());
                $this->create($validated);
            } catch (ValidationException $e) {
                $errors[$activity['name']] = $e->errors();
            }
        }

        return $errors;
    }

}
