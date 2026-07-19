<?php

namespace App\Actions\Doctor\Availability;

use App\Exceptions\ForbiddenException;
use App\Exceptions\InvalidArgumentException;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;

class DeleteDoctorSlotAction
{
    public function execute(User $doctor, DoctorAvailabilitySlot $slot): void
    {
        if ($slot->doctor_id !== $doctor->id) {
            throw new ForbiddenException(__('messages.unauthorized_action'));
        }

        if ($slot->isBooked()) {
            throw new InvalidArgumentException(
                __('messages.cannot_delete_booked_slot')
            );
        }

        if ($slot->isLocked()) {
            throw new InvalidArgumentException(
                __('messages.cannot_delete_locked_slot')
            );
        }

        $slot->delete();
    }
}