<?php

namespace App\Http\Resources\API\V1\Admin;

use App\Http\Resources\BasePaginationResource;
use App\Models\User\User;

class UserDetailsCollection extends BasePaginationResource
{

    public $collects = UserDetailsResource::class;

    /**
     * Transform the resource collection into an array.
     */
    public function toArray($request): array
    {
        return [
            'stats' => [
                'total'     => User::count(),
                'active'    => User::active()->count(),
                'pending'   => User::pending()->count(),
                'suspended' => User::suspended()->count(),

                'doctors_count' => User::role('doctor')->count(),
                'patients_count' => User::role('patient')->count(),
                'assistants_count' => User::role('assistant')->count(),
                'admins_count' => User::role('admin')->count(),
            ],

            'data' => $this->collection,
        ];
    }
}
