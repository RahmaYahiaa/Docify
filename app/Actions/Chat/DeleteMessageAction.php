<?php

namespace App\Actions\Chat;

use App\Events\Chat\MessageDeleted;
use App\Exceptions\ForbiddenException;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class DeleteMessageAction
{
    public function execute(Message $message, Conversation $conversation, int $userId): void
    {
        if ($message->conversation_id !== $conversation->id) {
            throw new ForbiddenException(__('messages.message_does_not_belong_to_this_conversation'));
        }
        if ($message->sender_id !== $userId) {
            throw new ForbiddenException(__('messages.you_can_only_delete_your_own_messages'));
        }
        DB::transaction(function () use ($message) {
            $message->delete();
            MessageDeleted::dispatch($message);
        });
        
    }
}
