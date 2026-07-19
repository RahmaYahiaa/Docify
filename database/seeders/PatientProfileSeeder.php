<?php

namespace Database\Seeders;

use App\Models\Allergy;
use App\Models\ChronicCondition;
use App\Models\PatientProfile;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class PatientProfileSeeder extends Seeder
{
    public function run(): void
    {
       $egyptianAddresses = [
    'Tahrir Street, Dokki, Giza',
    'Abbas El-Akkad Street, Nasr City, Cairo',
    'El Haram Street, Giza',
    'El Mahatta Street, El Montaza, Alexandria',
    'Salah Salem Street, Heliopolis, Cairo',
    'Gamal Abdel Nasser Street, Minya',
    'El Bahr El Aazam Street, Faisal, Giza',
    'El Thawra Street, Agouza, Giza',
    'El Wadi Street, Maadi, Cairo',
    'Sudan Street, Mohandessin, Giza',
    'Corniche El Nile, Zamalek, Cairo',
    'Kasr El Aini Street, Sayeda Zeinab, Cairo',
    'El Geish Street, Port Said',
    'Tanta Street, Gharbia',
    'Corniche Street, Ismailia',
];
        $patientProfiles = [
            ['email' => 'karim.mahmoud@gmail.com',    'dob' => '1990-04-15', 'gender' => 'male',   'contact' => '01501112201'],
            ['email' => 'dina.elsayed@gmail.com',     'dob' => '1985-08-22', 'gender' => 'female', 'contact' => '01512223302'],
            ['email' => 'mostafa.hussein@gmail.com',  'dob' => '1992-11-05', 'gender' => 'male',   'contact' => '01523334403'],
            ['email' => 'noha.abdallah@gmail.com',    'dob' => '1988-03-18', 'gender' => 'female', 'contact' => '01534445504'],
            ['email' => 'patient@gmail.com',       'dob' => '1975-07-30', 'gender' => 'male',   'contact' => '01545556605'],
            ['email' => 'shimaa.bakr@gmail.com',      'dob' => '1995-01-12', 'gender' => 'female', 'contact' => '01556667706'],
            ['email' => 'youssef.omar@gmail.com',     'dob' => '1983-09-25', 'gender' => 'male',   'contact' => '01567778807'],
            ['email' => 'rania.taher@gmail.com',      'dob' => '1991-06-08', 'gender' => 'female', 'contact' => '01578889908'],
            ['email' => 'islam.fathi@gmail.com',      'dob' => '1987-12-17', 'gender' => 'male',   'contact' => '01589990009'],
            ['email' => 'yasmine.kamal@gmail.com',    'dob' => '1998-05-03', 'gender' => 'female', 'contact' => '01590001110'],
            ['email' => 'abdo.saleh@gmail.com',       'dob' => '1972-02-28', 'gender' => 'male',   'contact' => '01501112311'],
            ['email' => 'marwa.gaber@gmail.com',      'dob' => '1993-10-14', 'gender' => 'female', 'contact' => '01512223412'],
            ['email' => 'ahmed.khalil@gmail.com',     'dob' => '1980-07-07', 'gender' => 'male',   'contact' => '01523334513'],
            ['email' => 'lamia.awad@gmail.com',       'dob' => '1996-04-19', 'gender' => 'female', 'contact' => '01534445614'],
            ['email' => 'sayed.hassan@gmail.com',     'dob' => '1969-11-01', 'gender' => 'male',   'contact' => '01545556715'],
            ['email' => 'hoda.monir@gmail.com',       'dob' => '1984-08-26', 'gender' => 'female', 'contact' => '01556667816'],
            ['email' => 'bilal.nasr@gmail.com',       'dob' => '1999-03-10', 'gender' => 'male',   'contact' => '01567778917'],
            ['email' => 'asmaa.aziz@gmail.com',       'dob' => '1977-06-22', 'gender' => 'female', 'contact' => '01578880018'],
            ['email' => 'mahmoud.sharqawy@gmail.com', 'dob' => '1986-01-30', 'gender' => 'male',   'contact' => '01589991119'],
            ['email' => 'nevin.ragheb@gmail.com',     'dob' => '1994-09-05', 'gender' => 'female', 'contact' => '01590002220'],
            ['email' => 'amro.makram@gmail.com',      'dob' => '1971-12-20', 'gender' => 'male',   'contact' => '01501112421'],
            ['email' => 'safaa.helmy@gmail.com',      'dob' => '1989-05-15', 'gender' => 'female', 'contact' => '01512223522'],
            ['email' => 'maged.zeidan@gmail.com',     'dob' => '1982-08-08', 'gender' => 'male',   'contact' => '01523334623'],
            ['email' => 'engy.badr@gmail.com',        'dob' => '1997-02-14', 'gender' => 'female', 'contact' => '01534445724'],
            ['email' => 'anwar.tawfik@gmail.com',     'dob' => '1965-10-03', 'gender' => 'male',   'contact' => '01545556825'],
        ];

        // Predefined condition assignments for realistic data
        $conditionMap = [
            'karim.mahmoud@gmail.com'    => ['Hypertension'],
            'dina.elsayed@gmail.com'     => ['Diabetes Type 2', 'High Cholesterol'],
            'mostafa.hussein@gmail.com'  => ['Asthma'],
            'noha.abdallah@gmail.com'    => ['Thyroid Disorders'],
            'patient@gmail.com'       => ['Hypertension', 'Diabetes Type 2', 'Heart Disease'],
            'shimaa.bakr@gmail.com'      => [],
            'youssef.omar@gmail.com'     => ['High Cholesterol'],
            'rania.taher@gmail.com'      => ['Anxiety Disorders'],
            'islam.fathi@gmail.com'      => ['Chronic Kidney Disease'],
            'yasmine.kamal@gmail.com'    => [],
            'abdo.saleh@gmail.com'       => ['Hypertension', 'Diabetes Type 2', 'COPD'],
            'marwa.gaber@gmail.com'      => ['Thyroid Disorders'],
            'ahmed.khalil@gmail.com'     => ['Hypertension', 'High Cholesterol'],
            'lamia.awad@gmail.com'       => [],
            'sayed.hassan@gmail.com'     => ['Heart Disease', 'Hypertension', 'Diabetes Type 2'],
            'hoda.monir@gmail.com'       => ['Rheumatoid Arthritis'],
            'bilal.nasr@gmail.com'       => [],
            'asmaa.aziz@gmail.com'       => ['Osteoporosis', 'Anxiety Disorders'],
            'mahmoud.sharqawy@gmail.com' => ['Asthma', 'Hypertension'],
            'nevin.ragheb@gmail.com'     => [],
            'amro.makram@gmail.com'      => ['Diabetes Type 2', 'Obesity'],
            'safaa.helmy@gmail.com'      => ['Depression'],
            'maged.zeidan@gmail.com'     => ['Hypertension'],
            'engy.badr@gmail.com'        => [],
            'anwar.tawfik@gmail.com'     => ['Heart Disease', 'Diabetes Type 2', 'Hypertension', 'High Cholesterol'],
        ];

        $allergyMap = [
            'karim.mahmoud@gmail.com'    => ['Penicillin'],
            'dina.elsayed@gmail.com'     => ['Aspirin'],
            'mostafa.hussein@gmail.com'  => ['Peanuts', 'Seafood'],
            'patient@gmail.com'       => ['NSAIDs'],
            'rania.taher@gmail.com'      => ['Fragrances'],
            'abdo.saleh@gmail.com'       => ['Sulfa Drugs'],
            'sayed.hassan@gmail.com'     => ['Penicillin', 'Aspirin'],
            'asmaa.aziz@gmail.com'       => ['Latex'],
            'anwar.tawfik@gmail.com'     => ['NSAIDs', 'Aspirin'],
        ];

        foreach ($patientProfiles as $i => $profileData) {
            $user = User::where('email', $profileData['email'])->first();
            if (! $user) {
                continue;
            }

            $address = $egyptianAddresses[$i % count($egyptianAddresses)];

            $profile = PatientProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'date_of_birth'     => $profileData['dob'],
                    'gender'            => $profileData['gender'],
                    'address'           => $address,
                    'emergency_contact' => '015' . rand(10000000, 99999999),
                ]
            );

            // Sync chronic conditions
            $conditionNames = $conditionMap[$profileData['email']] ?? [];
            if (! empty($conditionNames)) {
                $conditionIds = ChronicCondition::whereIn('name', $conditionNames)->pluck('id')->toArray();
                $profile->chronicConditions()->sync($conditionIds);
            }

            // Sync allergies
            $allergyNames = $allergyMap[$profileData['email']] ?? [];
            if (! empty($allergyNames)) {
                $allergyIds = Allergy::whereIn('name', $allergyNames)->pluck('id')->toArray();
                $profile->allergies()->sync($allergyIds);
            }
        }
    }
}