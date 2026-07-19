<?php

namespace App\Http\Controllers\API\V1\Doctor\Prescription;

use App\Actions\Prescription\CreatePrescriptionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Prescription\StorePrescriptionRequest;
use App\Http\Resources\API\V1\Prescription\PrescriptionCollection;
use App\Http\Resources\API\V1\Prescription\PrescriptionResource;
use App\Models\PatientProfile;
use App\Models\Prescription;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PrescriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $prescriptions = QueryBuilder::for(Prescription::class)
            ->where('doctor_id', auth()->id())
            ->with(['patient:id,first_name,last_name'])
            ->withCount('items')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::callback('name', function ($query, $value) {
                    $query->whereHas('patient', function ($q) use ($value) {
                        $q->where('first_name', 'like', "%{$value}%")
                            ->orWhere('last_name', 'like', "%{$value}%")
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$value}%"]);
                    });
                }),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id', 'created_at'])
            ->macroPaginate();

        return (new PrescriptionCollection($prescriptions))->response();
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient', 'items.medication:id,name'])->loadCount('items');

        return new PrescriptionResource($prescription);
    }

    public function store(
    StorePrescriptionRequest $request,
    CreatePrescriptionAction $createPrescription
) {
    try {
        $createPrescription->execute(
            $request->patient_id,
            $request->notes,
            $request->diagnosis,
            $request->medications
        );

        return $this->ok(
            message: __('messages.Prescription_generated_successfully')
        );

    } catch (\Exception $e) {
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());

        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}


    public function patientPrescriptions(User $patient): JsonResponse
{
    $doctorId = auth()->id();

   $prescriptions = Prescription::query()
        ->where('patient_id', $patient->id)
        ->where('doctor_id', $doctorId)
        ->latest()
         ->withCount('items')
         ->macroPaginate();

    return $this->ok(
        data: PrescriptionResource::collection($prescriptions)
    );
}
}