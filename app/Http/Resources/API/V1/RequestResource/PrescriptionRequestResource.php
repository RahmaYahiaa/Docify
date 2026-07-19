<?php

namespace App\Http\Resources\API\V1\RequestResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
return [
            'id'             => $this->id,
            'patient_id'     => $this->patient_id,
            'request_note'   => $this->note ?? 'No description provided',
            'created_at'     => $this->created_at->format('Y-m-d H:i A'),
        ];
    }
}
