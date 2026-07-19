<?php

namespace App\Actions\Patient\Appointment;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Enums\Appointment\AppointmentTypeEnum;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Events\Notification\AppointmentBooked;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Models\Appointment;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class BookAppointmentAction
{
    public function execute(User $patient, array $data): Appointment
    {
        $appointment = DB::transaction(function () use ($patient, $data) {

            $slot = DoctorAvailabilitySlot::where('id', $data['slot_id'])
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                throw new NotFoundException(__('messages.appointment_slot_not_found_or_unavailable'));
            }

            if ($slot->date->setTimeFromTimeString($slot->getRawOriginal('start_time'))->isPast()) {
                throw new InvalidArgumentException(__('messages.appointment_slot_in_the_past'));
            }

            if ($slot->type !== AppointmentTypeEnum::IN_PERSON) {
                throw new InvalidArgumentException(__('messages.invalid_consultation_type'));
            }

            if (!$slot->isAvailable()) {
                throw new NotFoundException(__('messages.appointment_slot_not_found_or_unavailable'));
            }

            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $slot->doctor_id,
                'slot_id' => $slot->id,
                'type' => $slot->type,
                'status' => AppointmentStatusEnum::CONFIRMED,
                'confirmed_at' => now(),
                'reason_for_visit' => $data['reason_for_visit'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            // activity()
            //     ->performedOn($appointment)
            //     ->causedBy($patient)
            //     ->withProperties([
            //         'status' => AppointmentStatusEnum::CONFIRMED->value,
            //         'message' => "Scheduled with Dr. {$appointment->doctor?->full_name}",
            //     ])
            //     ->log('Appointment Created');

            $slot->update(['status' => AvailabilitySlotStatusEnum::BOOKED]);

            return $appointment->load(['doctor', 'slot', 'slot.clinic', 'patient']);
        });

        event(new AppointmentBooked($appointment));
        
        return $appointment;
    }
}
