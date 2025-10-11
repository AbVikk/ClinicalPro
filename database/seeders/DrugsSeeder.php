<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drug;

class DrugsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample drugs
        Drug::create([
            'name' => 'Lisinopril',
            'category' => 'Antihypertensives',
            'strength_mg' => 10,
            'unit_price' => 0.50,
            'is_controlled' => false,
            'details' => [
                'description' => 'ACE inhibitor used to treat high blood pressure',
                'side_effects' => 'Dizziness, headache, dry cough',
                'contraindications' => 'Pregnancy, angioedema history'
            ]
        ]);

        Drug::create([
            'name' => 'Metformin',
            'category' => 'Antidiabetics',
            'strength_mg' => 500,
            'unit_price' => 0.25,
            'is_controlled' => false,
            'details' => [
                'description' => 'Biguanide used to treat type 2 diabetes',
                'side_effects' => 'Nausea, diarrhea, stomach upset',
                'contraindications' => 'Kidney disease, heart failure'
            ]
        ]);

        Drug::create([
            'name' => 'Atorvastatin',
            'category' => 'Lipid Regulators',
            'strength_mg' => 20,
            'unit_price' => 0.75,
            'is_controlled' => false,
            'details' => [
                'description' => 'Statin used to lower cholesterol',
                'side_effects' => 'Muscle pain, liver problems',
                'contraindications' => 'Liver disease, pregnancy'
            ]
        ]);

        Drug::create([
            'name' => 'Hydrochlorothiazide',
            'category' => 'Diuretics',
            'strength_mg' => 12.5,
            'unit_price' => 0.30,
            'is_controlled' => false,
            'details' => [
                'description' => 'Thiazide diuretic used to treat high blood pressure',
                'side_effects' => 'Dizziness, increased urination',
                'contraindications' => 'Severe kidney disease, anuria'
            ]
        ]);
    }
}