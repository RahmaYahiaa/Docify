<?php

namespace App\Http\Requests\API\V1\Doctor\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorProfileRequest extends FormRequest
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
          'first_name' => ['required', 'string'],
        'last_name' => ['required', 'string'],
        'phone' => ['nullable', 'string'],
   'about' => ['nullable', 'string'],
'years_of_experience'=>['nullable', 'string'],
        'clinic.name' => ['required', 'string'],
        'clinic.address' => ['required', 'string'],
        'clinic.city' => ['required', 'string'],
        'clinic.phone' => ['required', 'string'],
        ];
    }
}
