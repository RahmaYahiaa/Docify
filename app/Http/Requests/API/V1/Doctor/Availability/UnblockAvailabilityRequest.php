<?php

namespace App\Http\Requests\API\V1\Doctor\Availability;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UnblockAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:' . Carbon::today()->toDateString()],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'type' => ['nullable', Rule::in(['video', 'in_person'])],
        ];
    }
}