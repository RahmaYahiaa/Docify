<?php

namespace App\Http\Requests\API\V1\Doctor\Assistant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAssistantRequest extends FormRequest
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
    {return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password'   => ['required', 'string', Password::min(8), 'max:255'],
            'phone'      => ['required', 'string', 'max:20', Rule::unique('users', 'phone')],
        ];
    }
}
