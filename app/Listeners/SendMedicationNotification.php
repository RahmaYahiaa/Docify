<?php

namespace App\Listeners;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Events\Notification\MedicationDue;
use App\Services\Notification\DeliveryService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMedicationNotification 
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MedicationDue $event): void
    {
        $title = "Medication Reminder";
        $body = "Time to take {$event->dose->medication->name}";

        $data = [
            'medication_id' => $event->dose->medication_id,
            'dose_id' => $event->dose->id, 
            // 'priority' => 'high' 
        ];

        app(DeliveryService::class)->notifyUser(
            $event->user,
            NotificationType::MEDICATIONS,
            $title,
            $body,
            $data,
            [
                DeliveryChannel::PUSH,
                DeliveryChannel::IN_APP,
            ]
        );
    }
}
