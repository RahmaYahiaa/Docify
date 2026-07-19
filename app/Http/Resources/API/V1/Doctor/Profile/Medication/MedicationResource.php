<?php

namespace App\Http\Resources\API\V1\Doctor\Profile\Medication;

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'dosage' => $this->dosage,
            // 'frequency' => $this->frequency,
            // 'duration' => $this->duration,
            // 'source' => $this->prescription_id ? 'prescription' : 'patient',
        ];
    }
}
