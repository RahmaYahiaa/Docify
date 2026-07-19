<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Admin\Setting\UpdateSettingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Setting\UpdateSettingRequest;
use App\Http\Resources\API\V1\Admin\SystemSettingsResource;
use App\Models\Setting;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all();
        return SystemSettingsResource::collection($settings);
    }

    public function update(
        UpdateSettingRequest $request,
        UpdateSettingAction $action
    ) {
        $setting = $action->execute(
            $request->key,
            $request->is_enabled
        );

        return new SystemSettingsResource($setting);
    }
}
