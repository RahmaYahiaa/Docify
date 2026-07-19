<?php

namespace App\Actions\Chat;

use App\Events\Chat\MessageSent;
use App\Exceptions\ForbiddenException;
use App\Jobs\Chat\SendChatNotificationJob;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User\User;
use App\Services\Chat\PresenceService;
use Illuminate\Support\Facades\DB;

class SendMessageAction
{
    public function __construct(private readonly PresenceService $presence) {}

    public function execute(array $data, User $sender, Conversation $conversation): Message
    {
        $participantIds = $conversation->users()->pluck('users.id');

        if (! $participantIds->contains($sender->id)) {
            throw new ForbiddenException(__('messages.you_are_not_part_of_this_conversation'));
        }

        $receiverId = $participantIds->firstWhere(fn($id) => $id !== $sender->id);

        if ($sender->hasBlockRelationWith($receiverId)) {
            throw new ForbiddenException(__('messages.you_cannot_message_this_user'));
        }

        $message = DB::transaction(function () use ($data, $sender, $conversation) {
            $message = Message::create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $sender->id,
                'body'            => $data['body'] ?? null,
                'type'            => $data['type'] ?? 'text',
            ]);

            if (! empty($data['attachment'])) {
                $this->handleAttachment($message, $data);
            }

            $conversation->updateQuietly(['last_message_at' => now()]);

            /* A conversation stays "pending" (hidden from lists) until .
             the first message is actually sent — just like WhatsApp.*/
            $conversation->activate();
            $conversation->users()
                ->where('user_id', '!=', $sender->id)
                ->increment('unread_count');

            $message->setRelation('sender', $sender);

            return $message;
        });

        MessageSent::dispatch($message);

        if (! $this->presence->isViewing($receiverId, $conversation->id)) {
            SendChatNotificationJob::dispatch($message, $receiverId);
        }

        return $message;
    }

    private function handleAttachment(Message $message, array $data): void
    {
        $collection = ($data['type'] === 'voice') ? 'voice_messages' : 'chat_attachments';

        $media = $message->addMedia($data['attachment'])
            ->toMediaCollection($collection);

        $message->update([
            'media_id'        => $media->id,
            'attachment_url'  => $media->getUrl(),
            'attachment_type' => $media->mime_type,
            'attachment_name' => $media->name,
            'attachment_size' => $media->size,
            'voice_duration'  => $data['voice_duration'] ?? null,
        ]);
    }
}
