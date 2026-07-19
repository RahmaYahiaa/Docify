<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageDeleted implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $messageId;
    public int $conversationId;
    public string $deletedAt;

    public function __construct(Message $message)
    {
        $this->messageId     = $message->id;
        $this->conversationId = $message->conversation_id;
        $this->deletedAt     = now()->toISOString();
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }
    public function broadcastAs(): string
    {
        return 'message.deleted';
    }
    public function broadcastWith(): array
    {
        return [
            'message_id'      => $this->messageId,
            'conversation_id' => $this->conversationId,
            'deleted_at'     => $this->deletedAt,
        ];
    }
}
