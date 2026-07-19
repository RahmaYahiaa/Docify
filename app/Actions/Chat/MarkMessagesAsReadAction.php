<?php

namespace App\Actions\Chat;

use App\Events\Chat\MessageRead;
use App\Exceptions\ForbiddenException;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;

class MarkMessagesAsReadAction
{
    public function execute(Conversation $conversation, int $userId): void
    {
        if (! $conversation->users()->where('user_id', $userId)->exists()) {
            throw new ForbiddenException(__('messages.you_are_not_part_of_this_conversation'));
        }

        DB::transaction(function () use ($conversation, $userId) {
            $conversation->users()
                ->where('user_id', $userId)
                ->update(['unread_count' => 0]);

            $updatedCount = $conversation->messages()
                ->where('sender_id', '!=', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            if ($updatedCount > 0) {
                MessageRead::dispatch($conversation->id, $userId);
            }
        });
    }
}
