<?php

namespace App\Http\Requests\API\V1\Doctor\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorLanguagesRequest extends FormRequest
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
        'languages' => 'required|array|min:1',
        'languages.*' => 'required|string|distinct',
    ];
    }
}
