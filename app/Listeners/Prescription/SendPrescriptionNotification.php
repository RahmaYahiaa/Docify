<?php

namespace App\Listeners\Prescription;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Prescription\PrescriptionCreated;
use App\Services\Notification\DeliveryService;
// use Illuminate\Contracts\Queue\ShouldQueue;

class SendPrescriptionNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(protected DeliveryService $deliveryService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PrescriptionCreated $event): void
    {
        $prescription = $event->prescription;
        $patient = $prescription->patient;

        $title = "New Prescription";
        $body = "{$prescription->doctor->first_name} issued a new prescription";

        $data = [
            'prescription_id' => $prescription->id
        ];

        $this->deliveryService->notifyUser(
            $patient,
            NotificationType::PRESCRIPTIONS,
            $title,
            $body,
            $data,
            [
                DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
                // DeliveryChannel::EMAIL,
            ]
        );
    }
}
