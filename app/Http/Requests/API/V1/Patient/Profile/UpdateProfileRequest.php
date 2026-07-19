<?php

namespace App\Http\Requests\API\V1\Patient\Profile;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'first_name'     => ['sometimes', 'string', 'max:255'],
            'last_name'      => ['sometimes', 'string', 'max:255'],
            'email'          => ['sometimes','email', 'max:255', Rule::unique('users', 'email')->ignore($this->user()->id)],
            'phone'          => ['sometimes', 'string', 'max:20'],
            'date_of_birth'  => ['sometimes', 'date'],
            'gender'         => ['sometimes', 'in:male,female'],
            'address'        => ['sometimes', 'string', 'max:500'],
            'emergency_contact' => 'sometimes|string|max:50',
        ];
    }
}
