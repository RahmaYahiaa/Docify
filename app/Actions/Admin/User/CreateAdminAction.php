<?php

namespace App\Actions\Admin\User;

use App\Enums\User\UserStatusEnum;
use App\Mail\UserCredentialsMail;
use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateAdminAction
{
    use AsAction;

    public function execute(array $data)
    {
        $password = Str::random(8);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password'   => Hash::make($password),
            'admin_level' => $data['admin_level'],
            'status' => UserStatusEnum::ACTIVE,
        ]);

        $user->assignRole('admin');

        Mail::to($user->email)
            ->send(new UserCredentialsMail($user, $password));

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'message' => "Admin added {$user->full_name}",
            ])
            ->log('Admin Added');
            
        return $user;
    }
}
