<?php

namespace App\Models;

use App\Enums\Specialty\SpecialtyStatusEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Specialization extends Model implements HasMedia
{
    use InteractsWithMedia, HasTranslations;

    protected $fillable = ['name', 'description', 'status'];

    public $translatable = ['name'];
    protected $casts = [
        'status' => SpecialtyStatusEnum::class,

    ];
    public function scopeActive($query)
    {
        return $query->where('status', SpecialtyStatusEnum::ACTIVE->value);
    }

    public function doctors()
    {
        return $this->hasMany(User::class);
    }

    public function doctorProfiles()
    {
        return $this->hasMany(DoctorProfile::class, 'specialization_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('specialty_icon')
            ->singleFile()
            ->useDisk('public')
            ->useFallbackUrl('/images/default-specialization.png');
    }


}
