<?php

use App\Http\Controllers\API\V1\Admin\ActivityLogController;
use App\Http\Controllers\API\V1\Admin\AdminDashboardController;
use App\Http\Controllers\API\V1\Admin\AppointmentController;
use App\Http\Controllers\API\V1\Admin\DoctorController;
use App\Http\Controllers\API\V1\Admin\PaymentController;
use App\Http\Controllers\API\V1\Admin\SystemSettingsController;
use App\Http\Controllers\API\V1\Admin\UserController;
use App\Http\Controllers\API\V1\Admin\UserManagementController;
use App\Http\Controllers\API\V1\SpecializationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;



Route::get('dashboard', [AdminDashboardController::class, 'index']);

Route::get('doctors', [DoctorController::class, 'index']);
Route::get('doctors/pending', [DoctorController::class, 'pending']);

Route::post('doctors/{doctor}/approve', [DoctorController::class, 'approve']);
Route::post('doctors/{doctor}/reject', [DoctorController::class, 'reject']);
Route::get('doctors', [DoctorController::class, 'index']);
Route::get('doctors/{doctor}', [DoctorController::class, 'show']);

Route::get('/logs', [ActivityLogController::class, 'index']);
Route::get('user-reasons', [UserManagementController::class, 'reason']);


Route::prefix('specializations')->group(function () {
    Route::get('/', [SpecializationController::class, 'Specialty']);
    Route::post('/', [SpecializationController::class, 'store']);
    Route::put('/{specialization}', [SpecializationController::class, 'update']);
    Route::patch('/{specialization}/disable', [SpecializationController::class, 'disable']);
    Route::patch('/{specialization}/activate', [SpecializationController::class, 'activate']);
    Route::get('/dropdown', [SpecializationController::class, 'dropdown']);
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserManagementController::class, 'index']);
    Route::get('/{user}', [UserManagementController::class, 'show']);
    Route::put('/{user}', [UserController::class, 'update']);
    Route::post('/create-doctor', [UserManagementController::class, 'CreateDoctor']);
    Route::post('/create-admin', [UserManagementController::class, 'CreateAdmin']);
    Route::post('{user}/suspend', [UserManagementController::class, 'suspend']);
    Route::post('{user}/activate', [UserManagementController::class, 'activate']);
});

Route::prefix('settings')->group(function () {
    Route::get('/', [SystemSettingsController::class, 'index']);
    Route::put('/', [SystemSettingsController::class, 'update']);
});

Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentController::class, 'index']);
    Route::get('/{appointment}', [AppointmentController::class, 'show']);
});

Route::get('/payments', [PaymentController::class, 'payment']);
Route::get('/refunds', [PaymentController::class, 'refund']);


Route::get('/seed-settings', function () {
    
    Artisan::call('db:seed', [
        '--class' => 'SettingSeeder',
        '--force' => true,
    ]);

    return response()->json([
        'message' => 'Settings seeded successfully',
        'output'  => Artisan::output(),
    ]);
});
