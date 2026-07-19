<?php

namespace App\Services\Chat;

use Illuminate\Support\Facades\Redis;

class PresenceService
{
    private const TTL = 120;

    public function markAsViewing(int $userId, int $conversationId): void
    {
        Redis::setex($this->key($userId, $conversationId), self::TTL, 1);
    }

    public function markAsLeft(int $userId, int $conversationId): void
    {
        Redis::del($this->key($userId, $conversationId));
    }

    public function isViewing(int $userId, int $conversationId): bool
    {
        return (bool) Redis::exists($this->key($userId, $conversationId));
    }

    private function key(int $userId, int $conversationId): string
    {
        return "chat:viewing:{$conversationId}:{$userId}";
    }
    
}