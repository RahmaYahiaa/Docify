<?php

namespace App\Actions\Review;

use App\Enums\Appointment\AppointmentStatusEnum;
use App\Exceptions\ForbiddenException;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Review;
use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreReviewAction
{
    use AsAction;

    public function execute(array $data, User $patient)
    {
        if (!$patient->isPatient()) {
            throw new ForbiddenException(
                'Only patients can submit reviews.'
            );
        }

        $doctorProfile = DoctorProfile::where(
            'user_id',
            $data['doctor_id']
        )->firstOrFail();

        $appointment = Appointment::query()
            ->where('id', $data['appointment_id'])
            ->where('doctor_id', $data['doctor_id'])
            ->where('patient_id', $patient->id)
            ->where('status', AppointmentStatusEnum::COMPLETED)
            ->first();

        if (!$appointment) {
            throw new ForbiddenException(
                'You can only review a completed appointment.'
            );
        }

        $alreadyReviewed = Review::where(
            'appointment_id',
            $appointment->id
        )->exists();

        if ($alreadyReviewed) {
            throw new ForbiddenException(
                'This appointment has already been reviewed.'
            );
        }

        return Review::create([
            'appointment_id' => $appointment->id,
            'doctor_profile_id' => $doctorProfile->id,
            'user_id' => $patient->id,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);
    }
}
