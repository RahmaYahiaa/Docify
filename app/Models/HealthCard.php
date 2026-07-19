<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;


class HealthCard extends Model
{
    protected $fillable = ['user_id', 'uuid',];

    protected static function booted()
    {
        static::creating(function ($card) {
            if (!$card->uuid) {
                $card->uuid = Str::uuid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return config('app.healthcard_url') . '/medical-history/' . $this->uuid;
    }
}
