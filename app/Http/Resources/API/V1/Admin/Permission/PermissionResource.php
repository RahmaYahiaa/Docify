<?php

namespace App\Http\Resources\API\V1\Admin\Permission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {       /** @var Permission $permission */
        $permission = $this->resource;

        return [
            'id' => $this->id,
            'name' => $this->whenHas('name', fn () => $permission->name),
            'user_have_permission_count' => $this->whenCounted('users'),
            'created_at' => $this->whenHas('created_at', fn () => [
                'value' => $this->whenHas('created_at', fn () => $permission->created_at),
                'human' => $this->when($permission->created_at, fn () => $permission->created_at->diffForHumans()),
            ]),
        ];
    }
}
