<?php

namespace App\Enums\Notification;

enum NotificationType : string
{
    case APPOINTMENTS = 'appointments';
    case MEDICATIONS = 'medications';
    case PRESCRIPTIONS = 'prescriptions';
    case PAYMENTS = 'payments';
    case MESSAGES = 'messages';
    case EMERGENCY_ALERTS = 'emergency_alerts';
    case REVIEW = 'review';
    case CHAT = 'chat';

    // public function sendEmail(): bool
    // {
    //     return match($this) {
    //         self::APPOINTMENTS,
    //         self::PAYMENTS,
    //         self::PRESCRIPTIONS,
    //         self::EMERGENCY_ALERTS => true,

    //         default => false,
    //     };
    // }
}
