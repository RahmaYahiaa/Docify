<?php

namespace App\Http\Requests\API\V1\Patient\Measurements;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VitalsSummaryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
     return [
            'id' => $this->id,
            'type_name' => $this->type->name,
            'value' => $this->value,
            'unit' => $this->type->unit,
          //  'status' => $this->status,
            'last_update' => Carbon::parse($this->measured_at)->diffForHumans(),

        ];
    }
}
