<?php

namespace App\Services\Auth\Strategies;

use App\Models\User\User;
use App\Services\Auth\UserLoginStateResolver;
use App\Traits\ApiResponseTrait;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleAuthStrategy implements AuthStrategyInterface
{
    use ApiResponseTrait;

    public function authenticate(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        $client = new Google_Client([
            'client_id' => config('services.google.client_id'),
        ]);

        $payload = $client->verifyIdToken($request->id_token);

        if (! $payload || empty($payload['email']) || empty($payload['sub'])) {
            return $this->unauthorized(message: __('messages.Invalid_Google_token'));
        }

        $user = User::where('google_id', $payload['sub'])
            ->orWhere('email', $payload['email'])
            ->first();

        if (! $user) {
            $user = User::create([
                'first_name' => $payload['given_name'] ?? 'GoogleUser',
                'last_name' => $payload['family_name'] ?? null,
                'email' => $payload['email'],
                'google_id' => $payload['sub'],
                'password' => Hash::make(Str::random(32)),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->google_id) {
            $user->update(['google_id' => $payload['sub']]);
        }

        return (new UserLoginStateResolver)->resolve($user);
    }
}
