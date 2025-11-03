<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugCategory;

class MedicationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Medication types/categories that are specifically used for the Medication model
        // These may be different from DrugCategory but we'll ensure they're consistent
        $medicationTypes = [
            ['name' => 'Analgesic', 'description' => 'Pain relief medications'],
            ['name' => 'Antibiotic', 'description' => 'Medications that fight bacterial infections'],
            ['name' => 'Antiviral', 'description' => 'Medications that fight viral infections'],
            ['name' => 'Antifungal', 'description' => 'Medications that fight fungal infections'],
            ['name' => 'Antihypertensive', 'description' => 'Medications that lower blood pressure'],
            ['name' => 'Antidiabetic', 'description' => 'Medications that manage blood sugar levels'],
            ['name' => 'Antidepressant', 'description' => 'Medications that treat depression'],
            ['name' => 'Antihistamine', 'description' => 'Medications that counteract histamine'],
            ['name' => 'Bronchodilator', 'description' => 'Medications that open airways'],
            ['name' => 'Diuretic', 'description' => 'Medications that increase urine production'],
            ['name' => 'Anticoagulant', 'description' => 'Blood thinners that prevent clot formation'],
            ['name' => 'Antiplatelet', 'description' => 'Medications that prevent platelet aggregation'],
            ['name' => 'Anticonvulsant', 'description' => 'Medications that prevent or reduce seizures'],
            ['name' => 'Antipsychotic', 'description' => 'Medications that treat psychosis and schizophrenia'],
            ['name' => 'Anxiolytic', 'description' => 'Medications that reduce anxiety'],
            ['name' => 'Sedative', 'description' => 'Medications that calm and induce sleep'],
            ['name' => 'Stimulant', 'description' => 'Medications that increase alertness and energy'],
            ['name' => 'Hormone', 'description' => 'Medications that replace or regulate hormones'],
            ['name' => 'Immunosuppressant', 'description' => 'Medications that suppress the immune system'],
            ['name' => 'Vaccine', 'description' => 'Biological preparations that provide immunity'],
            ['name' => 'Gastrointestinal', 'description' => 'Medications for digestive system disorders'],
            ['name' => 'Cardiovascular', 'description' => 'Medications for heart and blood vessel conditions'],
            ['name' => 'Respiratory', 'description' => 'Medications for lung and breathing conditions'],
            ['name' => 'Neurological', 'description' => 'Medications for nervous system disorders'],
            ['name' => 'Endocrine', 'description' => 'Medications for hormone-related conditions'],
            ['name' => 'Supplement', 'description' => 'Vitamins, minerals, and other nutritional supplements'],
            ['name' => 'Topical', 'description' => 'Medications applied to the skin'],
            ['name' => 'Ophthalmic', 'description' => 'Medications for eye conditions'],
            ['name' => 'Otic', 'description' => 'Medications for ear conditions'],
            ['name' => 'Other', 'description' => 'Other types of medications']
        ];

        // Ensure we have medication types in the database
        foreach ($medicationTypes as $type) {
            DrugCategory::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}