<?php

namespace App\Listeners\Appointment;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\AppointmentReminder;
use App\Services\Notification\DeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentReminderNotification implements ShouldQueue
{
    public function __construct(
        protected DeliveryService $delivery
    ) {}

    public function handle(AppointmentReminder $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;
        $doctor = $appointment->doctor;


        $this->delivery->notifyUser(
            user: $patient,
            type: NotificationType::APPOINTMENTS,
            title: 'Appointment Reminder',
            body: "You have an appointment with Dr. {$doctor->full_name} in 6 hours.",
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