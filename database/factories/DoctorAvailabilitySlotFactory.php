<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class DoctorAvailabilitySlotFactory extends Factory
{
  public function definition(): array
    {
        $doctor = User::role('doctor')->inRandomOrder()->first()
            ?? User::factory()->doctor()->create();
        $type = fake()->randomElement(['video', 'in_person']);
        $date = fake()->dateTimeBetween('now', '+60 days')->format('Y-m-d');
        $duration = $type === 'video' ? 20 : 30;
        $existingSlots = DoctorAvailabilitySlot::where('doctor_id', $doctor->id)
            ->where('date', $date)
            ->get();
        $startTime = $this->generateNonOverlappingStartTime($existingSlots, $duration);
        $clinicId = $type === 'in_person'
            ? Clinic::inRandomOrder()->first()?->id
            : null;

        return [
            'doctor_id' => $doctor->id,
            'date' => $date,
            'start_time' => $startTime,
            'duration_minutes' => $duration,
            'type' => $type,
            'clinic_id' => $clinicId,
            'status' => 'available',
        ];
    }
    private function generateNonOverlappingStartTime($existingSlots, int $durationMinutes): string
    {
        $startOfDay = Carbon::parse('08:00:00');
        $endOfDay = Carbon::parse('22:00:00');

        $attempts = 0;
        $maxAttempts = 50; 
        do {
            $candidate = $startOfDay->copy()->addMinutes(fake()->numberBetween(0, $endOfDay->diffInMinutes($startOfDay)));
            $candidateEnd = $candidate->copy()->addMinutes($durationMinutes);
            if ($candidateEnd->greaterThan($endOfDay)) {
                continue;
            }

            $overlaps = false;
            foreach ($existingSlots as $slot) {
                $slotStart = Carbon::parse($slot->start_time);
                $slotEnd = $slotStart->copy()->addMinutes($slot->duration_minutes);
                if ($candidate->lessThan($slotEnd) && $candidateEnd->greaterThan($slotStart)) {
                    $overlaps = true;
                    break;
                }
            }

            if (! $overlaps) {
                return $candidate->format('H:i:s');
            }
            $attempts++;
        } while ($attempts < $maxAttempts);
        return '09:00:00';
    }

    public function video()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'video',
                'duration_minutes' => 20,
                'clinic_id' => null,
            ];
        });
    }

    public function inPerson()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'in_person',
                'duration_minutes' => 30,
                'clinic_id' => Clinic::inRandomOrder()->first()?->id,
            ];
        });
    }
}