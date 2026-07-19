<?php

namespace App\Http\Controllers\API\V1\Doctor;

use App\Actions\Doctor\Profile\Fee\UpdateConsultationFeesAction;
use App\Actions\Doctor\Profile\Language\AddDoctorLanguagesAction;
use App\Actions\Doctor\Profile\ProfilePicture\UploadDoctorPictureAction;
use App\Actions\Doctor\Profile\UpdateDoctorProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Profile\UpdateConsultationFeesRequest;
use App\Http\Requests\API\V1\Doctor\Profile\UpdateDoctorLanguagesRequest;
use App\Http\Requests\API\V1\Doctor\Profile\UpdateDoctorProfileRequest;
use App\Http\Resources\API\V1\Doctor\Profile\ConsultationFeeResource;
use App\Http\Resources\API\V1\Doctor\Profile\DoctorProfileResource;
use App\Http\Resources\API\V1\Doctor\Profile\DoctorResource;
use App\Models\DoctorProfile;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    public function show()
    {
        $doctor = auth()->user()->load(['doctorProfile' ]);
        return new DoctorProfileResource($doctor);
    }

public function data(User $user )
{
    $user = auth()->user()->load(['doctorProfile.specialization', 'doctorProfile.clinic']);

    return $this->ok(data: new DoctorResource($user));
}



public function update(UpdateDoctorProfileRequest $request, UpdateDoctorProfileAction $action  ): JsonResponse
{
    $user = auth()->user();
    $doctorProfile = $action->execute($user, $request->validated());
    return $this->ok( __('messages.profile_updated') );
}

public function updateFee(UpdateConsultationFeesRequest $request, UpdateConsultationFeesAction $action): JsonResponse
{//TODO

    $user = auth()->user();
    $profile = $user->doctorProfile;
    if (!$profile) {
        $profile = $user->doctorProfile()->create();
    }

    $doctorProfile = $action->execute($profile, $request->validated());

    return $this->ok(
        __('messages.profile_updated'),
        data: new ConsultationFeeResource($doctorProfile)
    );
}


public function showFee(): JsonResponse
{

    $profile = auth()->user()->doctorProfile;

    return $this->ok(  data: new ConsultationFeeResource($profile) );
}

 public function addLanguages( UpdateDoctorLanguagesRequest $request, AddDoctorLanguagesAction $action) {

    $profile = $action->execute($request->validated()['languages']);
    return response()->json([
        'status' => 'success',
        'message' => 'Languages added successfully',
        'current_languages' => $profile->languages
    ]);
}
public function getLanguages()
{

    $profile = auth()->user()->doctorProfile;
    return response()->json([
        "message" => null,
        "data" => [
            "languages" => $profile->languages ?? []
        ]
    ], 200);
}


public function uploadPhoto(Request $request, UploadDoctorPictureAction $action)
{

    $user = $action->execute($request->user(), $request->file('profile_picture'));


    return $this->ok(  __('messages.profile_updated') );
}
}

