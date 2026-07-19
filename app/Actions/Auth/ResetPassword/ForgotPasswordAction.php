<?php

namespace App\Actions\Auth\ResetPassword;

use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class ForgotPasswordAction
{
    public function execute(string $email)
    {
        $otp = rand(100000, 999999);
        $ttl = 300;
        Redis::setex("password_reset_{$email}", $ttl, $otp);
        Mail::to($email)
            ->send(
                new OtpMail($otp)
            );

        return true;
    }
}
