<?php

namespace Database\Factories;

use App\Models\DoctorAvailabilitySlot;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $slot = DoctorAvailabilitySlot::where('status', 'available')->inRandomOrder()->first() ?? DoctorAvailabilitySlot::factory()->create();

        return [
            'patient_id' => User::factory()->patient(),
            'doctor_id' => $slot->doctor_id,
            'slot_id' => $slot->id,
            'type' => $slot->type,
            'status' => fake()->randomElement(['confirmed', 'completed']),
            'reason_for_visit' => fake()->sentence,
            'notes' => fake()->optional()->paragraph,
        ];
    }

    public function confirmed()
    {
        return $this->state(['status' => 'confirmed']);
    }

    public function pending()
    {
        return $this->state(['status' => 'pending']);
    }
}
