<?php

namespace App\Http\Requests\API\V1\Doctor\Profile;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultationFeesRequest extends FormRequest
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
        'video_fee'     => ['required', 'numeric', 'min:0'],
        'in_person_fee' => ['required', 'numeric', 'min:0'],
    ];
 }
}
