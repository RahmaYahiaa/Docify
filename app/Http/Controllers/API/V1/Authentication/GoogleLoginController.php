<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Enums\User\UserStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use App\Models\User\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleLoginController extends Controller
{
 public function login(Request $request)
{
    $request->validate([
        'id_token' => 'required|string',
        'chosen_role_id' => 'required|exists:roles,id',
    ]);

    $client = new GoogleClient([
        'client_id' => config('services.google.client_id')
    ]);

    $payload = $client->verifyIdToken($request->id_token);

    if (!$payload) {
        return $this->unauthorized(__('auth.invalid_google_token'));
    }

    $email = $payload['email'];
    $googleId = $payload['sub'];

    $user = User::where('google_id', $googleId)
        ->orWhere('email', $email)
        ->first();


    if (!$user) {

        $names = explode(' ', $payload['name'] ?? 'Google User', 2);

        $user = User::create([
            'email' => $email,
            'first_name' => $names[0],
            'last_name' => $names[1] ?? '',
            'google_id' => $googleId,
            'email_verified_at' => now(),
            'status' => UserStatusEnum::PENDING,
            'password' => Hash::make(Str::random(16)),
        ]);

        $user->roles()->attach($request->chosen_role_id);

        if ($user->hasRole('patient')) {
            $user->update(['status' => UserStatusEnum::ACTIVE]);
        }
    }

    $user->load('roles');


    if (!$user->roles->contains('id', $request->chosen_role_id)) {
        return $this->forbidden("You chose a wrong role");
    }


    if ($user->hasRole('doctor')) {

        if (!$user->hasMedia(User::MEDICAL_CERTIFICATE)) {
            return $this->unprocessable(
                __('messages.Upload_your_certificate'),
                ['user_id' => $user->id]
            );
        }

        if ($user->status === UserStatusEnum::PENDING) {
            return $this->unprocessable(
                __('messages.register_doctor_waiting_admin'),  ['user_id' => $user->id]
            );
        }
    }


    $token = $user->createToken('google-auth')->plainTextToken;
    $user->token = $token;

    return $this->ok(
        __('auth.signed'),
        AuthenticationResource::make($user)
    );
}}