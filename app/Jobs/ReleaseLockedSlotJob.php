<?php

namespace App\Jobs;

use App\Enums\Availability\AvailabilitySlotStatusEnum;
use App\Models\DoctorAvailabilitySlot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class ReleaseLockedSlotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;
    public function __construct(
        private readonly int $slotId
    ) {}

    public function handle(): void
    {
        $slot = DoctorAvailabilitySlot::find($this->slotId);

        if (!$slot || !$slot->isLocked()) {
            Log::info('ReleaseLockedSlotJob: slot already released or not found', [
                'slot_id' => $this->slotId,
            ]);
            return;
        }

        $slot->update([
            'status' => AvailabilitySlotStatusEnum::AVAILABLE,
            'locked_until' => null,
        ]);

        Log::info('ReleaseLockedSlotJob: slot released', [
            'slot_id' => $this->slotId,
        ]);
    }
    public function failed(Throwable $exception): void
    {
        Log::error('ReleaseLockedSlotJob: failed', [
            'slot_id' => $this->slotId,
            'exception' => $exception->getMessage(),
        ]);
    }
}
