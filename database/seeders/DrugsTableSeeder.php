<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Drug;

class DrugsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $drugs = [
            // Analgesics
            [
                'name' => 'Paracetamol',
                'category' => 'Analgesics',
                'strength_mg' => '500mg',
                'unit_price' => 0.10,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Pain reliever and fever reducer',
                    'side_effects' => 'Liver damage in high doses',
                    'contraindications' => 'Severe liver disease'
                ]
            ],
            [
                'name' => 'Ibuprofen',
                'category' => 'Analgesics',
                'strength_mg' => '200mg',
                'unit_price' => 0.15,
                'is_controlled' => false,
                'details' => [
                    'description' => 'NSAID for pain, fever, and inflammation',
                    'side_effects' => 'Stomach upset, heartburn',
                    'contraindications' => 'Stomach ulcers, severe heart disease'
                ]
            ],
            [
                'name' => 'Aspirin',
                'category' => 'Analgesics',
                'strength_mg' => '100mg',
                'unit_price' => 0.12,
                'is_controlled' => false,
                'details' => [
                    'description' => 'NSAID with antiplatelet properties',
                    'side_effects' => 'Stomach irritation, bleeding',
                    'contraindications' => 'Bleeding disorders, asthma'
                ]
            ],
            [
                'name' => 'Morphine',
                'category' => 'Analgesics',
                'strength_mg' => '10mg',
                'unit_price' => 2.50,
                'is_controlled' => true,
                'details' => [
                    'description' => 'Opioid pain medication for severe pain',
                    'side_effects' => 'Drowsiness, constipation, respiratory depression',
                    'contraindications' => 'Severe respiratory depression, acute asthma'
                ]
            ],
            
            // Antibiotics
            [
                'name' => 'Amoxicillin',
                'category' => 'Antibiotics',
                'strength_mg' => '500mg',
                'unit_price' => 0.25,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Penicillin antibiotic for bacterial infections',
                    'side_effects' => 'Diarrhea, rash, nausea',
                    'contraindications' => 'Penicillin allergy'
                ]
            ],
            [
                'name' => 'Azithromycin',
                'category' => 'Antibiotics',
                'strength_mg' => '250mg',
                'unit_price' => 0.75,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Macrolide antibiotic for respiratory infections',
                    'side_effects' => 'Nausea, diarrhea, abdominal pain',
                    'contraindications' => 'Liver disease, QT prolongation'
                ]
            ],
            [
                'name' => 'Ciprofloxacin',
                'category' => 'Antibiotics',
                'strength_mg' => '500mg',
                'unit_price' => 0.60,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Fluoroquinolone antibiotic for various infections',
                    'side_effects' => 'Nausea, diarrhea, tendon problems',
                    'contraindications' => 'Tendon disorders, pregnancy'
                ]
            ],
            
            // Antihypertensives
            [
                'name' => 'Lisinopril',
                'category' => 'Antihypertensives',
                'strength_mg' => '10mg',
                'unit_price' => 0.50,
                'is_controlled' => false,
                'details' => [
                    'description' => 'ACE inhibitor for high blood pressure',
                    'side_effects' => 'Dry cough, dizziness, headache',
                    'contraindications' => 'Pregnancy, angioedema history'
                ]
            ],
            [
                'name' => 'Amlodipine',
                'category' => 'Antihypertensives',
                'strength_mg' => '5mg',
                'unit_price' => 0.40,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Calcium channel blocker for hypertension',
                    'side_effects' => 'Swelling in legs, dizziness, flushing',
                    'contraindications' => 'Severe hypotension, cardiogenic shock'
                ]
            ],
            [
                'name' => 'Losartan',
                'category' => 'Antihypertensives',
                'strength_mg' => '50mg',
                'unit_price' => 0.70,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Angiotensin II receptor blocker for hypertension',
                    'side_effects' => 'Dizziness, fatigue, upper respiratory infection',
                    'contraindications' => 'Pregnancy, severe hepatic impairment'
                ]
            ],
            
            // Antidiabetics
            [
                'name' => 'Metformin',
                'category' => 'Antidiabetics',
                'strength_mg' => '500mg',
                'unit_price' => 0.25,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Biguanide for type 2 diabetes management',
                    'side_effects' => 'Nausea, diarrhea, stomach upset',
                    'contraindications' => 'Kidney disease, heart failure'
                ]
            ],
            [
                'name' => 'Insulin Glargine',
                'category' => 'Antidiabetics',
                'strength_mg' => '100mg',
                'unit_price' => 3.50,
                'is_controlled' => true,
                'details' => [
                    'description' => 'Long-acting insulin for diabetes',
                    'side_effects' => 'Hypoglycemia, injection site reactions',
                    'contraindications' => 'Hypoglycemia, diabetic ketoacidosis'
                ]
            ],
            [
                'name' => 'Glimepiride',
                'category' => 'Antidiabetics',
                'strength_mg' => '2mg',
                'unit_price' => 0.45,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Sulfonylurea for type 2 diabetes',
                    'side_effects' => 'Hypoglycemia, weight gain',
                    'contraindications' => 'Type 1 diabetes, diabetic ketoacidosis'
                ]
            ],
            
            // Cardiovascular
            [
                'name' => 'Atorvastatin',
                'category' => 'Cardiovascular',
                'strength_mg' => '20mg',
                'unit_price' => 0.75,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Statin for lowering cholesterol',
                    'side_effects' => 'Muscle pain, liver problems',
                    'contraindications' => 'Liver disease, pregnancy'
                ]
            ],
            [
                'name' => 'Clopidogrel',
                'category' => 'Cardiovascular',
                'strength_mg' => '75mg',
                'unit_price' => 1.20,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Antiplatelet for preventing blood clots',
                    'side_effects' => 'Bleeding, bruising',
                    'contraindications' => 'Active bleeding, severe liver disease'
                ]
            ],
            
            // Respiratory
            [
                'name' => 'Salbutamol',
                'category' => 'Respiratory',
                'strength_mg' => '100mg',
                'unit_price' => 0.35,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Bronchodilator for asthma and COPD',
                    'side_effects' => 'Tremor, nervousness, headache',
                    'contraindications' => 'Hypersensitivity to sympathomimetics'
                ]
            ],
            [
                'name' => 'Fluticasone',
                'category' => 'Respiratory',
                'strength_mg' => '50mg',
                'unit_price' => 1.50,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Corticosteroid inhaler for asthma',
                    'side_effects' => 'Oral thrush, hoarse voice',
                    'contraindications' => 'Hypersensitivity to corticosteroids'
                ]
            ],
            
            // Neurological
            [
                'name' => 'Levothyroxine',
                'category' => 'Endocrine',
                'strength_mg' => '50mg',
                'unit_price' => 0.40,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Thyroid hormone replacement',
                    'side_effects' => 'Palpitations, weight loss, insomnia',
                    'contraindications' => 'Untreated adrenal insufficiency'
                ]
            ],
            [
                'name' => 'Sertraline',
                'category' => 'Antidepressants',
                'strength_mg' => '50mg',
                'unit_price' => 0.80,
                'is_controlled' => false,
                'details' => [
                    'description' => 'SSRI antidepressant',
                    'side_effects' => 'Nausea, diarrhea, insomnia',
                    'contraindications' => 'MAO inhibitor use within 14 days'
                ]
            ],
            [
                'name' => 'Loratadine',
                'category' => 'Antihistamines',
                'strength_mg' => '10mg',
                'unit_price' => 0.20,
                'is_controlled' => false,
                'details' => [
                    'description' => 'Non-drowsy antihistamine for allergies',
                    'side_effects' => 'Headache, dry mouth',
                    'contraindications' => 'Hypersensitivity to loratadine'
                ]
            ]
        ];

        foreach ($drugs as $drug) {
            Drug::updateOrCreate(
                ['name' => $drug['name'], 'strength_mg' => $drug['strength_mg']],
                $drug
            );
        }
    }
}