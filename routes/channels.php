<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $cacheKey = "chat.auth.{$user->id}.{$conversationId}";
    return Cache::remember($cacheKey, now()->addMinutes(60), function () use ($user, $conversationId) {
        return $user->conversations()
            ->where('conversations.id', $conversationId)
            ->exists();
    });
});
Broadcast::channel('presence-user.{id}', function ($user, $id) {
    if ((int) $user->id !== (int) $id) {
        return false;
    }
    return ['id'   => $user->id, 'name' => $user->full_name,];
});
