<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'is_enabled',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];
}
