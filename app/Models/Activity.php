<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    /** @use HasFactory<\Database\Factories\ActivityFactory> */
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'max_capacity',
        'start_date',
    ];
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function toArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'max_capacity' => $this->max_capacity,
            'start_date' => $this->start_date
        ];
    }    

}
