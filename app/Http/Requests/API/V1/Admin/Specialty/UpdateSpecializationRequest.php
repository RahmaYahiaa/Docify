<?php

namespace App\Http\Requests\API\V1\Admin\Specialty;

use App\Enums\Specialty\SpecialtyStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpecializationRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255', Rule::unique('specializations', 'name')->ignore($this->specialization?->id)],
            'description' => ['sometimes', 'string', 'max:1000'],
            'status' => ['sometimes', Rule::in([SpecialtyStatusEnum::ACTIVE->value, SpecialtyStatusEnum::DISABLED->value,])],
            'icon_url' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
