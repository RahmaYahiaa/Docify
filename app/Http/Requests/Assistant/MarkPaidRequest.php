<?php

namespace App\Http\Requests\Assistant;

use Illuminate\Foundation\Http\FormRequest;

class MarkPaidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(): bool
{
    $appointment = $this->route('appointment');
    return $appointment && $appointment->doctor_id === auth()->user()->doctor_id;
}

    /**
     * 
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
