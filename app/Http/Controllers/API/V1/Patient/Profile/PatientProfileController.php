<?php

namespace App\Http\Controllers\API\V1\Patient\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\Patient\Profile\UpdateProfileAction;
use App\Actions\Patient\Profile\UploadProfilePictureAction;
use App\Http\Resources\API\V1\Patient\Profile\PatientProfileResource;
use App\Http\Requests\API\V1\Patient\Profile\UpdateProfileRequest;

class PatientProfileController extends Controller
{
    public function show(Request $request)
    {
        return new PatientProfileResource($request->user()->load('patientProfile'));
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action )
    {
        $user = $action->execute($request->user(), $request->validated());

        return $this->ok(__('messages.profile_updated'),PatientProfileResource::make($user));
    }

    public function uploadPhoto(Request $request, UploadProfilePictureAction $action)
    {
        $user = $action->execute($request->user(), $request->file('profile_picture'));

        return $this->ok(__('messages.profile_updated'),PatientProfileResource::make($user->load('patientProfile')));
    }
}
