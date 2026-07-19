<?php

namespace App\Http\Resources\API\V1\Patient\ChronicCondition;

use App\Models\ChronicCondition; // تأكدي من مسار الموديل عندك
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChronicConditionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var ChronicCondition $condition */
        $condition = $this->resource;

        return [
            'id' => $this->id,
            'name' => $this->whenHas('name', fn () => $condition->name),

        ];
    }
}