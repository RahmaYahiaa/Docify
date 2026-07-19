<?php

use App\Http\Controllers\API\V1\Patient\Profile\HealthCardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('medical-history/{uuid}', [HealthCardController::class, 'view']);
