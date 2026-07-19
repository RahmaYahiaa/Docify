<?php

namespace App\Http\Resources\API\V1;

use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'action' => [
                'name'        => $this->description,
                'type'        => strtolower(class_basename($this->subject_type)),
                'reason'      => $this->getExtraProperty('reason') ?? 'No reason provided',
                'message' => $this->formatMessage(),
            ],
            'actor' => [
                'name' => $this->causer?->full_name ?? 'System',
                'role' => $this->causer?->getRoleNames()?->first() ?? 'System',
            ],
            'target' => [
                'name' => $this->getSubjectName(),
                'type' => class_basename($this->subject_type),
            ],
            'timestamp' => [
                'date' => $this->created_at->format('M d'),
                'time' => $this->created_at->format('h:i A'),
            ],
        ];
    }

    private function getSubjectName()
    {
        $subject = $this->subject;

        if (!$subject) {
            return 'N/A';
        }

        if ($subject instanceof Prescription) {
            return $subject->patient?->full_name ?? 'Unknown Patient';
        }

        if ($subject instanceof Appointment) {
            return $subject->doctor?->full_name ?? 'Unknown Doctor';
        }

        if ($subject instanceof User) {
            return $subject->full_name;
        }
        return $subject->full_name ?? $subject->name ?? $subject->id;
    }

    private function formatMessage()
    {
        $message = $this->getExtraProperty('message');

        if (!$message) {
            return null;
        }

        if (str_starts_with($message, '{')) {
            return $this->causer?->full_name . ' performed action';
        }

        return $message;
    }
}