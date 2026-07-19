<?php

use App\Http\Controllers\API\V1\Assistant\AssistantDashboardController;
use App\Http\Controllers\API\V1\Assistant\FinancialController;
use App\Http\Controllers\API\V1\Assistant\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('assistant/appointments', [AssistantDashboardController::class, 'index']);
Route::get('assistant/dashboard', [AssistantDashboardController::class, 'profile']);
Route::get('profile/info', [ProfileController::class, 'info']);
// Route::patch('appointments/{appointment}/update-status', [AppointmentController::class, 'updateStatus']);
// Route::get('appointments/statuses', [AppointmentController::class, 'statuses']);


//summary

Route::get('financial/today-cash-summary', [FinancialController::class, 'todayCashSummary']);
    Route::patch('appointments/{appointment}/mark-as-paid', [FinancialController::class, 'markAsPaid']);
 Route::get('/financial/all-day-summary', [FinancialController::class, 'summary']);