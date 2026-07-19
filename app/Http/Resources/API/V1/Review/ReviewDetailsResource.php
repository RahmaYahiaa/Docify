<?php

namespace App\Http\Resources\API\V1\Review;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'patient_name' => $this->when($user?->isPatient(), function() {
                return $this->user?->full_name;
            }),
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at?->diffForHumans(),
        ];
    }
}
