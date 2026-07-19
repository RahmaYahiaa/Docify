<?php

namespace App\Actions\Chat;

use App\Exceptions\ForbiddenException;
use App\Models\Conversation;
use App\Models\User\User;

class InitiateConversationAction
{
    public function execute(User $initiator, int $receiverId): array
    {
        if ($initiator->hasBlockRelationWith($receiverId)) {
            throw new ForbiddenException(__('messages.you_cannot_message_this_user'));
        }

        $existing = $this->findExistingConversation($initiator->id, $receiverId);
        if ($existing) {
            return ['conversation' => $existing, 'is_new' => false];
        }

        $conversation = $this->createConversation($initiator->id, $receiverId);
        return ['conversation' => $conversation, 'is_new' => true];
    }

    private function findExistingConversation(int $sender, int $receiver): ?Conversation
    {
        return Conversation::query()
            ->where('conversations.type', 'private')
            ->join('conversation_user as cu1', function ($join) use ($sender) {
                $join->on('cu1.conversation_id', '=', 'conversations.id')
                    ->where('cu1.user_id', '=', $sender);
            })
            ->join('conversation_user as cu2', function ($join) use ($receiver) {
                $join->on('cu2.conversation_id', '=', 'conversations.id')
                    ->where('cu2.user_id', '=', $receiver);
            })
            ->select('conversations.*')
            ->first();
    }
    private function createConversation(int $senderId, int $receiverId): Conversation
    {
        $conversation = Conversation::create(['type' => 'private']);
        $conversation->users()->attach([
            $senderId => ['joined_at' => now(), 'unread_count' => 0],
            $receiverId => ['joined_at' => now(), 'unread_count' => 0],
        ]);

        return $conversation;
    }
}
