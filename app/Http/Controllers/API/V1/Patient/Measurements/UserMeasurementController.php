<?php

namespace App\Http\Controllers\API\V1\Patient\Measurements;

use App\Actions\Patient\Measurements\StoreUserMeasurementAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Measurements\StoreUserMeasurementRequest;

class UserMeasurementController extends Controller
{
    public function store(StoreUserMeasurementRequest $request, StoreUserMeasurementAction $action)
    {
     $action->execute(  $request->validated(),auth()->id());
       return $this->ok(__('messages.measurement_saved'));
    }
}