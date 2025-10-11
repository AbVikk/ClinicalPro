<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugCategory;

class DrugCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Analgesic', 'description' => 'Pain relief medications'],
            ['name' => 'Antibiotic', 'description' => 'Medications that fight bacterial infections'],
            ['name' => 'Antiviral', 'description' => 'Medications that fight viral infections'],
            ['name' => 'Antifungal', 'description' => 'Medications that fight fungal infections'],
            ['name' => 'Antihypertensive', 'description' => 'Medications that lower blood pressure'],
            ['name' => 'Antidepressant', 'description' => 'Medications that treat depression'],
            ['name' => 'Antihistamine', 'description' => 'Medications that counteract histamine'],
            ['name' => 'Bronchodilator', 'description' => 'Medications that open airways'],
            ['name' => 'Diuretic', 'description' => 'Medications that increase urine production'],
            ['name' => 'NSAID', 'description' => 'Non-steroidal anti-inflammatory drugs'],
            ['name' => 'Opioid', 'description' => 'Strong pain relief medications'],
            ['name' => 'Benzodiazepine', 'description' => 'Medications for anxiety and sleep disorders'],
            ['name' => 'Stimulant', 'description' => 'Medications that increase alertness'],
            ['name' => 'Antipsychotic', 'description' => 'Medications that treat psychosis'],
            ['name' => 'Immunosuppressant', 'description' => 'Medications that suppress the immune system'],
        ];

        foreach ($categories as $category) {
            DrugCategory::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}