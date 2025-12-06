<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DrugBatch;
use App\Models\Drug;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DrugBatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all drugs
        $drugs = Drug::all();
        
        if ($drugs->isEmpty()) {
            echo "No drugs found. Please run DrugsTableSeeder first.\n";
            return;
        }
        
        // Create batches for each drug
        foreach ($drugs as $drug) {
            // Create 2-3 batches for each drug
            $batchCount = rand(2, 3);
            
            for ($i = 1; $i <= $batchCount; $i++) {
                DrugBatch::updateOrCreate(
                    [
                        'batch_uuid' => 'BATCH' . substr(Str::uuid(), 0, 20), // Shortened UUID
                        'drug_id' => $drug->id,
                    ],
                    [
                        'supplier_id' => null,
                        'received_quantity' => rand(50, 500),
                        'expiry_date' => Carbon::now()->addMonths(rand(6, 24)),
                    ]
                );
            }
        }
        
        echo "Created drug batches for " . $drugs->count() . " drugs.\n";
    }
}