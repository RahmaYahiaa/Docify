<?php

namespace App\Http\Resources\API\V1\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $user->id),
            'name' => $this->whenHas('name', fn () => $user->name),
            'email' => $this->whenHas('email', fn () => $user->email),
            'roles' => $this->getRoleNames(),
            // 'roles' => $this->whenHas('roles', fn () => $user->getRoleNames()->all()),
            'created_at' => $this->whenHas('created_at', fn () => $user->created_at),
            'status' => $this->whenHas('status', fn () => $user->status),
        ];
    }
}
