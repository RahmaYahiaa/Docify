<?php

namespace App\Http\Requests\API\V1\Chat;

use Illuminate\Foundation\Http\FormRequest;

class TypingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'is_typing' => ['required', 'boolean'],
        ];
    }
}
