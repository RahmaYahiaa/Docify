<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Enums\User\UserStatusEnum;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use App\Http\Resources\API\V1\Authentication\DoctorRegisterMinimalResource;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::with('roles')
            ->whereEmail($request->email)
            ->firstOrFail();
        if ($user->roles->contains('name', 'doctor')) {

            if (! $user->hasMedia(User::MEDICAL_CERTIFICATE)) {
                return $this->forbidden(__('messages.Upload_your_certificate'));
            }
            if ($user->status !== UserStatusEnum::ACTIVE) {
                return $this->ok(
                    __('messages.register_doctor'),
                    DoctorRegisterMinimalResource::make($user)
                );
            }
        }
        $token = $user->createToken('auth')->plainTextToken;

        $user->update([
            'last_login_at' => now(),
        ]);

        return $this->ok(
            __('auth.signed'),
            AuthenticationResource::make(
                $user->setAttribute('token', $token)
            )
        );
    }
}
