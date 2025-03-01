<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Activity;

trait ActivityTestHelper
{
    private function CreateActivity(array $attributes = []): Activity
    {
        return Activity::factory()->create($attributes);
    }    

    private function CreateActivityData(array $attributes = []): array
    {
        $activityData = Activity::factory()->make($attributes)->toArray();
        $activityData['start_date'] = $activityData['start_date']->format('Y-m-d');
        return $activityData;
    }

}
