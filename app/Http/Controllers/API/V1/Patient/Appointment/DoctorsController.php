<?php

namespace App\Http\Controllers\API\V1\Patient\Appointment;

use App\Actions\Doctor\Appointment\ListDoctorsAction;
use App\Actions\Doctor\Appointment\ShowDoctorAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Doctor\Appointment\DoctorListCollection;
use App\Http\Resources\API\V1\Doctor\Appointment\DoctorProfileResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorsController extends Controller
{
    public function index(Request $request, ListDoctorsAction $action): JsonResponse
    {
        $doctors = $action->execute(perPage: $request->input('per_page', 20));
        return $this->ok(__('messages.doctors_list_retrieved_successfully'), new DoctorListCollection($doctors));
    }

    public function show(User $doctor, ShowDoctorAction $action): JsonResponse
    {
        $doctor = $action->execute(auth()->user(), $doctor);
        return $this->ok(__('messages.doctor_profile_retrieved_successfully'),new DoctorProfileResource($doctor));
    }
}
