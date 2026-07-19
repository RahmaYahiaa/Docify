<?php

namespace App\Http\Resources\API\V1\Notification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = is_array($this->data) ? $this->data : json_decode($this->data, true);

        return [
            'id' => $this->id,
            'title' => $data['title'] ?? null,
            'body' => $data['body'] ?? null,
            'category' => $this->category,
            'extra_data' => $data['extra_data'] ?? [],
            'read_at' => $this->read_at ? $this->read_at->diffForHumans() : null, 
            'is_read' => !is_null($this->read_at),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
