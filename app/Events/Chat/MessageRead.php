<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $conversationId;
    public int $readerId;

    public function __construct(int $conversationId, int $readerId)
    {
        $this->conversationId = $conversationId;
        $this->readerId = $readerId;
    }
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }

    public function broadcastAs(): string
    {
        return 'message.read';
    }
    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->conversationId,
            'reader_id'       => $this->readerId,
            'read_at'         => now()->toISOString(),
        ];
    }
}
