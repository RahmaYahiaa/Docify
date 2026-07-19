<?php

namespace App\Http\Resources\API\V1\Assistant;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->full_name,
            'email'          => $this->email,
            'initials'       => $this->getInitials(),
            'role'           => 'Doctor Assistant',
            'linked_doctor'  => $this->doctor ? 'Dr. ' . $this->doctor->full_name : 'No doctor linked',
        ];
    }


private function getInitials()
{
    return Str::upper(
        collect(explode(' ', $this->full_name))
            ->map(fn ($n) => mb_substr($n, 0, 1))
            ->join('')
    );
}
}