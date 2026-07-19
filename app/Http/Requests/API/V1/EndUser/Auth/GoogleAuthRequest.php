<?php

namespace App\Http\Requests\API\V1\EndUser\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GoogleAuthRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'google_id' => ['required', 'string'],
            'password' => ['required', 'string'],
            'role' => ['required', 'string'],
            'status' => ['required', 'string'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }
}
