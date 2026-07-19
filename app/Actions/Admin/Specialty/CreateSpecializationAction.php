<?php

namespace App\Actions\Admin\Specialty;

use App\Enums\Specialty\SpecialtyStatusEnum;
use App\Models\Specialization;
use Illuminate\Validation\ValidationException;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateSpecializationAction
{
    use AsAction;

    public function execute(array $data): Specialization
    {
        $exists = Specialization::where('name', $data['name'])->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => ['The name has already been taken.']
            ]);
        }
        $specialization = Specialization::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? SpecialtyStatusEnum::ACTIVE->value,
        ]);

        if (!empty($data['icon_url'])) {
            $specialization
                ->addMedia($data['icon_url'])
                ->toMediaCollection('specialty_icon');
        }

        return $specialization;
    }
}
