<?php

namespace App\Actions\Admin\Specialty;

use App\Models\Specialization;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSpecializationAction
{
    use AsAction;

    public function execute(Specialization $specialization, array $data): Specialization
    {
        $specialization->update([
            'name' => $data['name'] ?? $specialization->name,
            'description' => $data['description'] ?? $specialization->description,
            'status' => $data['status'] ?? $specialization->status,
        ]);

        if (!empty($data['icon_url'])) {
            $specialization->clearMediaCollection('specialty_icon');

            $specialization->addMedia($data['icon_url'])
                ->toMediaCollection('specialty_icon');
        }

        return $specialization;
    }
}
