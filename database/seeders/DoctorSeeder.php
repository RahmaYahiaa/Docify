<?php

namespace Database\Seeders;

use App\Models\Clinic;
use App\Models\DoctorProfile;
use App\Models\Specialization;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = [
            [
                'first_name'          => 'Mohamed',
                'last_name'           => 'El Shafei',
                'email'               => 'doctor@gmail.com',
                'phone'               => '01001234501',
                'specialization'      => 'Cardiology',
                'about'               => 'Senior consultant cardiologist with 18 years of experience treating complex heart conditions. Completed fellowship at Cairo University Hospital with advanced interventional cardiology training in Germany.',
                'years_of_experience' => 18,
                'video_fee'           => 300,
                'in_person_fee'       => 500,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Specialized Heart Center',   // ← matches ClinicSeeder
            ],
            [
                'first_name'          => 'Heba',
                'last_name'           => 'Abdelrahman',
                'email'               => 'dr.heba@gmail.com',
                'phone'               => '01012345602',
                'specialization'      => 'Neurology',
                'about'               => 'Neurologist specializing in epilepsy, stroke rehabilitation, and headache disorders. PhD from Ain Shams University with published international research.',
                'years_of_experience' => 14,
                'video_fee'           => 280,
                'in_person_fee'       => 450,
                'languages'           => ['Arabic', 'English', 'French'],
                'clinic'              => 'Sinai Medical Center',
            ],
            [
                'first_name'          => 'Khaled',
                'last_name'           => 'Mansour',
                'email'               => 'dr.khaled@gmail.com',
                'phone'               => '01123456703',
                'specialization'      => 'Orthopedics',
                'about'               => 'Orthopedic surgeon specialized in joint replacement and sports medicine. Trained at Alexandria University with arthroscopic surgery fellowship in France. Over 2,000 successful surgeries.',
                'years_of_experience' => 16,
                'video_fee'           => 350,
                'in_person_fee'       => 600,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Al Hayah Bone & Joint Center',
            ],
            [
                'first_name'          => 'Reem',
                'last_name'           => 'Ibrahim',
                'email'               => 'dr.reem@gmail.com',
                'phone'               => '01234567804',
                'specialization'      => 'Dermatology',
                'about'               => 'Dermatologist and cosmetologist with 10 years of experience specializing in acne treatment, anti-aging therapies, and laser procedures.',
                'years_of_experience' => 10,
                'video_fee'           => 250,
                'in_person_fee'       => 400,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Beautiful Skin Dermatology Clinic',
            ],
            [
                'first_name'          => 'Ayman',
                'last_name'           => 'Suleiman',
                'email'               => 'dr.ayman@gmail.com',
                'phone'               => '01001234905',
                'specialization'      => 'Pediatrics',
                'about'               => 'Pediatric consultant with 12 years of experience focusing on neonatal care, developmental disorders, and childhood immunization programs.',
                'years_of_experience' => 12,
                'video_fee'           => 200,
                'in_person_fee'       => 350,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Al Shifa Children Clinic',
            ],
            [
                'first_name'          => 'Nadia',
                'last_name'           => 'Othman',
                'email'               => 'dr.nadia@gmail.com',
                'phone'               => '01112345006',
                'specialization'      => 'General Medicine',
                'about'               => 'General practitioner providing comprehensive primary care for all ages. Focused on preventive care and chronic disease management.',
                'years_of_experience' => 8,
                'video_fee'           => 150,
                'in_person_fee'       => 250,
                'languages'           => ['Arabic'],
                'clinic'              => 'Internal Medicine Care Center',
            ],
            [
                'first_name'          => 'Tarek',
                'last_name'           => 'Zaki',
                'email'               => 'dr.tarek@gmail.com',
                'phone'               => '01023456107',
                'specialization'      => 'Psychiatry',
                'about'               => 'Consultant psychiatrist with 15 years of experience in mood disorders, anxiety, and cognitive behavioral therapy. Certified CBT practitioner trained at the American University in Cairo.',
                'years_of_experience' => 15,
                'video_fee'           => 350,
                'in_person_fee'       => 550,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Peace of Mind Psychiatry Clinic',
            ],
            [
                'first_name'          => 'Mona',
                'last_name'           => 'Hamed',
                'email'               => 'dr.mona@gmail.com',
                'phone'               => '01134567208',
                'specialization'      => 'Ophthalmology',
                'about'               => 'Ophthalmologist specializing in retinal diseases, laser vision correction, and cataract surgery. Trained at Mansoura Ophthalmology Center.',
                'years_of_experience' => 11,
                'video_fee'           => 270,
                'in_person_fee'       => 420,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Al Nour Eye Clinic',
            ],
            [
                'first_name'          => 'Omar',
                'last_name'           => 'Farouk',
                'email'               => 'dr.omar@gmail.com',
                'phone'               => '01245678309',
                'specialization'      => 'ENT',
                'about'               => 'ENT specialist with expertise in sinusitis, hearing disorders, and endoscopic sinus surgery. Faculty member at Cairo University. Trained over 50 ENT residents.',
                'years_of_experience' => 13,
                'video_fee'           => 240,
                'in_person_fee'       => 380,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Sinai Medical Center',
            ],
            [
                'first_name'          => 'Samar',
                'last_name'           => 'Al-Gawhari',
                'email'               => 'dr.samar@gmail.com',
                'phone'               => '01056789410',
                'specialization'      => 'Gynecology',
                'about'               => "Consultant obstetrician and gynecologist with 20 years of experience in maternal care, high-risk pregnancies, and minimally invasive gynecological surgery.",
                'years_of_experience' => 20,
                'video_fee'           => 400,
                'in_person_fee'       => 650,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Internal Medicine Care Center',
            ],
            [
                'first_name'          => 'Walid',
                'last_name'           => 'Al-Badri',
                'email'               => 'dr.walid@gmail.com',
                'phone'               => '01167890511',
                'specialization'      => 'Urology',
                'about'               => 'Urologist specializing in kidney stones, prostate diseases, and laparoscopic urological surgery. Completed urology residency in Germany.',
                'years_of_experience' => 9,
                'video_fee'           => 300,
                'in_person_fee'       => 480,
                'languages'           => ['Arabic', 'English', 'German'],
                'clinic'              => 'Future Urology Clinic',
            ],
            [
                'first_name'          => 'Iman',
                'last_name'           => 'Al-Tayyeb',
                'email'               => 'dr.iman@gmail.com',
                'phone'               => '01278901612',
                'specialization'      => 'Endocrinology',
                'about'               => 'Endocrinologist focused on diabetes management, thyroid disorders, and hormonal imbalances. Runs a dedicated diabetes clinic.',
                'years_of_experience' => 12,
                'video_fee'           => 320,
                'in_person_fee'       => 500,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Specialized Heart Center',
            ],
            [
                'first_name'          => 'Hossam',
                'last_name'           => 'El-Din',
                'email'               => 'dr.hossam@gmail.com',
                'phone'               => '01089012713',
                'specialization'      => 'Gastroenterology',
                'about'               => 'Gastroenterologist and hepatologist specializing in liver diseases, inflammatory bowel disease, and endoscopic procedures. Extensive experience treating Hepatitis C.',
                'years_of_experience' => 17,
                'video_fee'           => 330,
                'in_person_fee'       => 520,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Minya Comprehensive Medical Center',
            ],
            [
                'first_name'          => 'Sohair',
                'last_name'           => 'Al-Qadi',
                'email'               => 'dr.sohair@gmail.com',
                'phone'               => '01190123814',
                'specialization'      => 'Pulmonology',
                'about'               => 'Pulmonologist and critical care specialist treating asthma, COPD, interstitial lung diseases, and sleep apnea. Leads the respiratory unit at a major Cairo hospital.',
                'years_of_experience' => 14,
                'video_fee'           => 290,
                'in_person_fee'       => 460,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Sinai Medical Center',
            ],
            [
                'first_name'          => 'Yosra',
                'last_name'           => 'Al-Najjar',
                'email'               => 'dr.yosra@gmail.com',
                'phone'               => '01201234915',
                'specialization'      => 'Rheumatology',
                'about'               => 'Rheumatologist specializing in rheumatoid arthritis, lupus, and autoimmune diseases. Employs the latest biologic therapies to improve patient quality of life.',
                'years_of_experience' => 11,
                'video_fee'           => 310,
                'in_person_fee'       => 490,
                'languages'           => ['Arabic', 'English'],
                'clinic'              => 'Al Hayah Bone & Joint Center',
            ],
        ];

        // Build lookup maps
        $specializationMap = Specialization::all()->mapWithKeys(function ($s) {
            $name = is_array($s->name) ? ($s->name['en'] ?? '') : $s->getTranslation('name', 'en');
            return [$name => $s->id];
        });

        $clinicMap = Clinic::pluck('id', 'name');

        foreach ($doctors as $data) {
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

            $user->syncRoles(['doctor']);

            DoctorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    // IMPORTANT: DoctorProfile uses 'specialty_id', NOT 'specialization_id'
                    'specialization_id' => $specializationMap[$data['specialization']] ?? null,
                    'clinic_id'           => $clinicMap[$data['clinic']] ?? null,
                    'about'               => $data['about'],
                    'years_of_experience' => $data['years_of_experience'],
                    'video_fee'           => $data['video_fee'],
                    'in_person_fee'       => $data['in_person_fee'],
                    'languages'           => $data['languages'],
                    'status'              => 'active',
                ]
            );
        }
    }
}