<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class UserMeasurement extends Model
{
    protected $fillable = [
        'user_id',
        'measurement_type_id',
        'value',
        'value_2',
        'status',
        'note',
        'measured_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function type()
    {
        return $this->belongsTo(MeasurementType::class, 'measurement_type_id');
    }
    protected $casts = [
    'measured_at' => 'datetime',
];
}
