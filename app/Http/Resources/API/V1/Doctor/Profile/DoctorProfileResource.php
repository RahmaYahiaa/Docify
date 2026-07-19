<?php

namespace App\Http\Resources\API\V1\Doctor\Profile;

use App\Models\DoctorAvailabilitySlot;
use App\Enums\Availability\AvailabilitySlotStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DoctorProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $todaySlots = DoctorAvailabilitySlot::where('doctor_id', $this->id)
            ->whereDate('date', today())
          //  ->where('status', AvailabilitySlotStatusEnum::BOOKED)
            ->get();

        $nextSlot = $todaySlots->where('start_time', '>', now()->format('H:i:s'))
            ->sortBy('start_time')
            ->first();

        return [
            'id' => $this->id,
            'greeting' => $this->getGreeting(),
            'name' => 'Dr. ' . $this->first_name . ' ' . $this->last_name,
            'profile_picture' => $this->doctorProfile?->getFirstMediaUrl('profile_picture')
                                 ?: asset('/images/default-doctor.jpg'),
            'today_info' => [
                'full_date' => now()->format('l, F j'),
            ],
            'appointments_summary' => [
                'today_count' => $todaySlots->count(),
                'next_session_time' => $nextSlot
                    ? Carbon::parse($nextSlot->start_time)->format('h:i A')
                    : null,
            ],
        ];
    }

    private function getGreeting(): string
    {
        $hour = now()->hour;
        if ($hour < 12) return 'Good Morning';
        if ($hour < 17) return 'Good Afternoon';
        return 'Good Evening';
    }
}
