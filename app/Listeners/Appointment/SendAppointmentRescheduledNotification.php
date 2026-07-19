<?php

namespace App\Listeners\Appointment;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\AppointmentRescheduled;
use App\Services\Notification\DeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentRescheduledNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(protected DeliveryService $delivery)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AppointmentRescheduled $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        $oldSlotId = $event->oldSlotId;
        $newSlotId = $event->newSlotId;

        $this->delivery->notifyUser(
            user: $doctor,
            type: NotificationType::APPOINTMENTS,
            title: 'Appointment Rescheduled',
            body: "Patient {$patient->full_name} rescheduled the appointment.",
            data: [
                'appointment_id' => $appointment->id,
                'old_slot_id' => $oldSlotId,
                'new_slot_id' => $newSlotId,
            ],
            channels: [
                DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
            ]
        );

        foreach ($doctor->assistants as $assistant) {
            $this->delivery->notifyUser(
                user: $assistant,
                type: NotificationType::APPOINTMENTS,
                title: 'Appointment Rescheduled',
                body: "Patient {$patient->full_name} rescheduled the appointment.",
                data: [
                    'appointment_id' => $appointment->id,
                    'old_slot_id' => $oldSlotId,
                    'new_slot_id' => $newSlotId,
                ],
                channels: [
                    DeliveryChannel::PUSH,
                    DeliveryChannel::IN_APP,
                ]
            );
        }
    }
}
