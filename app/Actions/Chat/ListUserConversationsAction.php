<?php

namespace App\Actions\Chat;

use App\Models\Conversation;
use Illuminate\Pagination\LengthAwarePaginator;

class ListUserConversationsAction
{
    public function execute(int $userId, ?string $search = null): LengthAwarePaginator
    {
        $query = Conversation::query()
            ->active()
            ->whereHas('users', fn($q) => $q->where('user_id', $userId))
            ->with([
                'users' => fn($q) => $q
                    ->where('users.id', '!=', $userId)
                    ->select(['users.id', 'users.first_name', 'users.last_name'])
                    ->with('roles:id,name')
                    ->with(['doctorProfile.specialization' => fn($sq) => $sq->select('id', 'name')]),
                'lastMessage' => fn($q) => $q->select('messages.id', 'messages.conversation_id', 'messages.body', 'messages.type', 'messages.sender_id'),
            ])
            ->latest('last_message_at');

        if ($search && trim($search) !== '') {
            $searchTerm = '%' . trim($search) . '%';

            $query->whereHas('users', function ($q) use ($searchTerm, $userId) {
                $q->where('user_id', '!=', $userId)
                    ->where(function ($sq) use ($searchTerm) {
                        $sq->where('users.first_name', 'like', $searchTerm)
                            ->orWhere('users.last_name', 'like', $searchTerm)
                            ->orWhereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE ?", [$searchTerm])
                            ->orWhereHas('doctorProfile.specialization', fn($sub) => $sub->where('name', 'like', $searchTerm));
                    });
            });
        }
        return $query->paginate(15);
    }
}
