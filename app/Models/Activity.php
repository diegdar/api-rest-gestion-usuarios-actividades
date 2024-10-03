<?php

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

}
