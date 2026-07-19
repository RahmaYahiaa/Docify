<?php

namespace App\Http\Resources\API\V1\Patient;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Http\Resources\API\V1\Patient\Allergy\AllergyResource;
use App\Http\Resources\API\V1\Patient\ChronicCondition\ChronicConditionResource;
use App\Models\Appointment;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    /**
     * @var array
     */
    public $except = [];

   public function toArray(Request $request): array
{
    $patient = $this->resource;

    $userId = $patient->user_id;

   $lastCompletedVisit = $patient->user->appointmentsAsPatient
    ->where('status',  AppointmentStatusEnum::COMPLETED)
    ->filter(function ($appointment) {
        return $appointment->slot !== null;
    })
    ->sortByDesc(function ($appointment) {
        return $appointment->slot->date
            ->setTimeFromTimeString($appointment->slot->start_time);
    })
    ->first();

 $nextAppointment = $patient->user->appointmentsAsPatient
    ->where('status',  AppointmentStatusEnum::CONFIRMED)
    ->filter(fn ($appointment) => $appointment->slot_date_time?->isAfter(now()))
    ->sortBy(fn ($appointment) => $appointment->slot_date_time)
    ->sortBy(function ($appointment) {
        return optional($appointment->slot)->date;
    })
    ->first();
    
    return [
       // 'id' => $patient->id,
       'user_id'=>$userId,
        'name' => $patient->user?->full_name,
        'age' => $patient->age,
        'last_visit' => $lastCompletedVisit?->slot?->date?->format('M d, Y'),
        'next_appointment' => $nextAppointment?->slot?->date?->format('M d, Y'),
        'chronicConditions' => ChronicConditionResource::collection($this->whenLoaded('chronicConditions')),
        'allergies' => AllergyResource::collection($this->whenLoaded('allergies')),
        'created_at' => $patient->created_at?->format('Y-m-d'),
        'contact_information' => $this->when(!in_array('Contact_Information', $this->except), [
            'email' => $patient->user?->email,
            'phone' => $patient->user?->phone,
        ]),
    ];
}
}