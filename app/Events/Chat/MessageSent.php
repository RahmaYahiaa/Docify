<?php

namespace App\Events\Chat;

use App\Models\Message;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow, ShouldDispatchAfterCommit
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chat.' . $this->message->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
    public function broadcastWith(): array
    {
        $sender = $this->message->sender;

        return [
            'id'              => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'sender'          => $sender ? [
                'id'   => $sender->id,
                'name' => $sender->full_name,
            ] : null,
            'body'            => $this->message->body,
            'type'            => $this->message->type,
            'attachment'      => $this->message->attachment_url ? [
                'url'      => $this->message->attachment_url,
                'type'     => $this->message->attachment_type,
                'name'     => $this->message->attachment_name,
                'size'     => $this->message->attachment_size,
                'duration' => $this->message->type === 'voice' ? $this->message->voice_duration : null,
            ] : null,
            'created_at' => $this->message->created_at->toISOString(),
            'read_at'         => $this->message->read_at?->toISOString(),
        ];
    }
}
