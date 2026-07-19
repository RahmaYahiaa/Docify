<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Role\UpdateUserRolesAction;
use App\Actions\User\AssignRolesToUserAction;
use App\Actions\User\DeleteUserAction;
use App\Actions\User\UpdateUserAction;
use App\Filters\RoleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\User\DeleteUserRequest;
use App\Http\Requests\API\V1\Admin\User\RoleRequest;
use App\Http\Requests\API\V1\Admin\User\UpdateUserRequest;
use App\Http\Resources\API\V1\User\UserCollection;
use App\Http\Resources\API\V1\User\UserResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('email'),
                AllowedFilter::custom('roles', new RoleFilter),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new UserCollection($users));
    }

    public function store(Request $request) {}

    public function show(User $user): JsonResponse
    {
        return $this->ok(data: UserResource::make($user));
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): JsonResponse
    {
        $action->execute($user, $request->validated());

        return $this->ok(message: __('messages.user_updated_successfully'), data: UserResource::make($user)
        );
    }

    public function destroy(DeleteUserRequest $request, User $user, DeleteUserAction $action): JsonResponse
    {
        $action->execute($user);

        return $this->ok(message: __('messages.user_deleted_successfully'));
    }

    public function assignRoles(RoleRequest $request, User $user, AssignRolesToUserAction $action): JsonResponse
    {
        $action->execute($user, $request->validated('role'));

        return $this->ok(message: __('messages.roles_assigned_successfully'));

    }

    public function updateRoles(RoleRequest $request, User $user, UpdateUserRolesAction $action): JsonResponse
    {
        $action->execute($user, $request->validated('roles'));

        return $this->ok(message: __('messages.roles_updated_successfully'));
    }
}
