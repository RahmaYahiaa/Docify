<?php

namespace App\Http\Controllers\API\V1\Doctor\Appointment;

use App\Actions\Doctor\Availability\BlockDoctorAvailabilityAction;
use App\Actions\Doctor\Availability\CreateDoctorAvailabilityAction;
use App\Actions\Doctor\Availability\DeleteDoctorSlotAction;
use App\Actions\Doctor\Availability\ListDoctorSlotsAction;
use App\Actions\Doctor\Availability\UnblockDoctorAvailabilityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Doctor\Availability\BlockAvailabilityRequest;
use App\Http\Requests\API\V1\Doctor\Availability\ListDoctorSlotsRequest;
use App\Http\Requests\API\V1\Doctor\Availability\StoreAvailabilityRequest;
use App\Http\Requests\API\V1\Doctor\Availability\UnblockAvailabilityRequest;
use App\Http\Resources\API\V1\Doctor\Appointment\Slot\DoctorSlotCollection;
use App\Models\DoctorAvailabilitySlot;
use Illuminate\Http\JsonResponse;

class DoctorAvailabilityController extends Controller
{
    public function store(StoreAvailabilityRequest $request, CreateDoctorAvailabilityAction $action): JsonResponse
    {
        $action->execute(auth()->user(), $request->validated());
        return $this->ok(__('messages.doctor_availability_slots_created_successfully'));
    }

    public function block(BlockAvailabilityRequest $request, BlockDoctorAvailabilityAction $action): JsonResponse
    {
        $action->execute(auth()->user(), $request->validated());
        return $this->ok(__('messages.doctor_availability_slots_blocked_successfully'));
    }

    public function index(ListDoctorSlotsRequest $request, ListDoctorSlotsAction $action): JsonResponse
    {
        $slots = $action->execute(doctor: auth()->user(), filters: $request->validated());
        return $this->ok(__('messages.doctor_availability_slots_retrieved_successfully'), new DoctorSlotCollection($slots));
    }

    public function destroy(DoctorAvailabilitySlot $slot, DeleteDoctorSlotAction $action): JsonResponse
    {
        $action->execute(auth()->user(), $slot);
        return $this->ok(__('messages.doctor_availability_slot_deleted_successfully'));
    }

    public function unblock(UnblockAvailabilityRequest $request, UnblockDoctorAvailabilityAction $action): JsonResponse
    {
        $action->execute(auth()->user(), $request->validated());
        return $this->ok(__('messages.doctor_availability_slots_unblocked_successfully'));
    }
}
