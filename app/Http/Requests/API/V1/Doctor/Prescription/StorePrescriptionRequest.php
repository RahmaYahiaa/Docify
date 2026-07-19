<?php

namespace App\Http\Requests\API\V1\Doctor\Prescription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePrescriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => ['required', 'integer', Rule::exists('users', 'id')],
             'notes' => ['nullable', 'string', 'max:1000'],
             'diagnosis' => ['nullable', 'string', 'max:1000'],
            'medications' => ['required', 'array', 'min:1'],
            'medications.*.medication_id' => ['required', 'integer', Rule::exists('medications', 'id')],
            'medications.*.dosage' => ['required', 'string', 'max:255'],
            'medications.*.frequency' => ['required', 'integer', 'min:1'],
            'medications.*.duration' => ['required', 'integer', 'min:1'],
           'medications.*.instruction' => ['nullable' , 'string', 'max:255'],

        ];
    }
}
