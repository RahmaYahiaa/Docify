<?php

namespace App\Http\Resources\API\V1\Patient\Appointment;

use App\Http\Resources\API\V1\Doctor\Appointment\ClinicResource;
use App\Http\Resources\API\V1\Doctor\Appointment\DoctorBasicResource;
use App\Http\Resources\API\V1\Payment\PaymentResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        $slot = $this->slot;
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'status_label' => $this->status->label(),
            'type' => $this->type->value,
            'type_label' => $this->type->label(),
            'date' => $slot->date->format('Y-m-d'),
            'start_time' => Carbon::parse($slot->getRawOriginal('start_time'))->format('H:i'),
            'end_time' => $slot->end_time,
            'duration_minutes' => $slot->duration_minutes,
            'reason_for_visit' => $this->reason_for_visit,
            'notes' => $this->notes,
            'booked_on' => $this->created_at->format('M d, Y \a\t h:i A'), // Jan 4, 2026 at 3:30 PM
            'is_cancellable'   => $this->isCancellable(),
            'is_reschedulable' => $this->isReschedulable(),
            'doctor' => new DoctorBasicResource($this->doctor),
            'clinic' => $this->when(
                $this->type->value === 'in_person' && $slot->clinic,
                fn() => new ClinicResource($slot->clinic)
            ),
            'payment' => $this->when(
                $this->type->value === 'video' && $this->payment,
                fn() => new PaymentResource($this->payment)
            ),
            'Payment_Details' => $this->when(
                $this->type->value === 'in_person',
                fn() => [
                    'consultation_fee' => $this->slot->doctor->doctorProfile?->in_person_fee,
                    'payment_method'   => 'Pay at clinic',
                ]
            ),
            'cancelled' => $this->when(
                $this->status->value === 'cancelled',
                fn() => [
                    'cancelled_at' => $this->cancelled_at?->format('M d, Y \a\t h:i A'),
                    'cancel_reason' => $this->cancel_reason?->label(),
                    'cancel_notes' => $this->cancel_notes,
                ]
            ),
        ];
    }
}
