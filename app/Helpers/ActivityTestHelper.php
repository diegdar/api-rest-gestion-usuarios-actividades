<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Models\Activity;

trait ActivityTestHelper
{
    private function CreateActivity(): Activity
    {
        return Activity::factory()->create();
    }    

    private function CreateActivityData(): array
    {
        $activityData = Activity::factory()->make()->toArray();
        $activityData['start_date'] = $activityData['start_date']->format('Y-m-d');
        return $activityData;
    }    
}
