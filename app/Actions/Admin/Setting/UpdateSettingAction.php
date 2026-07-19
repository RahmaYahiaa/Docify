<?php

namespace App\Actions\Admin\Setting;

use App\Models\Setting;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateSettingAction
{
    use AsAction;

    public function execute(string $key, bool $is_enabled): Setting
    {
        $setting = Setting::where('key', $key)->firstOrFail();

        $setting->update([
            'is_enabled' => $is_enabled,
        ]);

        return $setting->fresh();
    }
}
