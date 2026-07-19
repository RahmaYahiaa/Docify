<?php

namespace App\Services\Patient;

use App\Models\Prescription;
use App\Models\PrescriptionItem;

class PatientMedicalHistoryService
{
    public function getMedications($user)
    {
        return PrescriptionItem::with('medication')
            ->whereNull('prescription_id')
            ->where('patient_id', $user->id)
            ->get();
    }

    public function getPrescriptions($user)
    {
        return Prescription::where('patient_id', $user->id)
            ->with([
                'doctor.doctorProfile.specialization',
                'patient',
                'items.medication'
            ])
            ->latest()
            ->get();
    }
}
