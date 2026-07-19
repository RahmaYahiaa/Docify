<?php

namespace App\Http\Controllers\API\V1\Chat;

use App\Actions\Chat\BlockUserAction;
use App\Actions\Chat\UnblockUserAction;
use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function block(Request $request, User $user, BlockUserAction $action): JsonResponse
    {
        $action->execute($request->user()->id, $user->id);

        return $this->ok(__('messages.user_blocked_successfully'));
    }

    public function unblock(Request $request, User $user, UnblockUserAction $action): JsonResponse
    {
        $action->execute($request->user()->id, $user->id);

        return $this->ok(__('messages.user_unblocked_successfully'));
    }
}
