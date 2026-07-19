<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'card_payments',  'is_enabled' => true],
            ['key' => 'cash_payments',  'is_enabled' => true],
            ['key' => 'refund_policy',  'is_enabled' => true],
            ['key' => 'video_calls',    'is_enabled' => true],
            ['key' => 'in_person_bookings', 'is_enabled' => true],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['is_enabled' => $setting['is_enabled']]
            );
        }
    }
}