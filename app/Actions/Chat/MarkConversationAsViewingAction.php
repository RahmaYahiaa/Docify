<?php

namespace App\Actions\Chat;

use App\Exceptions\ForbiddenException;
use App\Models\Conversation;
use App\Services\Chat\PresenceService;

class MarkConversationAsViewingAction
{
    public function __construct(private readonly PresenceService $presence) {}

    public function execute(int $userId, Conversation $conversation): void
    {
        if (! $conversation->users()->where('user_id', $userId)->exists()) {
            throw new ForbiddenException(__('messages.you_are_not_part_of_this_conversation'));
        }

        $this->presence->markAsViewing($userId, $conversation->id);
    }
}