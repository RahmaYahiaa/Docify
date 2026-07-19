<?php

namespace App\Actions\Chat;

use App\Models\UserBlock;

class UnblockUserAction
{
    public function execute(int $blockerId, int $blockedId): void
    {
        UserBlock::where('blocker_id', $blockerId)
            ->where('blocked_id', $blockedId)
            ->delete();
    }
}
