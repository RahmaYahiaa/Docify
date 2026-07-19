<?php

namespace App\Actions\Notification;

use App\Models\FcmToken;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreFcmTokenAction
{
    use AsAction;

    public function execute(int $user, array $data)
    {
        FcmToken::where('token', $data['token'])->delete();

        FcmToken::create([
            'user_id'     => $user,
            'token'       => $data['token'],
            'device_type' => $data['device_type'] ?? null,
        ]);
    }
}
