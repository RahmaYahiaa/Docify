<?php

namespace App\Http\Resources\API\V1\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'name' => $this->full_name,
                'specialty' => $this->doctorProfile?->specialization?->name,
            ],
            'contact' => [
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            'role' => $this->roles->first()?->name,
            'status' => $this->status,
            'last_login' => $this->last_login_at
                ? $this->last_login_at->format('M d, Y \a\t h:i A')
                : "Never",

            'created_at' => $this->created_at->format('M d, Y \a\t h:i A'),
            'created_by' => $this->creator ? $this->creator->full_name : 'System',
        ];
    }
}
