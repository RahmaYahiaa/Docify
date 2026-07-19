<?php

use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Authentication\ForgetPassword\ForgetPasswordController;
use App\Http\Controllers\API\V1\Authentication\GoogleLoginController;
use App\Http\Controllers\API\V1\Authentication\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/send-otp', [ForgetPasswordController::class, 'sendOtp']);
Route::post('/reset-password', [ForgetPasswordController::class, 'resetPassword']);
Route::post('login', [LoginController::class, 'login']);
Route::post('/auth/google', [GoogleLoginController::class, 'login'])->withoutMiddleware('auth:sanctum');
Route::post('users/{user}/assign-roles', [UserController::class, 'assignRoles'])->withoutMiddleware('auth:sanctum');
