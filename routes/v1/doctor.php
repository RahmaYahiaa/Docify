<?php

use App\Http\Controllers\API\V1\Authentication\Doctor\DoctorRegisterController;
use App\Http\Controllers\API\V1\Doctor\Appointment\AppointmentController;
use App\Http\Controllers\API\V1\Doctor\Appointment\DoctorAppointmentController;
use App\Http\Controllers\API\V1\Doctor\Appointment\DoctorAvailabilityController;
use App\Http\Controllers\API\V1\Doctor\DoctorProfileController;
use App\Http\Controllers\API\V1\Doctor\DoctorReviewController;
use App\Http\Controllers\API\V1\Doctor\Patient\LabReportController;
use App\Http\Controllers\API\V1\Doctor\Patient\PatientProfileController;
use App\Http\Controllers\API\V1\Doctor\Prescription\PrescriptionController;
use App\Http\Controllers\API\V1\Doctor\Prescription\PrescriptionRequestController;
use App\Http\Controllers\API\V1\Doctor\User\AssistantController;
use App\Http\Controllers\API\V1\Payment\PaymentMethodController;
use App\Http\Controllers\API\V1\Payment\StripeController;
use App\Http\Controllers\API\V1\Payment\WalletController;
use App\Http\Controllers\API\V1\SpecializationController;
use Illuminate\Support\Facades\Route;


Route::post('register/doctor', [DoctorRegisterController::class, 'register'])->withoutMiddleware('auth:sanctum');
Route::post('/doctor/{user}/upload-certificate', [DoctorRegisterController::class, 'upload'])->withoutMiddleware('auth:sanctum');
Route::get('/specializations', [SpecializationController::class, 'dropdown'])->withoutMiddleware('auth:sanctum');


// Availability
Route::prefix('availability')->group(function () {
    Route::get('/', [DoctorAvailabilityController::class, 'index'])
        ->name('doctor.availability.index');

    Route::post('/', [DoctorAvailabilityController::class, 'store'])
        ->name('doctor.availability.store');

    Route::patch('/block', [DoctorAvailabilityController::class, 'block'])
        ->name('doctor.availability.block');

    Route::patch('/unblock', [DoctorAvailabilityController::class, 'unblock'])
        ->name('doctor.availability.unblock');

    Route::delete('/{slot}', [DoctorAvailabilityController::class, 'destroy'])
        ->name('doctor.availability.destroy');
});


Route::prefix('appointments')->group(function () {

    Route::get('today', [AppointmentController::class, 'schedule']);
    // Calendar
    //Route::get('calendar', [AppointmentController::class, 'calendar']);
    Route::get('/', [AppointmentController::class, 'index']);
    Route::patch('/{appointment}/complete', [DoctorAppointmentController::class, 'complete'])
        ->name('doctor.appointments.complete');
    Route::patch('/{appointment}/no-show', [DoctorAppointmentController::class, 'noShow'])
        ->name('doctor.appointments.no-show');

    Route::get('/{appointment}/video-session', [DoctorAppointmentController::class, 'joinVideoSession'])
        ->name('doctor.appointments.video-session');
});

Route::get('appointment/calendar', [AppointmentController::class, 'calendar'])
    ->name('doctor.patient.appointments');

Route::name('doctor')->group(function () {
    Route::apiResource('prescriptions', PrescriptionController::class);
});
Route::get('/profile', [DoctorProfileController::class, 'show']);

//patient reports
Route::get(
    '/patients/{patientId}/lab-reports',
    [LabReportController::class, 'indexByPatient']
);
//patients
Route::apiResource('patients', PatientProfileController::class)->only(['index', 'show']);
Route::get('patients/{patient}/appointments', [AppointmentController::class, 'patientAppointments'])
    ->name('doctor.patients.appointments');
//prescription
Route::get('patients/{patient}/prescriptions', [PrescriptionController::class, 'patientPrescriptions']);

//prescription-requests
Route::get('patients/{patient}/prescription-requests', [PrescriptionRequestController::class, 'index']);
//doctor_profile
Route::get('/profile/data', [DoctorProfileController::class, 'data']);
Route::put('/doctor/profile', [DoctorProfileController::class, 'update']);
//updateFee
Route::put('doctor/profile/fees', [DoctorProfileController::class, 'updateFee']);
Route::get('doctor/profile/fees', [DoctorProfileController::class, 'showFee']);
Route::post('/doctor/profile/languages', [DoctorProfileController::class, 'addLanguages']);
Route::get('/doctor/profile/languages', [DoctorProfileController::class, 'getLanguages']);
Route::post('/doctor/profile/upload-photo', [DoctorProfileController::class, 'uploadPhoto']);
//wallet
Route::apiResource('payment-methods',  PaymentMethodController::class)
    ->only(['index', 'store', 'destroy']);

//wallet/withdraw
Route::post('/withdraw', [WalletController::class, 'withdraw']);
Route::get('/transactions', [WalletController::class, 'transactionsHistory']);


//assistant
Route::post('assistants', [AssistantController::class, 'store']);



//
Route::post('/doctor/stripe/onboarding', [StripeController::class, 'getOnboardingLink']);
Route::get('/stripe/return', [StripeController::class, 'handleReturn'])->name('stripe.return')->withoutMiddleware('auth:sanctum');
Route::get('/stripe/refresh', [StripeController::class, 'handleRefresh'])->name('stripe.refresh')->withoutMiddleware('auth:sanctum');

Route::get('/doctor/stripe/bank-info', [PaymentMethodController::class, 'bankInfo']);


// review
Route::get('reviews', [DoctorReviewController::class, 'index']);





Route::middleware('auth:sanctum')->group(function () {

    Route::post('stripe/connect', [StripeController::class, 'createStripeAccount']);

    Route::post('stripe/add-bank', [StripeController::class, 'addBankAccount']);
});
