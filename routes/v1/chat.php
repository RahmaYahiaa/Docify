<?php

use App\Http\Controllers\API\V1\Chat\BlockController;
use App\Http\Controllers\API\V1\Chat\ConversationController;
use App\Http\Controllers\API\V1\Chat\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rate limiting strategy
|--------------------------------------------------------------------------
|
|  120/min  → typing indicators     high-frequency, no DB write
|   60/min  → read operations       fetching data, low overhead
|   30/min  → standard writes       initiate, read-receipts, block, presence
|   20/min  → message sends         strict to prevent spam
|
| All limits are per-user per-minute via Laravel's throttle middleware
| keyed by authenticated user ID + IP.
|
*/

Route::middleware('auth:sanctum')->group(function () {

    // Conversations
    Route::post('conversations/initiate', [ConversationController::class, 'initiate'])
        ->middleware('throttle:30,1')
        ->name('chat.conversations.initiate');

    Route::get('conversations', [ConversationController::class, 'index'])
        ->middleware('throttle:60,1')
        ->name('chat.conversations.index');

    Route::get('conversations/{conversation}/messages', [ConversationController::class, 'show'])
        ->middleware('throttle:60,1')
        ->name('chat.conversations.messages.index');

    Route::post('conversations/{conversation}/read', [ConversationController::class, 'markAsRead'])
        ->middleware('throttle:30,1')
        ->name('chat.conversations.messages.read');

    Route::post('conversations/{conversation}/typing', [ConversationController::class, 'typing'])
        ->middleware('throttle:120,1')
        ->name('chat.conversations.typing');

    // Presence — notify backend when user opens/closes a conversation
    // A heartbeat every ~60s is recommended to refresh the Redis TTL.

    Route::post('conversations/{conversation}/presence', [ConversationController::class, 'join'])
        ->middleware('throttle:30,1')
        ->name('chat.conversations.presence.join');

    Route::delete('conversations/{conversation}/presence', [ConversationController::class, 'leave'])
        ->middleware('throttle:30,1')
        ->name('chat.conversations.presence.leave');

    // Messages
    Route::post('conversations/{conversation}/messages', [MessageController::class, 'store'])
        ->middleware('throttle:20,1')
        ->name('chat.conversations.messages.store');

    Route::delete('conversations/{conversation}/messages/{message}', [MessageController::class, 'destroy'])
        ->middleware('throttle:20,1')
        ->name('chat.conversations.messages.destroy');

    // Block / Unblock

    Route::post('users/{user}/block', [BlockController::class, 'block'])
        ->middleware('throttle:30,1')
        ->name('chat.users.block');

    Route::delete('users/{user}/block', [BlockController::class, 'unblock'])
        ->middleware('throttle:30,1')
        ->name('chat.users.unblock');
});
