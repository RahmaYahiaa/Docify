<?php

namespace App\Http\Controllers\API\V1\Notification;

use App\Actions\Notification\StoreFcmTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Notification\StoreFcmTokenRequest;
// use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function storeToken(StoreFcmTokenRequest $request,StoreFcmTokenAction $action)
    {
        $action->execute(auth()->id(), $request->validated());
        
        return $this->ok(__('messages.fcm_token_stored_successfully'));
    }
}
