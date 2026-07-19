<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            ['first_name' => 'Karim',       'last_name' => 'Mahmoud',    'email' => 'karim.mahmoud@gmail.com',    'phone' => '01001112201'],
            ['first_name' => 'Dina',        'last_name' => 'Elsayed',    'email' => 'dina.elsayed@gmail.com',     'phone' => '01112223302'],
            ['first_name' => 'Mostafa',     'last_name' => 'Hussein',    'email' => 'mostafa.hussein@gmail.com',  'phone' => '01223334403'],
            ['first_name' => 'Noha',        'last_name' => 'Abdallah',   'email' => 'noha.abdallah@gmail.com',    'phone' => '01334445504'],
            ['first_name' => 'Ali',         'last_name' => 'Rashad',     'email' => 'patient@gmail.com',       'phone' => '01445556605'],
            ['first_name' => 'Shaimaa',     'last_name' => 'Bakr',       'email' => 'shimaa.bakr@gmail.com',      'phone' => '01556667706'],
            ['first_name' => 'Youssef',     'last_name' => 'Omar',       'email' => 'youssef.omar@gmail.com',     'phone' => '01667778807'],
            ['first_name' => 'Rania',       'last_name' => 'Tahir',      'email' => 'rania.taher@gmail.com',      'phone' => '01778889908'],
            ['first_name' => 'Islam',       'last_name' => 'Fathi',      'email' => 'islam.fathi@gmail.com',      'phone' => '01889990009'],
            ['first_name' => 'Yasmine',     'last_name' => 'Kamal',      'email' => 'yasmine.kamal@gmail.com',    'phone' => '01990001110'],
            ['first_name' => 'Abdulrahman', 'last_name' => 'Saleh',      'email' => 'abdo.saleh@gmail.com',       'phone' => '01001112311'],
            ['first_name' => 'Marwa',       'last_name' => 'Gaber',      'email' => 'marwa.gaber@gmail.com',      'phone' => '01112223412'],
            ['first_name' => 'Ahmed',       'last_name' => 'Khalil',     'email' => 'ahmed.khalil@gmail.com',     'phone' => '01223334513'],
            ['first_name' => 'Lamia',       'last_name' => 'Awad',       'email' => 'lamia.awad@gmail.com',       'phone' => '01334445614'],
            ['first_name' => 'Sayed',       'last_name' => 'Hassan',     'email' => 'sayed.hassan@gmail.com',     'phone' => '01445556715'],
            ['first_name' => 'Hoda',        'last_name' => 'Monir',      'email' => 'hoda.monir@gmail.com',       'phone' => '01556667816'],
            ['first_name' => 'Bilal',       'last_name' => 'Nasr',       'email' => 'bilal.nasr@gmail.com',       'phone' => '01667778917'],
            ['first_name' => 'Asmaa',       'last_name' => 'Aziz',       'email' => 'asmaa.aziz@gmail.com',       'phone' => '01778880018'],
            ['first_name' => 'Mahmoud',     'last_name' => 'Sharqawy',   'email' => 'mahmoud.sharqawy@gmail.com', 'phone' => '01889991119'],
            ['first_name' => 'Nevin',       'last_name' => 'Ragheb',     'email' => 'nevin.ragheb@gmail.com',     'phone' => '01990002220'],
            ['first_name' => 'Amro',        'last_name' => 'Makram',     'email' => 'amro.makram@gmail.com',      'phone' => '01001112421'],
            ['first_name' => 'Safaa',       'last_name' => 'Helmy',      'email' => 'safaa.helmy@gmail.com',      'phone' => '01112223522'],
            ['first_name' => 'Maged',       'last_name' => 'Zeidan',     'email' => 'maged.zeidan@gmail.com',     'phone' => '01223334623'],
            ['first_name' => 'Engy',        'last_name' => 'Badr',       'email' => 'engy.badr@gmail.com',        'phone' => '01334445724'],
            ['first_name' => 'Anwar',       'last_name' => 'Tawfik',     'email' => 'anwar.tawfik@gmail.com',     'phone' => '01445556825'],
        ];

        foreach ($patients as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'first_name'        => $data['first_name'],
                    'last_name'         => $data['last_name'],
                    'phone'             => $data['phone'],
                    'status'            => 'active',
                    'password'          => bcrypt('password'),
                    'email_verified_at' => now(),
                ]
            );

            if (! $user->hasRole('patient')) {
                $user->assignRole('patient');
            }
            // gender + dob handled in PatientProfileSeeder
        }
    }
}