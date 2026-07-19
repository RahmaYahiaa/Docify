<?php

namespace App\Http\Resources\API\V1\Doctor\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultationFeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (is_null($this->resource)) {
        return [
            'video_fee' => null,
            'in_person_fee' => null,
        ];
    }   return [

         'video_fee' => $this->video_fee ,
    'in_person_fee' => $this->in_person_fee,
     ];
    }
}
