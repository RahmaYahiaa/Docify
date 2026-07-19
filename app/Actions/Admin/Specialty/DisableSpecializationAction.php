<?php

namespace App\Actions\Admin\Specialty;

use App\Enums\Specialty\SpecialtyStatusEnum;
use App\Models\Specialization;
use Lorisleiva\Actions\Concerns\AsAction;

class DisableSpecializationAction
{
    use AsAction;

    public function execute(Specialization $specialization) : Specialization
    {
        $specialization->update(['status'=>SpecialtyStatusEnum::DISABLED->value]);

        return $specialization;
    }
}
