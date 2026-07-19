<?php
namespace App\Actions\Patient\Profile;

use App\Models\User\User;

class UploadLabReportAction
{
    public function execute(User $user, $file): void
    {
        $user->addMedia($file)
            ->toMediaCollection(User::LAB_REPORTS);
    }

}