<?php

namespace App\Actions\Admin\User;

use App\Mail\UserCredentialsMail;
use App\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateDoctorAction
{
    use AsAction;

    public function execute(array $data)
    {
        return DB::transaction(function () use ($data) {

            $password = Str::random(8);

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'phone'      => $data['phone'],
                'password'   => Hash::make($password),
                'status'     => 'active',
            ]);

            $user->assignRole('doctor');

            $user->doctorProfile()->create([
                'specialization_id' => $data['specialization_id'],
            ]);

            if (!empty($data['certificate'])) {
                $user->addMedia($data['certificate'])
                    ->toMediaCollection(User::MEDICAL_CERTIFICATE);
            }

            Mail::to($user->email)
                ->send(new UserCredentialsMail($user, $password));

            activity()
                ->performedOn($user)
                ->causedBy(auth()->user())
                ->withProperties([
                    'message' => "Admin added {$user->full_name}",
                ])
                ->log('Doctor Added');
                
            return $user->load(['roles', 'doctorProfile.specialization']);
        });
    }
}
