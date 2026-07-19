<?php

namespace App\Http\Requests\API\V1\Patient\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalDataRequest extends FormRequest
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
            'blood_type' => ['sometimes', 'string', 'max:3'],
            'height' => ['sometimes', 'numeric'],
            'weight' => ['sometimes', 'numeric'],
            'chronic_conditions' => ['sometimes', 'array'],
            'chronic_conditions.*' => ['integer', 'exists:chronic_conditions,id'],
            'allergies' => ['sometimes', 'array'],
            'allergies.*' => ['integer', 'exists:allergies,id'],
        ];
    }
}
