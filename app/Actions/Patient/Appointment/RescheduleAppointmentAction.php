<?php

namespace App\Actions\Patient\Appointment;

use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Events\Notification\AppointmentRescheduled;
use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Exceptions\NotFoundException;
use App\Models\Appointment;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class RescheduleAppointmentAction
{
    public function execute(Appointment $appointment, User $patient, int $newSlotId): Appointment
    {
        $oldSlotId = $appointment->slot_id;

        $result = DB::transaction(function () use ($appointment, $patient, $newSlotId, $oldSlotId) {
            $appointment = Appointment::where('id', $appointment->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($appointment->patient_id !== $patient->id) {
                throw new ForbiddenException(__('messages.unauthorized_action'));
            }

            if (!$appointment->status->canReschedule()) {
                throw new InvalidArgumentException(__('messages.cannot_reschedule_finalized_appointment'));
            }

            $appointmentDateTime = $appointment->slot->date
                ->setTimeFromTimeString($appointment->slot->getRawOriginal('start_time'));

            if ($appointmentDateTime->isBefore(now()->addHours(24))) {
                throw new InvalidArgumentException(__('messages.cannot_reschedule_within_24_hours'));
            }

            $newSlot = DoctorAvailabilitySlot::where('id', $newSlotId)
                ->lockForUpdate()
                ->first();

            if (!$newSlot || !$newSlot->isAvailable()) {
                throw new NotFoundException(__('messages.appointment_slot_not_found_or_unavailable'));
            }

            if ($newSlot->date->setTimeFromTimeString($newSlot->getRawOriginal('start_time'))->isPast()) {
                throw new InvalidArgumentException(__('messages.appointment_slot_in_the_past'));
            }

            if ($newSlot->doctor_id !== $appointment->doctor_id) {
                throw new InvalidArgumentException(__('messages.reschedule_doctor_mismatch'));
            }

            if ($newSlot->type !== $appointment->type) {
                throw new InvalidArgumentException(__('messages.reschedule_type_mismatch'));
            }

            $oldSlot = DoctorAvailabilitySlot::where('id', $appointment->slot_id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldSlot->update(['status' => AvailabilitySlotStatusEnum::AVAILABLE]);
            $appointment->update(['slot_id' => $newSlot->id]);
            $newSlot->update(['status' => AvailabilitySlotStatusEnum::BOOKED]);

            return [
                'appointment' => $appointment->fresh()->load([
                    'doctor', 
                    'slot', 
                    'slot.clinic', 
                    'patient', 
                    'doctor.assistants'
                ]),
                'old_slot_id' => $oldSlotId,
                'new_slot_id' => $newSlotId
            ];
        });

        event(new AppointmentRescheduled(
            $result['appointment'],
            $result['old_slot_id'],
            $result['new_slot_id'],
            (string) $patient->id
        ));

        return $result['appointment'];
    }
}