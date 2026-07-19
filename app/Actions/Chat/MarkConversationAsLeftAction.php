<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use App\Services\Chat\PresenceService;

class MarkConversationAsLeftAction
{
    public function __construct(private readonly PresenceService $presence) {}

    public function execute(int $userId, Conversation $conversation): void
    {
        // No authorization needed — leaving is always allowed.
        // Even if the user isn't in the conversation, deleting a non-existent key is a no-op.
        $this->presence->markAsLeft($userId, $conversation->id);
    }
}