<?php

namespace App\Http\Requests\API\V1\Patient\Medication;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicationRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'dosage' => ['sometimes', 'string', 'max:255'],
            'frequency' => ['sometimes', 'string', 'max:255'],
            'duration' => ['sometimes', 'string', 'min:1'],
            'start_time' => ['nullable', 'date'],
        ];
    }
}
