<?php

namespace App\Actions\Auth\ResetPassword;

use App\Models\User\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;

class ResetPasswordAction
{
    public function execute(string $email, string $otp, string $password)
    {
        $storedOtp = Redis::get("password_reset_{$email}");
        if (! $storedOtp || $storedOtp != $otp) {
            return false;
        }
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($password);
        $user->save();
        Redis::del("password_reset_{$email}");

        return true;
    }
}
