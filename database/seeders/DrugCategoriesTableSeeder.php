<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugCategory;

class DrugCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Analgesics',
                'description' => 'Pain relief medications including NSAIDs and opioids'
            ],
            [
                'name' => 'Antibiotics',
                'description' => 'Medications that fight bacterial infections'
            ],
            [
                'name' => 'Antivirals',
                'description' => 'Medications that fight viral infections'
            ],
            [
                'name' => 'Antifungals',
                'description' => 'Medications that fight fungal infections'
            ],
            [
                'name' => 'Antihypertensives',
                'description' => 'Medications that lower blood pressure'
            ],
            [
                'name' => 'Antidiabetics',
                'description' => 'Medications that manage blood sugar levels'
            ],
            [
                'name' => 'Antidepressants',
                'description' => 'Medications that treat depression and mood disorders'
            ],
            [
                'name' => 'Antihistamines',
                'description' => 'Medications that counteract histamine for allergies'
            ],
            [
                'name' => 'Bronchodilators',
                'description' => 'Medications that open airways for respiratory conditions'
            ],
            [
                'name' => 'Diuretics',
                'description' => 'Medications that increase urine production to reduce fluid retention'
            ],
            [
                'name' => 'Anticoagulants',
                'description' => 'Blood thinners that prevent clot formation'
            ],
            [
                'name' => 'Antiplatelets',
                'description' => 'Medications that prevent platelet aggregation'
            ],
            [
                'name' => 'Anticonvulsants',
                'description' => 'Medications that prevent or reduce seizures'
            ],
            [
                'name' => 'Antipsychotics',
                'description' => 'Medications that treat psychosis and schizophrenia'
            ],
            [
                'name' => 'Anxiolytics',
                'description' => 'Medications that reduce anxiety and promote relaxation'
            ],
            [
                'name' => 'Sedatives',
                'description' => 'Medications that calm and induce sleep'
            ],
            [
                'name' => 'Stimulants',
                'description' => 'Medications that increase alertness and energy'
            ],
            [
                'name' => 'Hormones',
                'description' => 'Medications that replace or regulate hormones'
            ],
            [
                'name' => 'Immunosuppressants',
                'description' => 'Medications that suppress the immune system'
            ],
            [
                'name' => 'Vaccines',
                'description' => 'Biological preparations that provide immunity'
            ],
            [
                'name' => 'Gastrointestinal',
                'description' => 'Medications for digestive system disorders'
            ],
            [
                'name' => 'Cardiovascular',
                'description' => 'Medications for heart and blood vessel conditions'
            ],
            [
                'name' => 'Respiratory',
                'description' => 'Medications for lung and breathing conditions'
            ],
            [
                'name' => 'Neurological',
                'description' => 'Medications for nervous system disorders'
            ],
            [
                'name' => 'Endocrine',
                'description' => 'Medications for hormone-related conditions'
            ]
        ];

        foreach ($categories as $category) {
            DrugCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}