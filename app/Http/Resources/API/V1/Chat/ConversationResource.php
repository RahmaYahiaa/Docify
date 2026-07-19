<?php

namespace App\Http\Resources\API\V1\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUser = $request->user();
        $otherUser   = $this->users->where('id', '!=', $currentUser->id)->first()
            ?? $this->users->first();

        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'other_user'   => $otherUser ? array_merge(
                $otherUser->getDisplayInfo(),
                ['is_blocked' => $currentUser->hasBlocked($otherUser->id)]
            ) : null,
            'unread_count' => $this->pivot->unread_count ?? 0,

            'last_message' => $this->when($this->lastMessage, fn() => [
                'body'      => $this->lastMessage->body,
                'type'      => $this->lastMessage->type,
                'sender_id' => $this->lastMessage->sender_id,
                'is_mine'   => $this->lastMessage->sender_id === $currentUser->id,
                'created_at' => $this->created_at?->toISOString(),
            ]),

            'last_message_at' => $this->last_message_at?->toISOString(),
        ];
    }
}
