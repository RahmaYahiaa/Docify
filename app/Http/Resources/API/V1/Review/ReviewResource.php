<?php

namespace App\Http\Resources\API\V1\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'reviews_count' => $this->reviews_count,
            'average_rating' => $this->average_rating,
        ];
    }
}
