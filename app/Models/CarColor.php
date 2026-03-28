<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarColor extends Model
{
    protected $fillable = [
        'arabam_id',
        'name',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
