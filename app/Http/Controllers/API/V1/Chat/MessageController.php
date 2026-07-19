<?php

namespace App\Http\Controllers\API\V1\Chat;

use App\Actions\Chat\DeleteMessageAction;
use App\Actions\Chat\SendMessageAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Chat\SendMessageRequest;
use App\Http\Resources\API\V1\Chat\MessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{public function store(SendMessageRequest $request, Conversation $conversation, SendMessageAction $action): JsonResponse
    {
        try {
            $message = $action->execute($request->validated(), $request->user(), $conversation);

            return $this->ok(__('messages.message_sent_successfully'), new MessageResource($message));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Request $request, Conversation $conversation, Message $message, DeleteMessageAction $action): JsonResponse
    {
        $action->execute($message, $conversation, $request->user()->id);

        return $this->ok(__('messages.message_deleted_successfully'));
    }
}
