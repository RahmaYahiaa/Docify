<?php

namespace App\Http\Controllers\API\V1\Doctor\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorCalendarRequest;
use App\Http\Resources\API\V1\Doctor\Appointment\AppointmentResource;
use App\Http\Resources\API\V1\Doctor\Schedule\ScheduleCollection;
use App\Http\Resources\API\V1\Doctor\Schedule\ScheduleResource;
use App\Models\Appointment;
use App\Models\PatientProfile;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AppointmentController extends Controller
{


public function schedule(): JsonResponse
{
    $today = Carbon::today()->toDateString();

    $appointments = QueryBuilder::for(
            auth()->user()->appointmentsAsDoctor()
        )

        ->with(['patient', 'slot'])
        ->allowedFilters([
            AllowedFilter::exact('status'),
        ])
        ->TodayAppointments()
        ->defaultSort('-id')
        ->paginate();

    return $this->ok(data: new ScheduleCollection($appointments));
}
// public function index(): JsonResponse
// {
//     $user = auth()->user();

//     $appointments = Appointment::with([

//             'patient.patientProfile',
//       'slot:id,date,start_time,duration_minutes,type'
//     ])
//     ->where('doctor_id', $user->id)
//     ->get();
//     return $this->ok(data: AppointmentResource::collection($appointments));
// }

public function patientAppointments(PatientProfile $patient): JsonResponse
{
    $doctorId = auth()->id();

    $appointments = Appointment::query()
        ->where('patient_id', $patient->user_id)
        ->where('doctor_id', $doctorId)           
        ->latest()
        ->get();

    return $this->ok(
        data: AppointmentResource::collection($appointments)
    );
}



public function calendar(DoctorCalendarRequest $request): JsonResponse
{
    $doctorId = auth()->id();

    $date = $request->input('filter.date');
    $type = $request->input('filter.type');

    $appointments = QueryBuilder::for(Appointment::class)
        ->where('doctor_id', $doctorId)
        ->where('type', $type)
        ->whereHas('slot', function ($q) use ($date) {
            $q->whereDate('date', $date);
        })
        ->with(['patient', 'slot'])
        ->defaultSort('-id')
        ->get();

    return $this->ok(data: [
        'appointments' => ScheduleResource::collection($appointments),
    ]);
}
}