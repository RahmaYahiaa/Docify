<?php

namespace App\Listeners\Appointment;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\PaymentRefunded;
use App\Services\Notification\DeliveryService;

class SendRefundNotification
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
    public function handle(PaymentRefunded $event): void
    {
        $appointment = $event->appointment;

        $patient = $appointment->patient;
        $doctor = $appointment->doctor;

        $amount = number_format($event->refundAmount, 2);

        $this->delivery->notifyUser(
            user: $patient,
            type: NotificationType::PAYMENTS,
            title: 'Refund Processed',
            body: "A refund of {$amount} has been issued for your appointment with Dr. {$doctor->full_name}.",
            data: [
                'appointment_id' => $appointment->id,
                'refund_amount' => $event->refundAmount,
            ],
            channels: [
                DeliveryChannel::IN_APP,
                DeliveryChannel::EMAIL,
            ]
        );
    }
}
