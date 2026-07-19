<?php

namespace App\Http\Controllers\API\V1\Patient\Appointment;

use App\Actions\Appointment\Video\GenerateVideoSessionTokenAction;
use App\Actions\Patient\Appointment\BookAppointmentAction;
use App\Actions\Patient\Appointment\CancelAppointmentAction;
use App\Actions\Patient\Appointment\InitiateAppointmentAction;
use App\Actions\Patient\Appointment\ListPatientAppointmentsAction;
use App\Actions\Patient\Appointment\RescheduleAppointmentAction;
use App\Actions\Patient\Appointment\ShowAppointmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Appointment\BookAppointmentRequest;
use App\Http\Requests\API\V1\Patient\Appointment\CancelAppointmentRequest;
use App\Http\Requests\API\V1\Patient\Appointment\InitiateAppointmentRequest;
use App\Http\Requests\API\V1\Patient\Appointment\RescheduleAppointmentRequest;
use App\Http\Resources\API\V1\Patient\Appointment\AppointmentCollection;
use App\Http\Resources\API\V1\Patient\Appointment\AppointmentInitiationResource;
use App\Http\Resources\API\V1\Patient\Appointment\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request, ListPatientAppointmentsAction $action): JsonResponse
    {
        $appointments = $action->execute($request->user(), $request->input('filter', 'upcoming'));
        return $this->ok(__('messages.appointments_list_retrieved_successfully'), new AppointmentCollection($appointments));
    }

    public function show(Appointment $appointment, Request $request, ShowAppointmentAction $action): JsonResponse
    {
        $appointment = $action->execute($request->user(), $appointment);
        return $this->ok(__('messages.appointment_details_retrieved_successfully'), new AppointmentResource($appointment));
    }

    public function store(BookAppointmentRequest $request, BookAppointmentAction $action): JsonResponse
    {
        $appointment = $action->execute($request->user(), $request->validated());
        return $this->ok(__('messages.appointment_booked_successfully'), new AppointmentResource($appointment),);
    }
    public function initiate(InitiateAppointmentRequest $request, InitiateAppointmentAction $action): JsonResponse
    {
        $result = $action->execute($request->user(), $request->validated());
        return $this->ok(__('messages.appointment_initiated_successfully'), new AppointmentInitiationResource($result));
    }

    public function cancel(Appointment $appointment, CancelAppointmentRequest $request, CancelAppointmentAction $action): JsonResponse
    {
        $action->execute($appointment, $request->user(), $request->validated());
        return $this->ok(__('messages.appointment_cancelled_successfully'));
    }

    public function reschedule(Appointment $appointment, RescheduleAppointmentRequest $request, RescheduleAppointmentAction $action): JsonResponse
    {
        $updated = $action->execute($appointment, $request->user(),  newSlotId: $request->validated()['slot_id']);
        return $this->ok(__('messages.appointment_rescheduled_successfully'), new AppointmentResource($updated));
    }


public function joinVideoSession(Appointment $appointment, Request $request, GenerateVideoSessionTokenAction $action): JsonResponse
{
    $session = $action->execute($appointment, $request->user());
    return $this->ok(__('messages.video_session_token_generated_successfully'), $session);

}
}

