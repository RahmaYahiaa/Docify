<?php

namespace App\Http\Resources\API\V1\Authentication;

use App\Http\Resources\API\V1\Role\RoleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorRegisterMinimalResource extends JsonResource
{
    public function toArray($request)
    {
        return [

     'id' => $this->id,
            'status' => $this->status,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
        ];
    }
}
