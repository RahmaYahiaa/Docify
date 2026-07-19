<?php

namespace App\Http\Requests\API\V1\Patient\Appointment;

use App\Enums\Appointment\CancelReasonEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CancelAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'reason' => ['nullable', Rule::in(array_column(CancelReasonEnum::cases(), 'value'))],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
