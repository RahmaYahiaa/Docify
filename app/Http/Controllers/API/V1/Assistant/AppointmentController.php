<?php

namespace App\Http\Controllers\API\V1\Assistant;

use App\Actions\Doctor\Appointment\UpdateAppointmentStatusAction;
use App\Enums\Appointment\AppointmentStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Assistant\UpdateAppointmentStatusRequest;
use App\Models\Appointment;


class AppointmentController extends Controller
{
public function updateStatus(UpdateAppointmentStatusRequest $request,Appointment $appointment,UpdateAppointmentStatusAction $action)
{
   $action->execute($appointment, $request->status);
    return $this->ok(  message: __('messages.appointment_status_updated'), );
}
public function statuses()
{
    $statuses = AppointmentStatusEnum::values();
    return $this->ok( data: $statuses );
}
}
