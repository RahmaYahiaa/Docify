<?php

namespace App\Jobs\Chat;

use App\Enums\Notification\DeliveryChannel;
use App\Enums\Notification\NotificationType;
use App\Models\Message;
use App\Models\User\User;
use App\Services\Chat\PresenceService;
use App\Services\Notification\DeliveryService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendChatNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 15;

    public function __construct(
        private readonly Message $message,
        private readonly int $receiverId
    ) {}

    public function handle(PresenceService $presence, DeliveryService $delivery): void
    {

        if ($presence->isViewing($this->receiverId, $this->message->conversation_id)) {
            Log::info('Chat notification suppressed: receiver is viewing the conversation.', [
                'receiver_id'     => $this->receiverId,
                'conversation_id' => $this->message->conversation_id,
            ]);
            return;
        }

        $receiver = User::with(['fcmTokens'])
            ->find($this->receiverId);

        if (! $receiver) {
            Log::warning('Chat notification aborted: receiver not found.', [
                'receiver_id' => $this->receiverId,
            ]);
            return;
        }

        $senderName = $this->message->sender?->full_name ?? 'Someone';
        $title      = $senderName;
        $body       = $this->resolveBody();

        $delivery->notifyUser(
            user:     $receiver,
            type:     NotificationType::CHAT,
            title:    $title,
            body:     $body,
            data:     [
                'conversation_id' => (string) $this->message->conversation_id,
                'message_id'      => (string) $this->message->id,
                'message_type'    => $this->message->type,
                'sender_id'       => (string) $this->message->sender_id,
            ],
            channels: [DeliveryChannel::PUSH, DeliveryChannel::IN_APP],
        );
    }

    private function resolveBody(): string
    {
        return match ($this->message->type) {
            'image' => ' Sent a photo',
            'voice' => ' Sent a voice message',
            'file'  => ' Sent a file',
            default => $this->message->body ?? 'Sent you a message',
        };
    }

    public function failed(\Throwable $e): void
    {
        Log::error('SendChatNotificationJob failed permanently.', [
            'message_id'  => $this->message->id,
            'receiver_id' => $this->receiverId,
            'error'       => $e->getMessage(),
        ]);
    }
    
}