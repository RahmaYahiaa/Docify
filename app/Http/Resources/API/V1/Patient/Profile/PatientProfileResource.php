<?php

namespace App\Http\Resources\API\V1\Patient\Profile;

use Illuminate\Http\Request;
use App\Enums\Role\UserRoleEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'role'       => UserRoleEnum::PATIENT->value,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'emergency_contact' =>$this->patientProfile?->emergency_contact,
            'date_of_birth' => $this->patientProfile?->date_of_birth,
            'address'       => $this->patientProfile?->address,
            'gender'       => $this->patientProfile?->gender,
            'profile_picture' => $this->patientProfile?->getFirstMediaUrl('profile_picture'),
        ];
    }
}
