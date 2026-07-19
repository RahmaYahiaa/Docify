<?php

namespace App\Http\Controllers\API\V1\Doctor\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Patient\PatientResource;
use App\Models\Appointment;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PatientProfileController extends Controller
{

public function index(Request $request)
{
    $doctorId = auth()->id();

  $patients = PatientProfile::whereHas('user.appointmentsAsPatient', function ($q) use ($doctorId) {
            $q->where('doctor_id', $doctorId);
        })
        ->with([
            'user',
            'user.appointmentsAsPatient.slot'
        ])
        ->when($request->filter['name'] ?? null, function ($query, $name) {
            $query->whereHas('user', function ($q) use ($name) {
                $q->searchName($name);
            });
        })
        ->get();

    return PatientResource::collection($patients)->map(function ($resource) {
        $resource->except = ['Contact_Information'];
        return $resource;
    });
}
public function show($id)
{
    $patient = PatientProfile::where('user_id', $id)
        ->with(['user', 'chronicConditions', 'allergies', 'appointments.slot'])
        ->withCount('appointments')
        ->firstOrFail();
    return new PatientResource($patient);
}

public function overview($id)
{
    $patient = PatientProfile::where('user_id', $id)
        ->with(['user', 'chronicConditions', 'allergies'])
        ->withCount('appointments')
        ->firstOrFail();
    return new PatientResource($patient);
}

}
