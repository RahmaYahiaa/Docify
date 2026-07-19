<?php

namespace App\Enums\Notification;

enum DeliveryChannel: string
{
    case PUSH = 'push';
    case EMAIL = 'email';
    case IN_APP = 'in_app';
}
