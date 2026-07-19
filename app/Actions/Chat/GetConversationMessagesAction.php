<?php

namespace App\Actions\Chat;

use App\Exceptions\ForbiddenException;
use App\Models\Conversation;

class GetConversationMessagesAction
{
    public function execute(Conversation $conversation, $userId)
    {
        $conversation->load([
            'users' => fn($q) => $q
                ->select(['users.id', 'users.first_name', 'users.last_name'])
                ->with([
                    'roles:id,name',
                    'doctorProfile' => fn($q) => $q
                        ->select(['id', 'user_id', 'specialization_id'])
                        ->with(['specialization' => fn($sq) => $sq->select('id', 'name')]),
                    'patientProfile' => fn($q) => $q->select(['id', 'user_id']),
                ]),
        ]);

        if (! $conversation->users->contains('id', $userId)) {
            throw new ForbiddenException(__('messages.you_are_not_part_of_this_conversation'));
        }

        $messages = $conversation->messages()
            ->with('sender:id,first_name,last_name')
            ->latest('created_at')
            ->paginate(20);

        return [
            'conversation'    => $conversation,
            'messages'        => $messages,
            'current_user_id' => $userId,
        ];
    }
}
