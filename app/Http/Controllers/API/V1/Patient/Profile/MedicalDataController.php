<?php

namespace App\Http\Controllers\API\V1\Patient\Profile;

use App\Models\Allergy;
use Illuminate\Http\Request;
use App\Models\ChronicCondition;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Actions\Patient\Profile\GetMedicalDataAction;
use App\Actions\Patient\Profile\UpdateMedicalDataAction;
use App\Http\Resources\API\V1\Patient\Profile\MedicalDataResource;
use App\Http\Requests\API\V1\Patient\Profile\UpdateMedicalDataRequest;

class MedicalDataController extends Controller
{
    public function show(Request $request, GetMedicalDataAction $action)
    {
        $patient = $action->execute($request->user());

        return $this->ok(__('messages.medical_data_retrieved_successfully'),new MedicalDataResource($patient));
    }

    public function update(UpdateMedicalDataRequest $request, UpdateMedicalDataAction $action)
    {
        $medicalData = $action->execute($request->user(), $request->validated());

        return $this->ok(__('messages.medical_data_updated_successfully'), new MedicalDataResource($medicalData));
    }

    public function chronicConditionsList(): JsonResponse
    {
        $chronicConditions = ChronicCondition::select('id', 'name')->orderBy('id', 'asc')->get();

        return response()->json(['data' => $chronicConditions]);
    }

    public function allergiesList(): JsonResponse
    {
        $allergies = Allergy::select('id', 'name')->orderBy('id', 'asc')->get();

        return response()->json(['data' => $allergies]);
    }
}
