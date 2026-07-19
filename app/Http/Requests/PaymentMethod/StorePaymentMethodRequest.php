<?php

namespace App\Http\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
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
              'type'   => ['required', 'string'],
            'bank_name'           => ['required_if:type,bank', 'nullable', 'string', 'max:100'],
            'account_holder_name' => ['required', 'string', 'max:255'],
            'account_number'      => ['required', 'string', 'max:50'],
            'iban'                => ['nullable', 'string', 'max:50'],
        ];
    }
}
