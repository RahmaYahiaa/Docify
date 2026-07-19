<?php

namespace App\Listeners\Appointment;

use App\Enums\Appointment\AppointmentTypeEnum;
use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\AppointmentBooked;
use App\Services\Notification\DeliveryService;

class SendAppointmentBookedNotification
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
    public function handle(AppointmentBooked $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        if ($appointment->type === AppointmentTypeEnum::VIDEO) {

            $patientBody =
                "Your online appointment with Dr. {$doctor->full_name} has been confirmed.";

            $doctorBody =
                "{$patient->full_name} booked an online appointment with you.";
        } else {

            $patientBody =
                "Your appointment with Dr. {$doctor->full_name} has been confirmed.";

            $doctorBody =
                "{$patient->full_name} booked an appointment with you.";
        }

        $this->delivery->notifyUser(
            user: $patient,
            type: NotificationType::APPOINTMENTS,
            title: 'Appointment Confirmed',
            body: $patientBody,
            data: [
                'appointment_id' => $appointment->id,
                'appointment_type' => $appointment->type->value,
            ],
            channels: [
                // DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
                // DeliveryChannel::EMAIL,
            ]
        );

        $assistants = $doctor->assistants;

        foreach ($assistants as $assistant) {
            $this->delivery->notifyUser(
                user: $assistant,
                type: NotificationType::APPOINTMENTS,
                title: 'New Appointment',
                body: $doctorBody,
                data: [
                    'appointment_id' => $appointment->id,
                    'appointment_type' => $appointment->type->value,
                ],
                channels: [
                    DeliveryChannel::PUSH,
                    DeliveryChannel::IN_APP,
                ]
            );
        }
    }
}
