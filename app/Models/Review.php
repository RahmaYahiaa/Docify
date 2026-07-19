<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'doctor_profile_id',
        'appointment_id',
        'user_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
