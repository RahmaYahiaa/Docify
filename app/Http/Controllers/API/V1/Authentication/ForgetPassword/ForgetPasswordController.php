<?php

namespace App\Http\Controllers\API\V1\Authentication\ForgetPassword;

use App\Actions\Auth\ResetPassword\ForgotPasswordAction;
use App\Actions\Auth\ResetPassword\ResetPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;

class ForgetPasswordController extends Controller
{
    public function sendOtp(ForgotPasswordRequest $request, ForgotPasswordAction $action)
    {
        $action->execute($request->email);

        // return response()->json(['message' => 'OTP sent to your email.']);
        return $this->ok(__('messages.OTP_sent_to_your_email'));
    }

    public function resetPassword(ResetPasswordRequest $request, ResetPasswordAction $action)
    {
        $success = $action->execute($request->email, $request->otp, $request->password);
        if (! $success) {

            return $this->unprocessable(__('messages.invalid_otp_or_expired'));
        }

        return $this->ok(__('messages.password_reset_successfully'));

    }
}
