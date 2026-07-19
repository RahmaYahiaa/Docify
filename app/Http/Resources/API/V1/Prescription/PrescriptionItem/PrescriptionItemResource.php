<?php

namespace App\Http\Resources\API\V1\Prescription\PrescriptionItem;

use App\Http\Resources\API\V1\Doctor\Profile\Medication\MedicationResource;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var PrescriptionItem $item */
        $item = $this->resource;

        return [
            'id' => $item->id,
            'medication' => new MedicationResource($this->whenLoaded('medication')),
            'dosage' => $this->whenHas('dosage', fn() => $item->dosage),
            'frequency' => $this->whenHas('frequency', fn() => $item->frequency),
            'duration' => $this->whenHas('duration', fn() => $item->duration),
            'instruction' => $this->whenHas('instruction', fn() => $item->instruction),
            'start_time' => $item->start_time,
            'created_at' => $item->created_at?->diffForHumans(),
        ];
    }
}
