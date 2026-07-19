<?php

namespace App\Http\Resources\API\V1\Doctor\Appointment;

use App\Enums\Role\UserRoleEnum;
use App\Enums\User\UserStatusEnum;
use App\Http\Resources\BasePaginationResource;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class DoctorDetailsCollection extends BasePaginationResource
{
    public $collects = DoctorDetailsResource::class;

    public function toArray($request): array
    {
        $stats = User::role(UserRoleEnum::DOCTOR->value)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'stats' => [
                'active' => $stats[UserStatusEnum::ACTIVE->value] ?? 0,
                'pending' => $stats[UserStatusEnum::PENDING->value] ?? 0,
                'rejected' => $stats[UserStatusEnum::REJECTED->value] ?? 0,
            ],

            'data' => $this->collection,
        ];
    }
}
