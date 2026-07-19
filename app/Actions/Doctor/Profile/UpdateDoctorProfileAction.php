<?php

namespace App\Actions\Doctor\Profile;

use App\Models\Clinic;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;

class UpdateDoctorProfileAction
{
   public function execute(User $user, array $data)
{
    return DB::transaction(function () use ($user, $data) {


        $doctorProfile = $user->doctorProfile ?: $user->doctorProfile()->create();


        $user->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name'  => $data['last_name'] ?? $user->last_name,
            'phone'      => $data['phone'] ?? $user->phone,
        ]);


        if (!empty($data['clinic'])) {
            $clinicData = [
                'name'    => $data['clinic']['name'],
                'address' => $data['clinic']['address'],
                'city'    => $data['clinic']['city'],
              'phone'    => $data['clinic']['phone'],
            ];

            if ($doctorProfile->clinic) {

                $doctorProfile->clinic->update($clinicData);
            } else {

                $clinic = Clinic::create($clinicData);
                $doctorProfile->update(['clinic_id' => $clinic->id]);
            }
        }


        $doctorProfile->update([
            'about' => $data['about'] ?? $doctorProfile->about,
            'years_of_experience'=>$data['years_of_experience'] ?? $doctorProfile->years_of_experience,
        ]);

        return $doctorProfile->load(['user', 'clinic']);
    });
}
}
