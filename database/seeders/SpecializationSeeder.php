<?php

namespace Database\Seeders;

use App\Models\Specialization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SpecializationSeeder extends Seeder
{
    public function run(): void
    {
        $specializations = [
            [
                'name'        => ['en' => 'Cardiology',       'ar' => 'أمراض القلب'],
                'description' => 'Diagnosis and treatment of heart and cardiovascular diseases including heart failure, coronary artery disease, and arrhythmias.',
                'icon'        => 'cardiology_1_.png',
            ],
            [
                'name'        => ['en' => 'Neurology',        'ar' => 'الأعصاب'],
                'description' => 'Specialist in disorders of the nervous system including brain, spinal cord, and peripheral nerves.',
                'icon'        => 'neurology.png',
            ],
            [
                'name'        => ['en' => 'Orthopedics',      'ar' => 'العظام'],
                'description' => 'Treatment of musculoskeletal system disorders including bones, joints, ligaments, tendons, and muscles.',
                'icon'        => 'orthopedics.png',
            ],
            [
                'name'        => ['en' => 'Dermatology',      'ar' => 'الجلدية'],
                'description' => 'Diagnosis and treatment of skin, hair, and nail conditions including acne, eczema, psoriasis, and skin cancer.',
                'icon'        => 'dermatology.png',
            ],
            [
                'name'        => ['en' => 'Pediatrics',       'ar' => 'الأطفال'],
                'description' => 'Medical care for infants, children, and adolescents covering growth, development, and childhood diseases.',
                'icon'        => 'pediatric.png',
            ],
            [
                'name'        => ['en' => 'General Medicine', 'ar' => 'الطب العام'],
                'description' => 'Primary care for a wide range of common illnesses and preventive health services for all age groups.',
                'icon'        => 'first-aid-kit.png',
            ],
            [
                'name'        => ['en' => 'Psychiatry',       'ar' => 'الطب النفسي'],
                'description' => 'Diagnosis and treatment of mental health disorders including depression, anxiety, bipolar disorder, and schizophrenia.',
                'icon'        => 'dissociation.png',
            ],
            [
                'name'        => ['en' => 'Ophthalmology',    'ar' => 'العيون'],
                'description' => 'Eye care including diagnosis and treatment of vision disorders, cataracts, glaucoma, and retinal diseases.',
                'icon'        => 'eye-test.png',
            ],
            [
                'name'        => ['en' => 'ENT',              'ar' => 'الأنف والأذن والحنجرة'],
                'description' => 'Treatment of disorders of the ear, nose, throat, and related structures of the head and neck.',
                'icon'        => 'sore-throat.png',
            ],
            [
                'name'        => ['en' => 'Gynecology',       'ar' => 'النساء والتوليد'],
                'description' => "Women's reproductive health including pregnancy, childbirth, menstrual disorders, and gynecological conditions.",
                'icon'        => 'maternity.png',
            ],
            [
                'name'        => ['en' => 'Urology',          'ar' => 'المسالك البولية'],
                'description' => 'Treatment of urinary tract disorders and male reproductive system conditions including kidney stones and prostate issues.',
                'icon'        => 'urology.png',
            ],
            [
                'name'        => ['en' => 'Endocrinology',    'ar' => 'الغدد الصماء'],
                'description' => 'Management of hormonal disorders including diabetes, thyroid diseases, adrenal, and pituitary gland conditions.',
                'icon'        => 'endocrine.png',
            ],
            [
                'name'        => ['en' => 'Gastroenterology', 'ar' => 'الجهاز الهضمي'],
                'description' => 'Diagnosis and treatment of digestive system disorders including stomach, intestines, liver, and pancreas.',
                'icon'        => 'gastroenterology.png',
            ],
            [
                'name'        => ['en' => 'Pulmonology',      'ar' => 'الأمراض الصدرية'],
                'description' => 'Specialist in respiratory diseases including asthma, COPD, pneumonia, lung cancer, and sleep apnea.',
                'icon'        => 'pulmonology.png',
            ],
            [
                'name'        => ['en' => 'Rheumatology',     'ar' => 'الروماتيزم'],
                'description' => 'Treatment of autoimmune and inflammatory diseases affecting joints, muscles, and connective tissues.',
                'icon'        => 'rheumatology.png',
            ],
        ];

        foreach ($specializations as $data) {
            // name is a JSON translatable column
            $record = Specialization::where('name->en', $data['name']['en'])->first();

            if ($record) {
                $record->update([
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'status'      => 'active',
                ]);
            } else {
                $record = Specialization::create([
                    'name'        => $data['name'],
                    'description' => $data['description'],
                    'status'      => 'active',
                ]);
            }

            // Attach icon via Spatie Media Library
            // Media collection name is 'specialty_icon' (defined in registerMediaCollections)
            // Icons must be copied to storage/app/public/specializations/ first:
            //   php artisan storage:link
            //   cp resources/images/specializations/*.png storage/app/public/specializations/
            $iconSourcePath = public_path('images/specializations/' . $data['icon']);

            if (File::exists($iconSourcePath) && ! $record->hasMedia('specialty_icon')) {
                $record->addMedia($iconSourcePath)
                       ->preservingOriginal()
                       ->toMediaCollection('specialty_icon');
            }
        }
    }
}