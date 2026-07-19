<?php

namespace App\Http\Requests\Role;

use App\Enums\Role\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateRoleRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($this->role->id)],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $role = $this->route('role');
            // cannot update admin role
            if ($role->name === UserRoleEnum::ADMIN->value) {
                $validator->errors()->add('name', __('messages.admin_role_cannot_be_updated'));

                return;
            }
        });
    }
}
