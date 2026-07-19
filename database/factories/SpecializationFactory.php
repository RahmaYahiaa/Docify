<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SpecializationFactory extends Factory
{
    public function definition(): array
    {
        $specializations = [
            'Cardiology', 'Neurology', 'Orthopedics', 'Dermatology', 'Pediatrics',
            'General Medicine', 'Psychiatry', 'Ophthalmology', 'ENT', 'Gynecology',
        ];

        return [
            'name' => fake()->unique()->randomElement($specializations),
        ];
    }
}
