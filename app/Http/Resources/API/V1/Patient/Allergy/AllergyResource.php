<?php

namespace App\Http\Resources\API\V1\Patient\Allergy;

use App\Models\Allergy;  
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllergyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Allergy $allergy */
        $allergy = $this->resource;

        return [
            'id' => $this->id,
            'name' => $this->whenHas('name', fn () => $allergy->name),
        ];
    }
}