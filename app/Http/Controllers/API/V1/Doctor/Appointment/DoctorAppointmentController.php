<?php

namespace App\Http\Controllers\API\V1\Doctor\Appointment;

use App\Actions\Appointment\Video\GenerateVideoSessionTokenAction;
use App\Actions\Doctor\Appointment\CompleteAppointmentAction;
use App\Actions\Doctor\Appointment\MarkAppointmentNoShowAction;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorAppointmentController extends Controller
{
    public function complete(Appointment $appointment, Request $request, CompleteAppointmentAction $action): JsonResponse
    {
        $action->execute($appointment, $request->user());
        return $this->ok(__('messages.appointment_completed_successfully'));
    }

    public function noShow(Appointment $appointment, Request $request, MarkAppointmentNoShowAction $action): JsonResponse
    {
        $action->execute($appointment, $request->user());
        return $this->ok(__('messages.appointment_marked_no_show_successfully'));
    }

public function joinVideoSession(Appointment $appointment, Request $request, GenerateVideoSessionTokenAction $action): JsonResponse
{
    $session = $action->execute($appointment, $request->user());
    return $this->ok(__('messages.video_session_token_generated_successfully'), $session);
}

}
