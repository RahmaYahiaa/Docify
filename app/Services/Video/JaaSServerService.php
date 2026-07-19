<?php

namespace App\Services\Video;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Illuminate\Support\Carbon;

class JaaSServerService
{
    private const API_BASE        = 'https://api.jaas.8x8.vc';
    private const TOKEN_CACHE_KEY = 'jaas_server_access_token';

    private function accessToken(): string
    {
        return Cache::remember(self::TOKEN_CACHE_KEY, now()->addMinutes(55), function () {

            $payload = [
                'iss'  => 'chat',
                'aud'  => 'jitsi',
                'iat'  => Carbon::now()->subSeconds(30)->timestamp,
                'exp'  => Carbon::now()->addHour()->timestamp,
                'sub'  => config('jitsi.app_id'),
                'room' => '*',
                'context' => [
                    'user' => [
                        'moderator' => true,
                        'id'        => 'server-admin',
                    ],
                ],
            ];

            return JWT::encode(
                $payload,
                config('jitsi.private_key'),
                'RS256',
                config('jitsi.api_key_id')
            );
        });
    }

    public function listActiveRooms(): array
    {
        $appId    = config('jitsi.app_id');
        $response = Http::withToken($this->accessToken())
            ->get(self::API_BASE . "/v1/rooms/{$appId}");

        if (!$response->successful()) {
            return [];
        }

        return $response->json('rooms') ?? [];
    }

    public function destroyRoom(string $roomName): bool
    {
        $appId    = config('jitsi.app_id');
        $response = Http::withToken($this->accessToken())
            ->delete(self::API_BASE . "/v1/rooms/{$appId}/{$roomName}");

        return $response->successful();
    }
}
