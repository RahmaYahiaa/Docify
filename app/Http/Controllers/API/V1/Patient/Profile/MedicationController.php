<?php

namespace App\Http\Controllers\API\V1\Patient\Profile;

use App\Actions\Patient\Profile\Medication\DeleteMedicationAction;
use App\Actions\Patient\Profile\Medication\GetMedicationsAction;
use App\Actions\Patient\Profile\Medication\StoreMedicationAction;
use App\Actions\Patient\Profile\Medication\UpdateMedicationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Patient\Medication\StoreMedicationRequest;
use App\Http\Requests\API\V1\Patient\Medication\UpdateMedicationRequest;
use App\Http\Resources\API\V1\Doctor\Profile\Medication\MedicationResource;
use App\Http\Resources\API\V1\Prescription\PrescriptionItem\PrescriptionItemResource;
use App\Models\Medication;
use App\Models\PrescriptionItem;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetMedicationsAction $action)
    {
        $items = $action->execute();

        return PrescriptionItemResource::collection($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationRequest $request, StoreMedicationAction $action)
    {
        $item = $action->execute($request->validated());

        return $this->ok(__('messages.medication_created_successfully'), new PrescriptionItemResource($item));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationRequest $request, PrescriptionItem $medication, UpdateMedicationAction $action)
    {
        $item = $action->execute($medication, $request->validated());
        return $this->ok(__('messages.medication_updated_successfully'), new PrescriptionItemResource($item));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PrescriptionItem $medication, DeleteMedicationAction $action)
    {
        $action->execute($medication);
        return $this->ok(__('messages.medication_deleted_successfully'));
    }

    public function getAllMedications(Request $request)
    {
        $medications = QueryBuilder::for(Medication::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
            ])
            ->defaultSort('name')
            ->get();

        return MedicationResource::collection($medications);
    }
}