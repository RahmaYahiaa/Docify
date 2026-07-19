<?php

namespace App\Http\Resources\API\V1\Patient\Profile;

use App\Http\Resources\API\V1\Patient\Reports\LabReportResource;
use App\Http\Resources\API\V1\Prescription\PrescriptionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientMedicalHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->resource['user'];

        return [
            'profile' => [
                'full_name'       => $user->full_name,
                'gender'          => $user->patientProfile?->gender,
                'age'             => $user->patientProfile?->age,
                'profile_picture' => $user->patientProfile?->getFirstMediaUrl('profile_picture'),
            ],

            'medical_data' => new MedicalDataResource($user),

            'medications' => collect($this->resource['medications'])->map(fn($med) => [
                'id'         => $med->id,
                'name'       => $med->medication?->name,
                'dosage'     => $med->dosage,
                'created_at' => $med->created_at?->toDateTimeString(),
            ])->values(),

            'prescriptions' => PrescriptionResource::collection($this->resource['prescriptions']),

            'reports' => LabReportResource::collection($this->resource['reports']),
        ];
    }
}
