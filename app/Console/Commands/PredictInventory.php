<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\InventoryAiService;
use Illuminate\Support\Facades\Log;

class PredictInventory extends Command
{
    /**
     * The name and signature of the console command.
     * Run this using: php artisan inventory:predict
     */
    protected $signature = 'inventory:predict {days=365 : Days of history to analyze}';

    /**
     * The console command description.
     */
    protected $description = 'Analyze pharmacy sales history and generate stock predictions using Gemini AI';

    protected $aiService;

    public function __construct(InventoryAiService $aiService)
    {
        parent::__construct();
        $this->aiService = $aiService;
    }

    public function handle()
    {
        $this->info('🧠 Connecting to Google Gemini for Inventory Analysis...');
        
        try {
            $days = (int) $this->argument('days');
            
            // 1. Generate Predictions
            $result = $this->aiService->generateInventoryPredictions($days);
            
            if (!$result['success']) {
                $this->error('Analysis Failed: ' . $result['message']);
                Log::error('AI Inventory Prediction Failed: ' . $result['message']);
                return 1;
            }
            
            // 2. Save to Database
            $saved = $this->aiService->storePredictions($result['predictions']);
            
            if ($saved) {
                $this->info('✅ AI Predictions generated and saved successfully!');
                
                // Show a quick preview in the terminal
                $headers = ['Drug', 'Predicted Qty', 'Reorder Point'];
                $data = [];
                
                if (isset($result['predictions']['predictions'])) {
                    foreach (array_slice($result['predictions']['predictions'], 0, 5) as $p) {
                        $data[] = [
                            $p['drug_name'],
                            $p['monthly_predictions'][0]['predicted_quantity'] ?? 0,
                            $p['monthly_predictions'][0]['reorder_point'] ?? 0,
                        ];
                    }
                }
                
                $this->table($headers, $data);
                return 0;
            } else {
                $this->error('Failed to save predictions to database.');
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('Critical Error: ' . $e->getMessage());
            Log::error('AI Prediction Crash: ' . $e->getMessage());
            return 1;
        }
    }
}