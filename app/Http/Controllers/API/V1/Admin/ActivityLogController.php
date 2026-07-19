<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\ActivityLogCollection;
use Spatie\Activitylog\Models\Activity;
use Spatie\QueryBuilder\QueryBuilder;

class ActivityLogController extends Controller
{
    public function index()
    {
        $logs = QueryBuilder::for(Activity::class)
        ->with(['causer', 'subject']) 
        ->allowedFilters([
            'description',
            'subject_type',
        ])
        ->latest()
        ->paginate(15);

        return new ActivityLogCollection($logs);
    }
}
