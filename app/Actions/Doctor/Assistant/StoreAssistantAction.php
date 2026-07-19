<?php
namespace App\Actions\Doctor\Assistant;

use App\Models\User\User;
use App\Enums\Role\UserRoleEnum;
use App\Enums\User\UserStatusEnum;
use Illuminate\Support\Facades\Hash;

class StoreAssistantAction
{
    public function execute(array $data, int $doctorId): User
    {

        $assistant = User::create(array_merge($data, [
            'password'  => Hash::make($data['password']),
            'doctor_id' => $doctorId,
            'status'    => UserStatusEnum::ACTIVE,
        ]));


        $assistant->assignRole(UserRoleEnum::ASSISTANT->value);

        return $assistant;
    }
}