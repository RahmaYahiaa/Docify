<?php

namespace App\Http\Controllers\API\V1;

use App\Actions\Admin\Specialty\ActivateSpecializationAction;
use App\Actions\Admin\Specialty\CreateSpecializationAction;
use App\Actions\Admin\Specialty\DisableSpecializationAction;
use App\Actions\Admin\Specialty\UpdateSpecializationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Specialty\CreateSpecializationRequest;
use App\Http\Requests\API\V1\Admin\Specialty\UpdateSpecializationRequest;
use App\Http\Resources\API\V1\Specialty\SpecializationDashboardResource;
use App\Http\Resources\API\V1\Specialty\SpecializationDetailsResource;
use App\Http\Resources\API\V1\Specialty\SpecializationResource;
use App\Models\Specialization;
use Illuminate\Http\JsonResponse;

class SpecializationController extends Controller
{
    public function dropdown(): JsonResponse
    {
        $specializations = Specialization::select(['id', 'name', 'status'])->active()->get();

        $data = SpecializationResource::collection($specializations)->resolve();

        return $this->ok(data: $data);
    }

    public function index(): JsonResponse
    {
        $specializations = Specialization::select(['id', 'name', 'status'])->active()->with('media')->get();
       
        return $this->ok(data: SpecializationResource::collection($specializations));
    }

    public function Specialty()
    {
        return $this->ok(data: new SpecializationDashboardResource(null));
    }

    public function store(CreateSpecializationRequest $request, CreateSpecializationAction $action)
    {
        $specialization = $action->execute($request->validated());

        return $this->ok(__('messages.specialization_created_successfully'), new SpecializationDetailsResource($specialization));
    }

    public function update(Specialization $specialization, UpdateSpecializationRequest $request, UpdateSpecializationAction $action)
    {
        $specialization = $action->execute($specialization, $request->validated());

        return $this->ok(__('messages.specialization_updated_successfully'), new SpecializationDetailsResource($specialization));
    }

    public function disable(Specialization $specialization, DisableSpecializationAction $action)
    {
        $action->execute($specialization);

        return $this->ok(__('messages.specialization_disabled_successfully'));
    }

    public function activate(Specialization $specialization, ActivateSpecializationAction $action)
    {
        $action->execute($specialization);

        return $this->ok(__('messages.specialization_activated_successfully'));
    }
}
