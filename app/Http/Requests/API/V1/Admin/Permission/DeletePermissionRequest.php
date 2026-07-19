<?php

namespace App\Http\Requests\API\V1\Admin\Permission;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DeletePermissionRequest extends FormRequest
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

        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'permission' => $this->route('permission'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $permission = $this->route('permission')->withCount(['roles', 'users']);

            if ($permission->roles_count > 0) {
                $validator->errors()->add('permission', __('messages.permission_has_associated_roles'));

                return;
            }

            if ($permission->users_count > 0) {
                $validator->errors()->add('permission', __('messages.permission_has_associated_users'));

                return;
            }

        });
    }
}
