<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Permission\DeletePermissionAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Permission\DeletePermissionRequest;
use App\Http\Resources\API\V1\Admin\Permission\PermissionCollection;
use App\Http\Resources\API\V1\Admin\Permission\PermissionResource;
use App\Models\Permission;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $permissions = QueryBuilder::for(Permission::class)
            ->withCount('users')
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PermissionCollection($permissions));
    }

    public function show(Permission $permission): JsonResponse
    {
        return $this->ok(data: PermissionResource::make($permission));
    }

    public function destroy(DeletePermissionRequest $request, Permission $permission, DeletePermissionAction $action): JsonResponse
    {
        $action->execute($permission);

        return $this->ok(message: __('messages.permissions_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $permissions = Permission::query()->select(['id', 'name'])->orderBy('id', 'desc')->get();

        return $this->ok(data: PermissionResource::collection($permissions));
    }
}
