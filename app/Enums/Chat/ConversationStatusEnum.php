<?php

namespace App\Enums\Chat;

enum ConversationStatusEnum: string

{
    case PENDING = 'pending';
    case ACTIVE  = 'active';
}
