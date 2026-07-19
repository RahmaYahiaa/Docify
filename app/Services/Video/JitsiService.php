<?php

namespace App\Services\Video;

use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class JitsiService
{
    public const ROLE_PARTICIPANT = 'participant';
    public const ROLE_MODERATOR   = 'moderator';


    public function generateToken(
        string $roomName,
        string $role,
        int    $userId,
        string $userName,
        string $userAvatar = '',
        bool   $moderator = false
    ): array {
        $appId     = config('jitsi.app_id');
        $privateKey = config('jitsi.private_key');
        $keyId     = config('jitsi.api_key_id');
        $ttl       = (int) config('jitsi.token_ttl_minutes', 15);

        $now       = Carbon::now();
        $expiresAt = $now->copy()->addMinutes($ttl);

        $fullRoomName = $appId . '/' . $roomName;

        $payload = [
            'iss'  => 'chat',
            'aud'  => 'jitsi',
            'iat'  => $now->subSeconds(30)->timestamp,
            'exp'  => $expiresAt->timestamp,
            'nbf'  => $now->timestamp,
            'sub'  => $appId,
            'room' => $fullRoomName,
            'context' => [
                'features' => [
                    'livestreaming' => false,
                    'outbound-call' => false,
                    'sip-outbound-call' => false,
                    'transcription' => false,
                    'recording' => false,
                ],
                'user' => [
                    'id'        => (string) $userId,
                    'name'      => $userName,
                    'avatar'    => $userAvatar,
                    'email'     => '',
                    'moderator' => $moderator,
                ],
            ],
        ];

        $token = JWT::encode(
            $payload,
            $privateKey,
            'RS256',
            $keyId
        );

        return [
            'token'      => $token,
            'room_name'  => $fullRoomName,
            'role'       => $role,
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }
}
