<?php

namespace App\Http\Resources\API\V1\Specialty;

use App\Http\Resources\BasePaginationResource;
use App\Models\Specialization;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationResource extends JsonResource
{     use HasTranslatableFields;
    public function toArray(Request $request): array
    {
      /** @var Specialization $specialization */
$specialization = $this->resource;
        return [
            'id' => $this->whenHas('id', fn() => $specialization->id),
            //fn () => $this->getTranslatableField($country, 'name')
       'name' => $this->getTranslatableField($specialization, 'name'),
            'icon_url' => $this->when(
                $this->relationLoaded('media'),
                fn() => $this->getFirstMediaUrl('specialty_icon')
            ),
        ];
    }
}
