<?php

namespace App\Events\Chat;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingIndicator implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public int $conversationId;
    public int $userId;
    public bool $isTyping;

    public function __construct(int $conversationId, int $userId, bool $isTyping)
    {
        $this->conversationId = $conversationId;
        $this->userId         = $userId;
        $this->isTyping       = $isTyping;
    }
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->conversationId);
    }
    public function broadcastAs(): string
    {
        return 'typing.indicator';
    }
}
