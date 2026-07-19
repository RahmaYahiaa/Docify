<?php

namespace Database\Seeders;

use App\Models\Clinic;
use Illuminate\Database\Seeder;

class ClinicSeeder extends Seeder
{
    public function run(): void
    {
        $clinics = [
            [
                'name'    => 'Specialized Heart Center',
                'address' => '15 Tahrir Street, Dokki',
                'city'    => 'Giza',
                'phone'   => '0233456789',
            ],
            [
                'name'    => 'Al Nour Eye Clinic',
                'address' => '22 Omar Ibn El Khattab Street, Heliopolis',
                'city'    => 'Cairo',
                'phone'   => '0224567890',
            ],
            [
                'name'    => 'Sinai Medical Center',
                'address' => '8 Abbas El Akkad Street, Nasr City',
                'city'    => 'Cairo',
                'phone'   => '0226789012',
            ],
            [
                'name'    => 'Al Shifa Children Clinic',
                'address' => '5 El Mahatta Street, Montaza',
                'city'    => 'Alexandria',
                'phone'   => '0345678901',
            ],
            [
                'name'    => 'Al Hayah Bone & Joint Center',
                'address' => '30 Pyramids Street, Haram',
                'city'    => 'Giza',
                'phone'   => '0233450123',
            ],
            [
                'name'    => 'Beautiful Skin Dermatology Clinic',
                'address' => '12 Safeya Zaghloul Street, Downtown',
                'city'    => 'Alexandria',
                'phone'   => '0345012345',
            ],
            [
                'name'    => 'Minya Comprehensive Medical Center',
                'address' => '3 Gamal Abdel Nasser Street',
                'city'    => 'Minya',
                'phone'   => '0862345678',
            ],
            [
                'name'    => 'Peace of Mind Psychiatry Clinic',
                'address' => '17 Mostafa El Nahas Street, Nasr City',
                'city'    => 'Cairo',
                'phone'   => '0222345678',
            ],
            [
                'name'    => 'Internal Medicine Care Center',
                'address' => '9 El Nozha Street, El Zeitoun',
                'city'    => 'Cairo',
                'phone'   => '0224123456',
            ],
            [
                'name'    => 'Future Urology Clinic',
                'address' => '44 Sudan Street, Mohandessin',
                'city'    => 'Giza',
                'phone'   => '0237891234',
            ],
        ];

        foreach ($clinics as $clinic) {
            Clinic::firstOrCreate(
                ['name' => $clinic['name']],
                $clinic
            );
        }
    }
}