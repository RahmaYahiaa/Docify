<?php

namespace App\Http\Requests\API\V1\Patient\Availability;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetDoctorSlotsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['video', 'in_person'])],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:' . Carbon::today()->toDateString()],
        ];
    }

    public function validatedData(): array
    {
        $data = $this->validated();
        return [
            'type' => $data['type'],
            'date' => Carbon::parse($data['date']),
        ];
    }
}