<?php

namespace App\Http\Controllers\API\V1\Patient\Prescription;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Prescription\PrescriptionCollection;
use App\Http\Resources\API\V1\Prescription\PrescriptionResource;
use App\Models\Prescription;
use App\Models\PrescriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PrescriptionController extends Controller
{

    public function index(): JsonResponse
    {
        $prescriptions = QueryBuilder::for(Prescription::class)
            ->where('patient_id', auth()->id())
            ->with(['doctor:id,first_name,last_name', 'doctor.doctorProfile.specialization',  ])
            ->withCount('items')
            ->allowedFilters([
                AllowedFilter::partial('doctor_name', 'doctor.first_name') ,
            ])
            ->defaultSort('-created_at')
            ->paginate();
      return $this->ok(data: new PrescriptionCollection($prescriptions));

    }

    public function show(Prescription $prescription): JsonResponse
    {
        $prescription->load(['doctor:id,first_name,last_name', 'items.medication']);
       return $this->ok(data: PrescriptionResource::make($prescription));

    }
public function renew(Request $request, Prescription $prescription)
{
    // if ($prescription->patient_id !== auth()->id()) {
    //     return response()->json(['message' => 'Unauthorized Access.'], 403);
    // }
    $prescriptionRequest = PrescriptionRequest::create([
        'prescription_id' => $prescription->id,
        'patient_id'      => auth()->id(),
        'doctor_id'       => $prescription->doctor_id,
        'note'            => $request->note,
        'status'          => 'pending'
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Request sent successfully.',
        'data'    => $prescriptionRequest
    ], 201);
}}