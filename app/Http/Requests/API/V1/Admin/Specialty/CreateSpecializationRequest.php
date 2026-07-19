<?php

namespace App\Http\Requests\API\V1\Admin\Specialty;

use App\Enums\Specialty\SpecialtyStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateSpecializationRequest extends FormRequest
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
           'name' => ['required', 'array'],
             'name.en' => ['required', 'string', 'max:255'],
            'name.ar' => ['required', 'string', 'max:255'],
            'description' =>['nullable','string','max:1000'],
            'status' => ['nullable',new Enum(SpecialtyStatusEnum::class)],
            'icon_url' => ['required','image','mimes:jpg,jpeg,png,webp','max:2048'],
        ];
    }
}
