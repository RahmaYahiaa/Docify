<?php

namespace App\Http\Resources\API\V1\Specialty;

use App\Enums\Specialty\SpecialtyStatusEnum;
use App\Models\Specialization;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpecializationDashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $specializations = Specialization::withCount('doctorProfiles')->get();

        return [
            'stats' => [
                'total'    => $specializations->count(),
                'active'   => $specializations->where('status', SpecialtyStatusEnum::ACTIVE->value)->count(),
                'disabled' => $specializations->where('status', SpecialtyStatusEnum::DISABLED->value)->count(),
            ],
            'specialties' => SpecializationDetailsResource::collection($specializations),
        ];
    }
}
