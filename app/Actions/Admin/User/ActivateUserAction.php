<?php

namespace App\Actions\Admin\User;

use App\Enums\User\ActivateReasonEnum;
use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class ActivateUserAction
{
    use AsAction;

    public function execute(User $user, ActivateReasonEnum $reason): User
    {
        $user->update([
            'status' => 'active',
        ]);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'type' => 'activate',
                'reason_key' => $reason->value,
                'reason' => $reason->label(),
            ])
            ->log('User Activated');

        return $user;
    }
}
