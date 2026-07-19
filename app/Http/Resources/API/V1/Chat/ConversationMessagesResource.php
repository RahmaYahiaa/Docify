<?php

namespace App\Http\Resources\API\V1\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationMessagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currentUser   = $request->user();
        $currentUserId = $this->resource['current_user_id'];
        $conversation  = $this->resource['conversation'];
        $messages      = $this->resource['messages'];

        $sender   = $conversation->users->firstWhere('id', $currentUserId);
        $receiver = $conversation->users->firstWhere('id', '!=', $currentUserId);

        return [
            'conversation' => [
                'id'       => $conversation->id,
                'sender'   => $sender?->getDisplayInfo(),
                'receiver' => $receiver ? array_merge(
                    $receiver->getDisplayInfo(),
                    ['is_blocked' => $currentUser->hasBlocked($receiver->id)]
                ) : null,
            ],
            'messages' => new MessageCollection($messages),
        ];
    }
}
