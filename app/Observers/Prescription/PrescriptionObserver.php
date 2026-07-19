<?php

namespace App\Observers\Prescription;

use App\Models\Prescription;

class PrescriptionObserver
{
    /**
     * Handle the Prescription "created" event.
     */
    public function creating(Prescription $prescription)
    {

        if (auth()->check()) {
            $prescription->doctor_id = auth()->id();
        }
    }

    public function created(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "updated" event.
     */
    public function updated(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "deleted" event.
     */
    public function deleted(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "restored" event.
     */
    public function restored(Prescription $prescription): void
    {
        //
    }

    /**
     * Handle the Prescription "force deleted" event.
     */
    public function forceDeleted(Prescription $prescription): void
    {
        //
    }
}
