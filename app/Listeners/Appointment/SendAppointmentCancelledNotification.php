<?php

namespace App\Listeners\Appointment;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\AppointmentCancelled;
use App\Services\Notification\DeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentCancelledNotification 
{
    public function __construct(
        protected DeliveryService $delivery
    ) {}

    public function handle(AppointmentCancelled $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        $cancelledBy = $event->cancelledBy;

        // Patient notification
        // $this->delivery->notifyUser(
        //     user: $patient,
        //     type: NotificationType::APPOINTMENTS,
        //     title: 'Appointment Cancelled',
        //     body: "Your appointment with Dr. {$doctor->full_name} was cancelled.",
        //     data: [
        //         'appointment_id' => $appointment->id,
        //         'cancelled_by' => $cancelledBy,
        //     ],
        //     channels: [
        //         DeliveryChannel::PUSH,
        //         DeliveryChannel::IN_APP,
        //     ]
        // );

        // doctor notification
        $this->delivery->notifyUser(
            user: $doctor,
            type: NotificationType::APPOINTMENTS,
            title: 'Appointment Cancelled',
            body: "Appointment with {$patient->full_name} was cancelled.",
            data: [
                'appointment_id' => $appointment->id,
                'cancelled_by' => $cancelledBy,
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
                title: 'Appointment Cancelled',
                body: "Appointment with {$patient->full_name} was cancelled.",
                data: [
                    'appointment_id' => $appointment->id,
                    'cancelled_by' => $cancelledBy,
                ],
                channels: [
                    DeliveryChannel::PUSH,
                    DeliveryChannel::IN_APP,
                ]
            );
        }
    }
}
