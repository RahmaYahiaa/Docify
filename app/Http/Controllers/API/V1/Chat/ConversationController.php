<?php

namespace App\Http\Controllers\API\V1\Chat;

use App\Actions\Chat\GetConversationMessagesAction;
use App\Actions\Chat\InitiateConversationAction;
use App\Actions\Chat\ListUserConversationsAction;
use App\Actions\Chat\MarkConversationAsLeftAction;
use App\Actions\Chat\MarkConversationAsViewingAction;
use App\Actions\Chat\MarkMessagesAsReadAction;
use App\Actions\Chat\SendTypingIndicatorAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Chat\InitiateConversationRequest;
use App\Http\Requests\API\V1\Chat\TypingRequest;
use App\Http\Resources\API\V1\Chat\ConversationCollection;
use App\Http\Resources\API\V1\Chat\ConversationMessagesResource;
use App\Http\Resources\API\V1\Chat\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function initiate(InitiateConversationRequest $request, InitiateConversationAction $action): JsonResponse
    {
        $conversation = $action->execute($request->user(), $request->integer('receiver_id'));
        return $this->ok(
            __('messages.conversation_initiated_successfully'),
            [
                'conversation' => new ConversationResource($conversation['conversation']),
                'is_new' => $conversation['is_new']
            ]
        );
    }

    public function index(Request $request, ListUserConversationsAction $action): JsonResponse
    {
        $conversations = $action->execute($request->user()->id, $request->query('search'));

        return $this->ok(__('messages.conversations_retrieved_successfully'), new ConversationCollection($conversations));
    }

    public function show(Request $request, Conversation $conversation, GetConversationMessagesAction $action): JsonResponse
    {
        $messages = $action->execute($conversation, $request->user()->id);

        return $this->ok(__('messages.conversation_messages_retrieved_successfully'), new ConversationMessagesResource($messages));
    }

    public function markAsRead(Request $request, Conversation $conversation, MarkMessagesAsReadAction $action): JsonResponse
    {
        $action->execute($conversation, $request->user()->id);

        return $this->ok(__('messages.messages_marked_as_read'));
    }

    public function typing(TypingRequest $request, Conversation $conversation, SendTypingIndicatorAction $action): JsonResponse
    {
        $action->execute($conversation, $request->user()->id, $request->boolean('is_typing'));

        return $this->ok(__('messages.typing_indicator_sent'));
    }


    public function join(Request $request, Conversation $conversation, MarkConversationAsViewingAction $action): JsonResponse
    {
        $action->execute($request->user()->id, $conversation);

        return $this->ok(__('messages.presence_updated'));
    }

    public function leave(Request $request, Conversation $conversation, MarkConversationAsLeftAction $action): JsonResponse
    {
        $action->execute($request->user()->id, $conversation);

        return $this->ok(__('messages.presence_updated'));
    }
}
