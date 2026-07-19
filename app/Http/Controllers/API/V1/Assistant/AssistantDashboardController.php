<?php

namespace App\Http\Controllers\API\V1\Assistant;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Assistant\AppointmentCollection;
use App\Http\Resources\API\V1\Assistant\AppointmentResource; // تأكدي إن ده المسار اللي كريتي فيه الريسورس
use App\Http\Resources\API\V1\Assistant\DashboardResource;
use App\Models\Appointment;
use App\Models\DoctorAvailabilitySlot;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AssistantDashboardController extends Controller
{

public function index()
{
    $user = auth()->user();

    $appointments = QueryBuilder::for(
            Appointment::forDoctor($user->doctor_id)
                ->todayAppointments()
                ->orderBy(
                    DoctorAvailabilitySlot::select('start_time')
                        ->whereColumn('doctor_availability_slots.id', 'appointments.slot_id'),
                    'asc'
                )
        )
        ->with(['patient', 'slot'])
        ->allowedFilters([
            AllowedFilter::exact('status')
        ])
        ->macroPaginate();

    return $this->ok(data: new  AppointmentCollection($appointments)  );
}
public function profile()
{
    $user = auth()->user();
    $appointments = Appointment::forDoctor($user->doctor_id)
        ->todayAppointments()
        ->with(['patient', 'slot'])
        ->get();

   return $this->ok(  data: new DashboardResource($user), );
}


}