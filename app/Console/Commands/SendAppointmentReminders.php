<?php

namespace App\Console\Commands;

use App\Events\Notification\AppointmentReminder;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send appointment reminders before 6 hours';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = now();
        $total = 0;

        Appointment::query()
            ->whereNull('reminder_sent_at')
            ->where('status', 'confirmed')
            ->with([
                'patient',
                'doctor',
                'slot'
            ])
            ->chunk(100, function ($appointments) use (&$total, $now) {

                foreach ($appointments as $appointment) {

                    $slot = $appointment->slot;

                    if (!$slot) {
                        continue;
                    }

                    $appointmentDateTime = Carbon::parse($slot->date)
                        ->setTimeFromTimeString($slot->start_time);

                    if (
                        $appointmentDateTime->between(
                            $now->copy()->addHours(6),
                            $now->copy()->addHours(6)->addMinutes(5)
                        )
                    ) {
                        $appointment->update([
                            'reminder_sent_at' => now(),
                        ]);

                        event(
                            new AppointmentReminder($appointment)
                        );

                        $total++;
                    }
                }
            });

        $this->info(
            "Sent {$total} appointment reminders."
        );

        return self::SUCCESS;
    }
}
