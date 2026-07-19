<?php

namespace App\Http\Requests\API\V1\Payment;

use Illuminate\Foundation\Http\FormRequest;

class CheckPaymentStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'cache_key' => ['required', 'string'],
        ];
    }
}
