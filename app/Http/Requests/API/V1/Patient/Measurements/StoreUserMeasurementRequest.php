<?php

namespace App\Http\Requests\API\V1\Patient\Measurements;

use Illuminate\Foundation\Http\FormRequest;

 use Illuminate\Validation\Rule;

class StoreUserMeasurementRequest extends FormRequest
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
            'measurement_type_id' => ['required', 'integer', Rule::exists('measurement_types', 'id')],
            'value'               => ['required', 'numeric'],
             'value_2' => [  'nullable',  'numeric',  'required_if:measurement_type_id,1' ],
            'note'                => ['nullable', 'string', 'max:500','required_if:measurement_type_id,3'],
            'measured_at'         => ['nullable', 'date'],
        ];
    }
}
