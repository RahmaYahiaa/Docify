<?php

namespace App\Http\Requests\API\V1\Admin\User;

use App\Enums\Role\UserRoleEnum;
use App\Models\User\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class DeleteUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user' => $this->route('user'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $user = $this->route('user');
            User::with('roles')->get();

            if ($user->roles->contains('name', UserRoleEnum::ADMIN)) {
                $validator->errors()->add('user', __('messages.admin_cannot_be_deleted'));
            }
        });
    }
}
