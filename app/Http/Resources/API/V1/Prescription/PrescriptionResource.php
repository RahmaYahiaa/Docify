<?php

namespace App\Http\Resources\API\V1\Prescription;

use App\Http\Resources\API\V1\Doctor\Appointment\DoctorListResource;
use App\Http\Resources\API\V1\Doctor\Profile\DoctorProfileResource;
use App\Http\Resources\API\V1\Doctor\Profile\Medication\MedicationResource;
use App\Http\Resources\API\V1\Patient\PatientResource;
use App\Http\Resources\API\V1\Prescription\PrescriptionItem\PrescriptionItemResource;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Prescription $prescription */
        $prescription = $this->resource;

        return [
            'id' => $prescription->id,
            'doctor' => $this->whenLoaded('doctor', fn() => [
                'id' => $this->doctor->id,
                'name' => $this->doctor->full_name,
            'specialty' => [
    'name' => $this->doctor->doctorProfile?->specialization?->name  ,

],
            ]),
            'patient' => $this->whenLoaded('patient', fn() => [
                'id' => $this->patient->id,
                'name' => $this->patient->full_name,
            ]),

            'notes' => $this->whenHas('notes', fn() => $prescription->notes),
               'diagnosis' => $this->whenHas('notes', fn() => $prescription->diagnosis),
            'meds_count' => $this->whenCounted('items'),
            'medication' => MedicationResource::make($this->whenLoaded('medication')),
            'created_at' => $this->whenHas('created_at', fn() => $prescription->created_at->format('Y-m-d')),
            'items' => PrescriptionItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
