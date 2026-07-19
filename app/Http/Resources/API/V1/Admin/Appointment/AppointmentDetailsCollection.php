<?php

namespace App\Http\Resources\API\V1\Admin\Appointment;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Http\Resources\BasePaginationResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentDetailsCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects =  AppointmentDetailsResource::class;

    public function toArray(Request $request): array
    {
        return [
            'stats' => [
                'all'        => Appointment::count(),
                'completed'  => Appointment::withStatus(AppointmentStatusEnum::COMPLETED)->count(),
                'confirmed'  => Appointment::withStatus(AppointmentStatusEnum::CONFIRMED)->count(),
                'cancelled'  => Appointment::withStatus(AppointmentStatusEnum::CANCELLED)->count(),
                'no_show'    => Appointment::withStatus(AppointmentStatusEnum::NO_SHOW)->count(),
            ],

            'data' => $this->collection,
        ];
    }
}
