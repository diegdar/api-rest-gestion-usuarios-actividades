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
}
