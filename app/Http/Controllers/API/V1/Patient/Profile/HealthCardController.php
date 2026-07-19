<?php

namespace App\Http\Controllers\API\V1\Patient\Profile;

use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Patient\Profile\HealthCardResource;
use App\Http\Resources\API\V1\Patient\Profile\PatientMedicalHistoryResource;
use App\Models\HealthCard;
use App\Models\User\User;
use App\Services\Patient\PatientMedicalHistoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthCardController extends Controller
{
    public function show()
    {
        $card = Auth::user()->healthCard;

        if (!$card) {
            throw new NotFoundException(__('messages.healthCard_not_Found'));
        }

        return new HealthCardResource($card);
    }

    public function view(Request $request, string $uuid, PatientMedicalHistoryService $service)
    {
        $data = $this->buildMedicalHistoryData($uuid, $service);

        if ($request->wantsJson() || $request->is('api/*')) {
            return new PatientMedicalHistoryResource($data);
        }

        return view('patient.medical-history', compact('data'));
    }

    private function buildMedicalHistoryData(string $uuid, PatientMedicalHistoryService $service): array
    {
        $card = HealthCard::where('uuid', $uuid)
            ->with([
                'user.patientProfile',
                'user.patientProfile.medicalData',
                'user.patientProfile.allergies',
                'user.patientProfile.chronicConditions'
            ])
            ->firstOrFail();

        return [
            'user'          => $card->user,
            'medications'   => $service->getMedications($card->user),
            'prescriptions' => $service->getPrescriptions($card->user),
            'reports'       => $card->user->getMedia(User::LAB_REPORTS),
        ];
    }
}
