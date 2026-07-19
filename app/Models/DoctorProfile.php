<?php

namespace App\Models;

use App\Enums\Doctor\DoctorStatusEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DoctorProfile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'user_id',
        'about',
        'years_of_experience',
        'video_fee',
        'in_person_fee',
        'languages',
        'specialty_id',
        'clinic_id',
        'stripe_account_id',
        // 'specialization_id',
        'status',
        'rejected_at'
    ];

    protected $casts = [
        'languages' => 'array',
        'video_fee' => 'decimal:2',
        'in_person_fee' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'rejected_at' => 'datetime',
        'status' => DoctorStatusEnum::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function getMinFeeAttribute()
    {
        $fees = [$this->video_fee, $this->in_person_fee];
        $validFees = array_filter($fees, fn($fee) => $fee !== null);

        return ! empty($validFees) ? min($validFees) : null;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('profile_picture')
            ->singleFile()
            ->useDisk('public')
            ->useFallbackUrl('/images/default-doctor.jpg')
            ->useFallbackPath(public_path('/images/default-doctor.jpg'));
    }

    public function scopeWithReviewsStats($query)
    {
        return $query
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');
    }

    protected $appends = ['average_rating'];

    public function getAverageRatingAttribute()
    {
        return $this->reviews_avg_rating
            ? round($this->reviews_avg_rating, 1)
            : 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
