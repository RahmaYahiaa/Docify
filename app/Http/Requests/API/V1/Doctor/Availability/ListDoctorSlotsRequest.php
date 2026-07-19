<?php

namespace App\Http\Requests\API\V1\Doctor\Availability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListDoctorSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date_format:Y-m-d'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'type' => ['nullable', Rule::in(['video', 'in_person'])],
            'status' => ['nullable', Rule::in(['available', 'booked', 'blocked', 'locked'])],
        ];
    }
}
