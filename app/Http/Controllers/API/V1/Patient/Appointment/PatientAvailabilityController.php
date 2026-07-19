<?php

namespace App\Http\Controllers\API\V1\Patient\Appointment;

use App\Actions\Patient\Availability\GetDoctorAvailableSlotsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Availability\GetDoctorSlotsRequest;
use App\Http\Resources\API\V1\Patient\Appointment\DoctorAvailabilitySlotCollection;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class PatientAvailabilityController extends Controller
{
    public function index(User $doctor, GetDoctorSlotsRequest $request, GetDoctorAvailableSlotsAction $action): JsonResponse
    {
        $filters = $request->validatedData();
        $slots = $action->execute(doctor: $doctor, type: $filters['type'], date: $filters['date']);
        return $this->ok(__('messages.doctor_availability_slots_retrieved_successfully'), new DoctorAvailabilitySlotCollection($slots));
    }
}
