<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->full_name,
            'profile_picture' => $this->doctorProfile?->getFirstMediaUrl('profile_picture') ?? '/images/default-doctor.jpg',
            'specialty' => [
                'name' => $this->doctorProfile?->specialization?->name,
                'icon_url' => $this->doctorProfile?->specialization?->getFirstMediaUrl('specialty_icon')
            ],
            'rating' => [
                'reviews_count' => $this->doctorProfile?->reviews_count ?? 0,
                'average_rating' => $this->doctorProfile?->average_rating ?? 0,
            ],
            'experience_years' => $this->doctorProfile?->years_of_experience,
            'consultation_options' => [
                [
                    'type' => 'video',
                    'fee' => $this->doctorProfile?->video_fee,
                    'available' => $this->doctorProfile?->video_fee !== null
                ],
                [
                    'type' => 'in_person',
                    'fee' => $this->doctorProfile?->in_person_fee,
                    'available' => $this->doctorProfile?->in_person_fee !== null
                ],
            ],
            // About Tab
            'about' => $this->doctorProfile?->about,
            'languages' => $this->doctorProfile?->languages ?? [],
            // Locations Tab
            'locations' => $this->when(
                $this->doctorProfile?->clinic,
                fn() => new ClinicResource($this->doctorProfile->clinic)
            ),
        ];
    }
}
