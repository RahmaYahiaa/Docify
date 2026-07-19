<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Role\AssignPermissionsToRoleAction;
use App\Actions\Role\DeleteRoleAction;
use App\Actions\Role\StoreRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Role\AssignPermissionsRequest;
use App\Http\Requests\Role\DestroyRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\API\V1\Role\RoleCollection;
use App\Http\Resources\API\V1\Role\RoleResource;
use App\Models\Role;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ROLES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ROLES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ROLES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ROLES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ROLES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $roles = QueryBuilder::for(Role::class)
            ->withCount('users')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->macroPaginate();

        return $this->ok(data: new RoleCollection($roles));
    }

    public function store(StoreRoleRequest $request, StoreRoleAction $action): JsonResponse
    {
        $role = $action->execute($request->validated());

        return $this->ok(message: __('messages.role_created_successfully'), data: RoleResource::make($role));
    }

    public function show(Role $role): JsonResponse
    {
        return $this->ok(data: RoleResource::make($role));
    }

    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action): JsonResponse
    {
        $action->execute($role, $request->validated());

        return $this->ok(message: __('messages.role_updated_successfully'), data: RoleResource::make($role));
    }

    public function destroy(DestroyRoleRequest $request, Role $role, DeleteRoleAction $action): JsonResponse
    {
        $action->execute($role->loadCount('users'));

        return $this->ok(message: __('messages.role_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $roles = Role::query()->select(['id', 'name'])->get();

        return $this->ok(data: RoleResource::collection($roles));
    }

    public function assignPermissions(AssignPermissionsRequest $request, Role $role, AssignPermissionsToRoleAction $action): JsonResponse
    {

        $action->execute($role, $request->validated('permissions'));

        return $this->ok(message: __('messages.permissions_assigned_successfully'));
    }
}
