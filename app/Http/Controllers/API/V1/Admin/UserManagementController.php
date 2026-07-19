<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Admin\User\ActivateUserAction;
use App\Actions\Admin\User\CreateAdminAction;
use App\Actions\Admin\User\CreateDoctorAction;
use App\Actions\Admin\User\SuspendUserAction;
use App\Enums\User\ActivateReasonEnum;
use App\Enums\User\SuspendReasonEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\User\ActivateUserRequest;
use App\Http\Requests\API\V1\Admin\User\CreateAdminRequest;
use App\Http\Requests\API\V1\Admin\User\CreateDoctorRequest;
use App\Http\Requests\API\V1\Admin\User\SuspendUserRequest;
use App\Http\Resources\API\V1\Admin\UserDetailsCollection;
use App\Http\Resources\API\V1\Admin\UserDetailsResource;
use App\Models\User\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $users = QueryBuilder::for(User::class)
            ->with(['roles', 'doctorProfile.specialization'])
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, $value) {
                    $query->where(function ($q) use ($value) {
                        $q->where('first_name', 'LIKE', "%{$value}%")
                            ->orWhere('last_name', 'LIKE', "%{$value}%")
                            ->orWhere('email', 'LIKE', "%{$value}%")
                            ->orWhere('phone', 'LIKE', "%{$value}%");
                    });
                }),

                AllowedFilter::partial('first_name', 'first_name'),
                AllowedFilter::partial('email', 'email'),
                AllowedFilter::partial('phone', 'phone'),

                AllowedFilter::callback('role', function ($query, $value) {
                    $query->role($value);
                }),

                AllowedFilter::callback('status', function ($query, $value) {
                    $query->where('status', $value);
                }),
            ])
            ->latest()
            ->paginate(10);

        return new UserDetailsCollection($users);
    }

    public function show(User $user)
    {
        return $this->ok(data: new UserDetailsResource($user));
    }

    public function CreateDoctor(CreateDoctorRequest $request, CreateDoctorAction $action)
    {
        $action->execute($request->validated());

        return $this->ok(__('messages.doctor_created_successfully'));
    }

    public function CreateAdmin(CreateAdminRequest $request, CreateAdminAction $action)
    {
        $action->execute($request->validated());

        return $this->ok(__('messages.admin_created_successfully'));
    }

    public function suspend(User $user, SuspendUserRequest $request, SuspendUserAction $action)
    {
        $action->execute($user, SuspendReasonEnum::from($request->reason));

        return $this->ok(__('messages.user_suspended'));
    }

    public function activate(User $user, ActivateUserRequest $request, ActivateUserAction $action)
    {
        $action->execute($user, ActivateReasonEnum::from($request->reason));

        return $this->ok(__('messages.user_activated_successfully'));
    }

    public function reason()
    {
        return response()->json([
            'suspend_reasons' => SuspendReasonEnum::options(),
            'activate_reasons' => ActivateReasonEnum::options(),
        ]);
    }
}
