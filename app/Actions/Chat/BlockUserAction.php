<?php

namespace App\Actions\Chat;

use App\Exceptions\ForbiddenException;
use App\Models\UserBlock;

class BlockUserAction
{
   public function execute(int $blockerId, int $blockedId): void
    {
        if ($blockerId === $blockedId) {
            throw new ForbiddenException(__('messages.you_cannot_block_yourself'));
        }
        UserBlock::firstOrCreate([
            'blocker_id' => $blockerId,
            'blocked_id' => $blockedId,
        ]);
    }
}
