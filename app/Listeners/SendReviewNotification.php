<?php

namespace App\Listeners;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\AppointmentCompleted;
use App\Services\Notification\DeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReviewNotification 
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected DeliveryService $delivery
    ) {}

    /**
     * Handle the event.
     */
    public function handle(AppointmentCompleted $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;

        $this->delivery->notifyUser(
            user: $patient,
            type: NotificationType::REVIEW,
            title: 'Rate your doctor',
            body: 'Your session is completed. Please rate your doctor.',
            data: [
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
            ],
            channels: [
                DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
            ]
        );
    }
}
