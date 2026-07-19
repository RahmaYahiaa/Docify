<?php

namespace App\Http\Controllers\API\V1\Doctor\Prescription;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\RequestResource\PrescriptionRequestResource;
use App\Models\PatientProfile;
use App\Models\PrescriptionRequest;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrescriptionRequestController extends Controller
{
   public function index(User $patient): JsonResponse
    {
        $requests = PrescriptionRequest::query()
            ->where('patient_id', $patient->id)
            ->where('doctor_id', auth()->id())
            ->latest()
            ->macroPaginate();
        return $this->ok(
            data: PrescriptionRequestResource::collection($requests)->response()->getData(true)
        );
    }
}
