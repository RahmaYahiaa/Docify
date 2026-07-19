<?php

namespace App\Actions\Auth;

use App\Models\User\User;
use Google_Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Octane\Contracts\Client;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginAction
{
    /**
     * Execute Google login using ID Token or Access Token
     *
     * @param  array  $data  Optional fallback data
     */
    public function execute(?string $idToken = null, ?string $accessToken = null, array $data = []): array
    {
        try {
            $googleId = null;
            $email = null;
            $name = null;

            // 1️⃣ If ID Token is provided → validate with Google Client
            if ($idToken) {
                $client = new Google_Client(['client_id' => config('services.google.client_id')]);
                $payload = $client->verifyIdToken($idToken);

                if (! $payload) {

                    return [
                        'status' => 'error',
                        'message' => 'Invalid ID Token',
                    ];
                }

                $googleId = $payload['sub'];
                $email = $payload['email'];
                $name = $payload['name'] ?? ($data['name'] ?? 'New User');

            } elseif ($accessToken) {
                $googleUser = Socialite::driver('google')
                    ->stateless()
                    ->userFromToken($accessToken);

                $googleId = $googleUser->id;
                $email = $googleUser->email;
                $name = $googleUser->name ?? ($data['name'] ?? 'New User');
            }
            // 3️⃣ No token provided
            else {
                return [
                    'status' => 'error',
                    'message' => 'No token provided',
                ];
            }

            // 4️⃣ Find or create user
            $user = User::where('google_id', $googleId)
                ->orWhere('email', $email)
                ->first();

            if (! $user) {
                $user = User::create([
                    'first_name' => $name,
                    'email' => $email,
                    'google_id' => $googleId,
                    'password' => Hash::make(Str::random(32)),
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);

                return [
                    'status' => 'choose_role',
                    'message' => 'Please choose your role.',
                    'user_id' => $user->id,
                ];
            }

            // 5️⃣ Update google_id if missing
            if (! $user->google_id) {
                $user->update(['google_id' => $googleId]);
            }

            $user->load('roles');

            // 6️⃣ Check roles
            if ($user->roles->isEmpty()) {
                return [
                    'status' => 'choose_role',
                    'message' => 'Please choose your role.',
                    'user_id' => $user->id,
                ];
            }

            // 7️⃣ Patient
            if ($user->hasRole('patient')) {
                return [
                    'status' => 'patient',
                    'message' => 'Login successful.',
                    'access_token' => $user->createToken('api')->plainTextToken,
                    'user' => $user,
                ];
            }

            // 8️⃣ Doctor
            if ($user->hasRole('doctor')) {
                if ($user->status === 'pending') {
                    return [
                        'status' => 'doctor_pending',
                        'message' => 'Upload your certificate.',
                        'user_id' => $user->id,
                        'user' => [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'roles' => $user->roles->pluck('name'),
                        ],
                    ];
                } elseif ($user->status === 'active') {
                    return [
                        'status' => 'doctor',
                        'message' => 'Login successful.',
                        'access_token' => $user->createToken('api')->plainTextToken,
                        'user' => $user,
                    ];
                }
            }

            return [
                'status' => 'error',
                'message' => 'Something went wrong',
            ];

        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to verify Google token: '.$e->getMessage(),
            ];
        }
    }
}
