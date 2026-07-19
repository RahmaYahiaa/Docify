<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Admin\Doctor\ApproveDoctorAction;
use App\Actions\Admin\Doctor\RejectDoctorAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Doctor\RejectDoctorRequest;
use App\Http\Resources\API\V1\Doctor\Appointment\DoctorDetailsCollection;
use App\Http\Resources\API\V1\Doctor\Appointment\DoctorDetailsResource;
use App\Models\User\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::partial('name', 'first_name'),
                AllowedFilter::partial('status', 'status'),
                AllowedFilter::callback('global', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('first_name', 'like', "%{$value}%")
                            ->orWhere('last_name', 'like', "%{$value}%")
                            ->orWhereHas('doctorProfile', function ($dq) use ($value) {
                                $dq->whereHas('specialization', function ($sq) use ($value) {
                                    $sq->where('name', 'like', "%{$value}%");
                                });
                            });
                    });
                }),
            ])
            ->role(UserRoleEnum::DOCTOR->value)
            ->with([
                'doctorProfile.specialization',
                'media'
            ])->paginate(8);

        return new DoctorDetailsCollection($doctors);
    }

    public function show($id)
    {
        $user = User::role(UserRoleEnum::DOCTOR->value)
            ->with(['doctorProfile.specialization'])
            ->findOrFail($id);

        return $this->ok(data: new DoctorDetailsResource($user));
    }

    public function approve(User $doctor, ApproveDoctorAction $action)
    {
        $action->execute($doctor);
        return $this->ok(
            __('messages.doctor_accepted_successfully')
        );
    }

    public function reject(RejectDoctorRequest $request, User $doctor, RejectDoctorAction $action)
    {
        $action->execute($doctor, $request->reason);

        return $this->ok(__('messages.doctor_rejected_successfully'));
    }
}
