<?php

namespace App\Actions\Admin\User;

use App\Enums\User\SuspendReasonEnum;
use App\Models\User\User;
use Lorisleiva\Actions\Concerns\AsAction;

class SuspendUserAction
{
    use AsAction;

    public function execute(User $user, SuspendReasonEnum $reason): User
    {
        $user->update([
            'status' => 'suspended',
        ]);

        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->withProperties([
                'type' => 'suspend',
                'reason_key' => $reason->value,
                'reason' => $reason->label(),
            ])
            ->log('User Suspended');

        return $user;
    }
}
