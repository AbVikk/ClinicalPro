<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PrescriptionItem;
use App\Models\Drug;
use App\Models\InventoryPrediction;
use App\Services\InventoryAiService;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GenerateInventoryPredictions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:predict {--days=365 : Number of days to look back for data analysis}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate AI-powered inventory predictions using prescription history';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(InventoryAiService $inventoryAiService)
    {
        $this->info('Starting inventory prediction generation...');
        
        try {
            // Get the number of days to look back
            $days = $this->option('days');
            
            // Generate predictions using the inventory AI service
            $result = $inventoryAiService->generateInventoryPredictions($days);
            
            if (!$result['success']) {
                $this->warn($result['message']);
                return 0;
            }
            
            // Store predictions in database
            $stored = $inventoryAiService->storePredictions($result['predictions']);
            
            if ($stored) {
                $this->info('Inventory predictions generated and stored successfully!');
            } else {
                $this->error('Failed to store inventory predictions.');
                return 1;
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::error('Error generating inventory predictions: ' . $e->getMessage());
            $this->error('Error generating inventory predictions: ' . $e->getMessage());
            return 1;
        }
    }
}