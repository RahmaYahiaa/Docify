<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'basic info' => new DoctorBasicResource($this),
            'email' => $this->email,
            'address' => $this->address,
            'status'    => $this->status,
            'about'  => $this->doctorProfile?->about,
            'professional_details' => [
                'experience_years' => $this->doctorProfile?->years_of_experience,
            ],
            'uploaded_certificate' => [
                'url' => $this->getFirstMediaUrl(User::MEDICAL_CERTIFICATE),
                'name' => $this->getFirstMedia(User::MEDICAL_CERTIFICATE)?->name,
            ],
            'submission_info' => [
                'submitted_at' => $this->created_at?->format('F d, Y'),
            ],
            'verification_checklist' => $this->when(
                $this->hasRole('doctor'),
                [
                    'medical_certificate_uploaded' => $this->hasMedia('medical_certificate'),

                    'doctor_profile_information_completed' =>
                    !empty($this->doctorProfile?->video_fee)
                        && !empty($this->doctorProfile?->in_person_fee)
                        && !empty($this->doctorProfile?->specialty_id)
                        && !empty($this->doctorProfile?->clinic_id),

                    'phone_number_provided' => !empty($this->phone),

                    'email_address_verified' => !is_null($this->email_verified_at),
                ]
            ),
            'rejection' => $this->when(
                $this->resource->hasRole('doctor'),
                [
                    'rejection_reason' => $this->rejection_reason,
                    'rejected_at' => optional($this->doctorProfile?->rejected_at)
                        ?->format('M d, Y \a\t h:i A'),
                ]
            ),
        ];
    }
}
