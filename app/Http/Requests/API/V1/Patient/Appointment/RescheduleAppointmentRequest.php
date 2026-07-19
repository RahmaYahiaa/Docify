<?php

namespace App\Http\Requests\API\V1\Patient\Appointment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RescheduleAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'slot_id' => ['required', Rule::exists('doctor_availability_slots', 'id')],
        ];
    }
}
