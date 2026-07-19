<?php

namespace Database\Seeders;

use App\Models\Allergy;
use Illuminate\Database\Seeder;

class AllergySeeder extends Seeder
{
    public function run(): void
    {
        $allergies = [
            // Food allergies
            'Peanuts',
            'Tree Nuts',
            'Milk',
            'Dairy Products',
            'Seafood',
            'Shrimp',
            'Fish',
            'Wheat',
            'Soy',
            'Gluten',
            'Eggs',
            'Sesame',

            // Drug allergies
            'Penicillin',
            'Amoxicillin',
            'Sulfa Drugs',
            'Aspirin',
            'NSAIDs',
            'Ibuprofen',
            'Codeine',
            'Anticonvulsants',
            'Contrast Dye (Radiology)',
            'Cephalosporins',

            // Environmental / Contact
            'Latex',
            'Insect Stings',
            'Bee Venom',
            'Fragrances',
            'Nickel',
            'Dust Mites',
            'Pollen',
            'Mold',
            'Animal Dander',
            'Cold Allergy',
            'Sun/UV Allergy',
        ];

        foreach ($allergies as $allergy) {
            Allergy::firstOrCreate(['name' => $allergy]);
        }
    }
}