<?php

namespace App\Console\Commands;

use App\Events\Notification\MedicationDue;
use App\Models\MedicationDose;
use App\Models\User\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMedicationNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'medications:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for medication doses due now';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $startTime = now();
        $totalFound = 0;
        $totalDispatched = 0;

        MedicationDose::where('taken', false)
            ->whereNull('notified_at')
            ->where('dose_time', '<=', $startTime)
            ->with([
                'user',
                'prescriptionItem.prescription.patient',
                'medication'
            ])
            ->chunk(100, function ($doses) use (&$totalFound, &$totalDispatched) {

                $totalFound += $doses->count();

                foreach ($doses as $dose) {

                    $patient = $dose->user
                        ?? $dose->prescriptionItem?->prescription?->patient;

                    if (!$patient) {
                        $this->warn("Dose {$dose->id} has no patient");
                        continue;
                    }

                    $this->info("Sending notification for dose #{$dose->id}");

                    event(new MedicationDue($patient, $dose));

                    $dose->update([
                        'notified_at' => now()
                    ]);

                    $totalDispatched++;
                }
            });

        $this->info("Found doses: {$totalFound}");
        $this->info("Notifications sent: {$totalDispatched}");
    }
}
