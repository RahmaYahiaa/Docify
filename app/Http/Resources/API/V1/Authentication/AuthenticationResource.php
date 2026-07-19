<?php

namespace App\Http\Resources\API\V1\Authentication;

use App\Http\Resources\API\V1\Role\RoleResource;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticationResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $user->id),
            'first_name' => $this->whenHas('first_name', fn () => $user->first_name),
            'last_name' => $this->whenHas('last_name', fn () => $user->last_name),
            'email' => $user->email,
            'locale' => $user->getUserLocale(),
            'roles' => $this->whenLoaded('roles', fn () => RoleResource::collection($this->roles)
            ),

            'fcm_token' => $user->fcm_token,
            'token' => $user->tokenWithBearer(),
            'refresh_token' => $user->refreshTokenWithBearer(),
            'token_expired_at' => Carbon::now()->addMinutes((int) config('sanctum.expiration')),
            'email_verified_at' => $user->email_verified_at,

        ];
    }
}
