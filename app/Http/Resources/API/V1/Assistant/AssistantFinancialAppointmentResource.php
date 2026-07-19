<?php

namespace App\Http\Resources\API\V1\Assistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssistantFinancialAppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {$doctorFees = $this['doctor_fees'] ?? 0;
       return [

            'stats' => [
                'pending_amount'   => $this['pending_list']->count() * $doctorFees,
                'pending_count'    => $this['pending_list']->count(),
                'collected_amount' => $this['collected_list']->count() * $doctorFees,
                'collected_count'  => $this['collected_list']->count(),

            ],

            'pending_appointments'   => AppointmentResource::collection($this['pending_list']),
            'collected_appointments' => AppointmentResource::collection($this['collected_list']),
        ];
    }
}
