<?php

namespace App\Services;

use App\Models\User;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;

class JoinUserActivityService
{
    public function __invoke(User $user, Activity $activity): JsonResponse
    {
        if ($this->isUserAlreadyJoinedToActivity($user->id, $activity)) {
            return response()->json([
                'message' => "El usuario {$user->name} {$user->surname} ya estaba unido a la actividad {$activity->name} anteriormente",
            ], 409);
        }

        if ($this->isActivityFull($activity)) {
            return response()->json([
                'message' => "La actividad {$activity->name} ya está completa",
            ], 409);
        }

        $activity->users()->attach($user->id);

        return response()->json([
            'message' => "El usuario {$user->name} {$user->surname} se ha unido a la actividad {$activity->name} correctamente!",
        ], 200);
    }

    private function isUserAlreadyJoinedToActivity(int $userId, Activity $activity): bool
    {
        return $activity->users()->where('user_id', $userId)->exists();
    }

    private function isActivityFull(Activity $activity): bool
    {
        return $activity->users()->count() >= $activity->max_capacity;
    }
}