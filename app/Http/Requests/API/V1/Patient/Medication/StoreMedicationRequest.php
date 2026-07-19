<?php

namespace App\Http\Requests\API\V1\Patient\Medication;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicationRequest extends FormRequest
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
            'medication_id' => ['nullable', 'exists:medications,id'],
            'name'          => ['nullable', 'nullable', 'string', 'max:255'],
            'dosage' => ['required', 'string', 'max:255'],
            'frequency' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'min:1'],
            'start_time' => ['required', 'date'],
        ];
    }
}
