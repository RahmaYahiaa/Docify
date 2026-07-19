<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    public function run(): void
    {
        $medications = [

            // Pain & Fever
            'Paracetamol',
            'Brufen (Ibuprofen)',
            'Ketofan (Ketoprofen)',
            'Ketolac (Ketorolac)',
            'Cataflam (Diclofenac Potassium)',
            'Voltaren (Diclofenac)',
            'Zipurim',

            // Antibiotics
            'Amoxicillin',
            'Augmentin (Amoxicillin/Clavulanate)',
            'Flagyl (Metronidazole)',
            'Zithromax (Azithromycin)',
            'Ciprofloxacin',
            'Doxycycline',
            'Clarithromycin',

            // Stomach & Digestive
            'Omeprazole',
            'Losec (Omeprazole)',
            'Gastritol',
            'SpasmoDigestin',
            'Antinal (Nifuroxazide)',
            'Buscopan (Hyoscine Butylbromide)',
            'Esomeprazole',
            'Lactobacillus Supplement',

            // Heart & Blood Pressure
            'Concor (Bisoprolol)',
            'Lisinopril',
            'Amlodipine',
            'Atorvastatin',
            'Aspirin Cardio',
            'Clopidogrel',
            'Amiodarone',

            // Diabetes
            'Metformin',
            'Glucophage (Metformin)',
            'Glibenclamide',
            'Insulin',
            'Saxagliptin',

            // Allergy & Respiratory
            'Claritine (Loratadine)',
            'Telfast (Fexofenadine)',
            'Congestal',
            'Prednisolone',
            'Salbutamol',
            'Montelukast',
            'Fluticasone',

            // Vitamins & Supplements
            'Vitamin D3',
            'Calcium + D3',
            'Iron Supplement',
            'Omega-3',
            'Multivitamin',
            'Vitamin B12',
            'Folic Acid',

            // Thyroid
            'Eltroxin (Levothyroxine)',
            'Levothyroxine',

            // Mental Health
            'Seroxat (Paroxetine)',
            'Lexotanil (Bromazepam)',
            'Xanax (Alprazolam)',
            'Sertraline',
            'Escitalopram',

            // Topical
            'Fucidin Cream (Fusidic Acid)',
            'Betamethasone Cream',
            'Ketoconazole Shampoo',
        ];

        foreach ($medications as $name) {
            Medication::firstOrCreate([
                'name' => $name
            ]);
        }
    }
}