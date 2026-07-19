<?php

namespace App\Actions\Chat;

use App\Events\Chat\TypingIndicator;
use App\Exceptions\ForbiddenException;
use App\Models\Conversation;

class SendTypingIndicatorAction
{
    public function execute(Conversation $conversation, int $userId, bool $isTyping): void
    {
        if (! $conversation->users()->where('user_id', $userId)->exists()) {
            throw new ForbiddenException(__('messages.you_are_not_part_of_this_conversation'));
        }

        TypingIndicator::dispatch($conversation->id, $userId, $isTyping);
    }
}
