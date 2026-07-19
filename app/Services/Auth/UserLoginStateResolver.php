<?php

namespace App\Services\Auth;

use App\Http\Resources\API\V1\User\UserResource;
use App\Models\User\User;

class UserLoginStateResolver
{
    public function resolve(User $user): array
    {
        $user->load('roles');

        $roles = $user->roles->pluck('name');

        if ($roles->isEmpty()) {
            return [
                'status' => 'choose_role',
                'message' => __('messages.Please_choose_your_role'),
                'data' => UserResource::make($user),
            ];
        }

        if ($roles->contains('doctor') && $user->status === 'pending') {
            return [
                'message' => __('messages.Upload_your_certificate'),
                'data' => UserResource::make($user),
            ];
        }

        $token = $user->createToken('google-auth')->plainTextToken;

        return [
            'message' => __('auth.signed'),
            'token' => $token,
            'data' => UserResource::make($user),
        ];
    }
}
