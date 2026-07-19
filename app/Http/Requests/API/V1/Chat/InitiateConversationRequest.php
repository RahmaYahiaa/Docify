<?php

namespace App\Http\Requests\API\V1\Chat;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InitiateConversationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'integer', Rule::exists('users', 'id'), Rule::notIn([$this->user()->id])],// check if user is active after integration //->where('status', 'active')
        ];
    }
    public function messages(): array
    {
        return [
            'receiver_id.required' => 'Receiver is required.',
            'receiver_id.exists'   => 'User not found.',
            'receiver_id.not_in'   => 'You cannot start a conversation with yourself.',
        ];
    }
}
