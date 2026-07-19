<?php

namespace App\Http\Resources\API\V1\Chat;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isMine = $this->sender_id === $request->user()->id;

        return [
            'id'              => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id'       => $this->sender_id,
            'is_mine'         => $isMine,
            'body'            => $this->body,
            'type'            => $this->type,
            'attachment'      => $this->when($this->attachment_url, fn() => [
                'url'      => $this->attachment_url,
                'type'     => $this->attachment_type,
                'name'     => $this->attachment_name,
                'size'     => $this->attachment_size,
                'duration' => $this->when($this->type === 'voice', $this->voice_duration),
            ]),
            'created_at'      => $this->created_at->toISOString(),
            'read_at'          => $this->read_at?->toISOString(),

            'status'          => $this->when($isMine, fn() => $this->read_at ? 'seen' : 'sent'),
        ];
    }
}
