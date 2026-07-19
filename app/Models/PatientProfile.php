<?php

namespace App\Models;

use App\Models\Prescription;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class PatientProfile extends Model  implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'date_of_birth',
        'gender',
        'address',
        'emergency_contact',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'patient_profile_id');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function medicalData()
    {
        return $this->hasOne(MedicalData::class);
    }

    public function chronicConditions()
    {
        return $this->belongsToMany(ChronicCondition::class, 'patient_chronic_conditions');
    }

    public function allergies()
    {
        return $this->belongsToMany(Allergy::class, 'patient_allergies');
    }

     protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => Carbon::parse($this->date_of_birth)->age,
        );
    }
       public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->useDisk('public')
            ->useFallbackUrl('/images/default-patient.jpg')
            ->useFallbackPath(public_path('/images/default-patient.jpg'));
    }

    public function getRouteKeyName()
{
    return 'user_id';
}
}
