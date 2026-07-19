<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorListResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'profile_picture' => $this->doctorProfile?->getFirstMediaUrl('profile_picture') ?? '/images/default-doctor.jpg',
            'specialty' => [
                'name' => $this->doctorProfile?->specialization?->name,
            ],
            'experience_years' => $this->doctorProfile?->years_of_experience,
            'min_fee' => $this->doctorProfile?->min_fee,
            'rating' => $this->avg_rating ? round((float) $this->avg_rating, 1) : 0,
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'clinic' => $this->doctorProfile?->clinic ? [
                'name' => $this->doctorProfile->clinic->name,
            ] : null,
            'consultation_types' => [
                'video' => $this->doctorProfile?->video_fee !== null,
                'in_person'  => $this->doctorProfile?->in_person_fee !== null,
            ],
        ];
    }
}