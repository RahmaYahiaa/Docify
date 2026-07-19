<?php

namespace App\Http\Resources\API\V1\Doctor\Profile;

use App\Http\Resources\API\V1\Doctor\Appointment\ClinicResource;
use App\Http\Resources\API\V1\Specialty\SpecializationResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $user = $this->resource;
        $doctorProfile = $user->doctorProfile; // ممكن يكون null

        return [
            'id' => $doctorProfile?->id ?? null,
         //   'profile_image' => $user->getProfilePicture(),
            'full_name' => $user->full_name,

            // التخصص
            'specialization' => $doctorProfile && $doctorProfile->relationLoaded('specialization') && $doctorProfile->specialization
                ? new SpecializationResource($doctorProfile->specialization)
                : null,

            'about' => $doctorProfile?->about ?? null,
            'experience' => $doctorProfile?->years_of_experience ?? null,

            'Contact_Information' => [
                'email' => $user->email,
                'phone' => $user->phone,
            ],
'clinic' => $doctorProfile && $doctorProfile->relationLoaded('clinic') && $doctorProfile->clinic
    ? new ClinicResource($doctorProfile->clinic)
    : null,
   'profile_picture' => $this->whenLoaded('doctorProfile', fn() => $this->doctorProfile?->getFirstMediaUrl('profile_picture') ?? '/images/default-doctor.jpg')
        ];
    }
}