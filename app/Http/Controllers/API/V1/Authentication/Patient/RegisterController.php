<?php

namespace App\Http\Controllers\API\V1\Authentication\Patient;

use App\Actions\Register\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\RegisterRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request, RegisterAction $registerAction): JsonResponse
    {
        $user = $registerAction->execute($request->validated());
        $user->load('roles:id,name');

        return $this->ok(__('messages.register_successfully'), data: new AuthenticationResource($user));
    }
}
