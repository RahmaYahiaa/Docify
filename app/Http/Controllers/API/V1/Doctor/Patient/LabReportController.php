<?php

namespace App\Http\Controllers\API\V1\Doctor\Patient;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Patient\Reports\LabReportResource;
use App\Models\User\User;
use Illuminate\Http\Request;

class LabReportController extends Controller
{
 public function indexByPatient(Request $request, int $patientId)
    {
        $patient = User::findOrFail($patientId);

        return $this->ok(LabReportResource::collection(  $patient->getMedia(User::LAB_REPORTS) ));
    }
}
