<?php

namespace Database\Seeders;

use App\Models\ChronicCondition;
use Illuminate\Database\Seeder;

class ChronicConditionSeeder extends Seeder
{
    public function run(): void
    {
        $conditions = [
            'Diabetes Type 1',
            'Diabetes Type 2',
            'Hypertension',
            'Hypotension',
            'Asthma',
            'Heart Disease',
            'Chronic Kidney Disease',
            'Chronic Liver Disease',
            'Thyroid Disorders',
            'Arthritis',
            'Rheumatoid Arthritis',
            'Osteoporosis',
            'COPD',
            'Epilepsy',
            'Cancer',
            'Depression',
            'Anxiety Disorders',
            'Bipolar Disorder',
            'Autoimmune Diseases',
            'HIV/AIDS',
            'Obesity',
            'High Cholesterol',
            'Stroke',
            'Migraine',
            'Multiple Sclerosis (MS)',
            "Alzheimer's Disease",
            'Irritable Bowel Syndrome (IBS)',
            'Psoriasis',
            'Sleep Apnea',
            'Hepatitis C',
            'Hepatitis B',
            'Anemia',
            'Vitamin D Deficiency',
            'Gout',
        ];

        foreach ($conditions as $condition) {
            ChronicCondition::firstOrCreate(['name' => $condition]);
        }
    }
}