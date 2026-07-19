<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedAppointments = Appointment::where('status', 'completed')
            ->with(['doctor', 'patient'])
            ->limit(50)
            ->get();

        $positiveComments = [
            'Excellent doctor, very cooperative and explains everything clearly. Takes enough time to listen carefully.',
            'Highly experienced and accurate diagnosis. I strongly recommend this doctor.',
            'Very polite and professional. Booking was easy and the consultation started on time.',
            'One of the best doctors I have visited. I felt reassured from the very first minute.',
            'Accurate diagnosis and effective treatment. My condition improved quickly.',
            'Professional and friendly. I feel comfortable speaking openly during the visit.',
            'Clean and well-organized clinic. The consultation was quick and efficient.',
            'Detailed explanation of the condition and treatment. Patiently answered all my questions.',
            'Great follow-up after the visit. Sent the medical report via mobile.',
            'Highly skilled and knowledgeable with excellent results.',
        ];

        $averageComments = [
            'The consultation was good, but the waiting time was a bit long.',
            'Good doctor, but more time should be given for explanations.',
            'Helpful, but the clinic was quite crowded.',
            'Correct diagnosis, but the treatment explanation could have been more detailed.',
        ];

        foreach ($completedAppointments as $appointment) {
            // 80% chance of leaving a review
            if (rand(1, 10) > 8) {
                continue;
            }

            // Skip if review already exists for this appointment
            if (Review::where('appointment_id', $appointment->id)->exists()) {
                continue;
            }

            // IMPORTANT: reviews table uses 'doctor_profile_id' + 'user_id' (NOT doctor_id / patient_id)
            $doctorProfile = DoctorProfile::where('user_id', $appointment->doctor_id)->first();
            if (! $doctorProfile) {
                continue;
            }

            $isPositive = rand(1, 10) > 2; // 80% positive
            $rating     = $isPositive ? rand(4, 5) : rand(3, 4);
            $comment    = $isPositive
                ? $positiveComments[array_rand($positiveComments)]
                : $averageComments[array_rand($averageComments)];

            Review::create([
                'appointment_id'   => $appointment->id,
                'doctor_profile_id' => $doctorProfile->id,   // ← correct FK
                'user_id'          => $appointment->patient_id, // ← correct FK
                'rating'           => $rating,
                'comment'          => $comment,
            ]);
        }
    }
}