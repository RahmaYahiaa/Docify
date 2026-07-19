<?php

namespace App\Http\Controllers\API\V1\Authentication\Doctor;

use App\Actions\Auth\RegisterDoctorAction;
use App\Actions\Auth\signUPwithgoogle\UploadCertificateAction;
use App\Models\User\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorRegisterRequest;
use App\Http\Requests\UploadCertificateRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use App\Http\Resources\API\V1\Authentication\DoctorRegisterMinimalResource;
use App\Http\Resources\API\V1\Role\RoleResource;
use Illuminate\Http\JsonResponse;

class DoctorRegisterController extends Controller
{
    public function register(
        DoctorRegisterRequest $request,
        RegisterDoctorAction $action
    ) {
        $user = $action->execute($request->validated());

        $user->load('roles:id,name');

        return $this->ok(
            __('messages.register_doctor'),
            DoctorRegisterMinimalResource::make($user)
        );
    }
  public function upload(
    UploadCertificateRequest $request,
    User $user,
    UploadCertificateAction $action
): JsonResponse
{
    $action->execute(  $user,  'certificate',  $request->specialization_id );

    return $this->ok(
        message: __('certificate_uploaded_successfully')
    );
}
}