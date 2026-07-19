<?php

namespace App\Http\Requests\Role;

use App\Enums\Role\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class DestroyRoleRequest extends FormRequest
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
        return [];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'role' => $this->route('role'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $role = $this->route('role');

            if ($role->users_count > 0) {
                $validator->errors()->add('role', __('messages.role_has_associated_users'));

                return;
            }

            if ($role->name === UserRoleEnum::ADMIN->value) {
                $validator->errors()->add('role', __('messages.admin_role_cannot_be_deleted'));

                return;
            }
        });
    }
}
