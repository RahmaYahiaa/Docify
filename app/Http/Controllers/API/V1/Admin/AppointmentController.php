<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Admin\Appointment\AppointmentDetailsCollection;
use App\Http\Resources\API\V1\Admin\Appointment\AppointmentDetailsResource;
use App\Models\Appointment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = QueryBuilder::for(Appointment::class)
            ->with(['patient', 'doctor', 'payment'])

            ->allowedFilters([

                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {

                        $q->whereHas('patient', function ($q2) use ($value) {
                            $q2->where('first_name', 'LIKE', "%{$value}%")
                                ->orWhere('last_name', 'LIKE', "%{$value}%");
                        })

                            ->orWhereHas('doctor', function ($q2) use ($value) {
                                $q2->where('first_name', 'LIKE', "%{$value}%")
                                    ->orWhere('last_name', 'LIKE', "%{$value}%");
                            });
                    });
                }),

                AllowedFilter::partial('status', 'status'),
                AllowedFilter::partial('type', 'type'),
            ])

            ->latest()
            ->paginate(10);

        return new AppointmentDetailsCollection($appointments);
    }

    public function show(Appointment $appointment)
    {
        return new AppointmentDetailsResource($appointment);
    }
}
