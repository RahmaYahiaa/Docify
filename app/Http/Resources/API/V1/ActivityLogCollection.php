<?php

namespace App\Http\Resources\API\V1;

use App\Http\Resources\BasePaginationResource;
use Spatie\Activitylog\Models\Activity;


class ActivityLogCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'stats' => [
                'total_logs'    => Activity::count(),
                'today'         => Activity::whereDate('created_at', today())->count(),
                'admin_actions' => Activity::whereHas('causer', fn($q) => $q->whereHas('roles', fn($r) => $r->where('name', 'admin')))->count(),
                'system_events' => Activity::whereNull('causer_id')->count(),
            ],
            'logs' => ActivityLogResource::collection($this->collection),
        ];
    }
}
