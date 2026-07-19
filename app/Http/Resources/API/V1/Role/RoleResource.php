<?php

namespace App\Http\Resources\API\V1\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Role $role */
        $role = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $role->id),
            'name' => $this->whenHas('name', fn () => $role->name),
            // 'user_have_role_count' => $this->whenCounted('users'),
            // 'created_at' => $this->whenHas('created_at', fn () => [
            //     'value' => $role->created_at,
            //     'human' => $role->created_at ? $role->created_at->diffForHumans() : null,
            // ]),
        ];

    }
}
