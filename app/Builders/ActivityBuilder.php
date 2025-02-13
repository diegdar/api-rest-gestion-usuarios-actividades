<?php

declare(strict_types=1);

namespace App\Builders;

use App\Models\Activity;
use DateTime;

class ActivityBuilder
{
    private string $name;
    private string $description;
    private int $maxCapacity;
    private string $startDate;

    public function __construct()
    {
        $this->name = "Activity name";
        $this->description = "Activity description";
        $this->maxCapacity = 10;
        $this->startDate = "2022-01-01";
    }

    public function withName(string $name): ActivityBuilder
    {
        $this->name = $name;
        return $this;
    }

    public function withDescription(string $description): ActivityBuilder
    {
        $this->description = $description;
        return $this;
    }

    public function withMaxCapacity(int $maxCapacity): ActivityBuilder
    {
        $this->maxCapacity = $maxCapacity;
        return $this;
    }

    public function withStartDate(string $startDate): ActivityBuilder
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function build(): Activity
    {
        return Activity::factory()->create([
            'name' => $this->name,
            'description' => $this->description,
            'max_capacity' => $this->maxCapacity,
            'start_date' => $this->startDate,
        ]);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'max_capacity' => $this->maxCapacity,
            'start_date' => $this->startDate,
        ];
    }

    

}
