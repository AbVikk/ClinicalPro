<?php

namespace App\Services;

use App\Models\PrescriptionItem;
use App\Models\Drug;
use App\Models\InventoryPrediction;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryAiService
{
    protected $aiAssistantService;

    public function __construct(AiAssistantService $aiAssistantService)
    {
        $this->aiAssistantService = $aiAssistantService;
    }

    /**
     * Generate inventory predictions using AI analysis of prescription history
     *
     * @param int $days Number of days to look back for data analysis
     * @return array
     */
    public function generateInventoryPredictions(int $days = 365)
    {
        try {
            // Fetch prescription data for the specified period
            $startDate = Carbon::now()->subDays($days);
            $prescriptionItems = PrescriptionItem::with(['drug', 'prescription'])
                ->whereHas('prescription', function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                })
                ->get();
            
            if ($prescriptionItems->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'No prescription data found for the specified period.',
                    'predictions' => []
                ];
            }
            
            // Group data by drug
            $drugUsage = [];
            foreach ($prescriptionItems as $item) {
                $drugName = $item->drug->name ?? 'Unknown Drug';
                if (!isset($drugUsage[$drugName])) {
                    $drugUsage[$drugName] = [
                        'total_quantity' => 0,
                        'prescription_count' => 0,
                        'drug' => $item->drug
                    ];
                }
                
                $drugUsage[$drugName]['total_quantity'] += $item->quantity ?? 0;
                $drugUsage[$drugName]['prescription_count']++;
            }
            
            // Prepare data for AI analysis
            $analysisData = [
                'period_days' => $days,
                'period_start' => $startDate->format('Y-m-d'),
                'period_end' => Carbon::now()->format('Y-m-d'),
                'drug_usage' => $drugUsage
            ];
            
            // Generate predictions using AI
            $predictions = $this->generatePredictionsWithAI($analysisData);
            
            return [
                'success' => true,
                'message' => 'Inventory predictions generated successfully',
                'predictions' => $predictions
            ];
        } catch (\Exception $e) {
            Log::error('Error generating inventory predictions: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error generating inventory predictions: ' . $e->getMessage(),
                'predictions' => []
            ];
        }
    }
    
    /**
     * Generate predictions using AI service
     *
     * @param array $data
     * @return array
     */
    private function generatePredictionsWithAI(array $data)
    {
        // Prepare the prompt for AI analysis
        $prompt = $this->prepareAIPrompt($data);
        
        // Get AI response using the medical query method
        $response = $this->aiAssistantService->getMedicalQueryResponse($prompt);
        
        // Try to parse the response as JSON
        $predictions = $this->parseAIResponse($response);
        
        if (!$predictions) {
            Log::warning('Could not parse AI response, using fallback method.');
            $predictions = $this->generateFallbackPredictions($data);
        }
        
        return $predictions;
    }
    
    /**
     * Prepare the prompt for AI analysis
     *
     * @param array $data
     * @return string
     */
    private function prepareAIPrompt(array $data)
    {
        $drugInfo = [];
        foreach ($data['drug_usage'] as $drugName => $usage) {
            $drugInfo[] = [
                'name' => $drugName,
                'total_quantity' => $usage['total_quantity'],
                'prescription_count' => $usage['prescription_count']
            ];
        }
        
        $prompt = "You are a Pharmaceutical Inventory Management System. Analyze the following pharmaceutical usage data and provide inventory predictions for the next 12 months.
        
Data Period: {$data['period_days']} days ({$data['period_start']} to {$data['period_end']})
        
Drug Usage Data:
" . json_encode($drugInfo, JSON_PRETTY_PRINT) . "

INSTRUCTIONS:
1. Analyze seasonal patterns (flu season, etc.)
2. Consider prescription trends and growth
3. Account for drug expiration dates
4. Provide monthly quantity predictions for each drug
5. Highlight drugs at risk of stockout or expiration
6. Suggest reorder points and quantities

RESPONSE FORMAT (JSON ONLY):
{
  \"predictions\": [
    {
      \"drug_name\": \"string\",
      \"monthly_predictions\": [
        {
          \"month\": \"string\",
          \"year\": \"int\",
          \"predicted_quantity\": \"int\",
          \"reorder_point\": \"int\",
          \"recommended_order_quantity\": \"int\"
        }
      ],
      \"risk_factors\": [\"string\"],
      \"notes\": \"string\"
    }
  ],
  \"summary\": {
    \"high_risk_drugs\": [\"string\"],
    \"opportunities_to_reduce_waste\": [\"string\"],
    \"total_estimated_value_at_risk\": \"float\"
  }
}";

        return $prompt;
    }
    
    /**
     * Parse AI response
     *
     * @param string $response
     * @return array|null
     */
    private function parseAIResponse(string $response)
    {
        // Clean the response to extract JSON
        $cleanResponse = trim($response);
        $cleanResponse = preg_replace('/^```[a-z]*\s*/i', '', $cleanResponse);
        $cleanResponse = trim(preg_replace('/\s*```$/', '', $cleanResponse));
        
        $parsed = json_decode($cleanResponse, true);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $parsed;
        }
        
        return null;
    }
    
    /**
     * Generate fallback predictions if AI fails
     *
     * @param array $data
     * @return array
     */
    private function generateFallbackPredictions(array $data)
    {
        $predictions = [
            'predictions' => [],
            'summary' => [
                'high_risk_drugs' => [],
                'opportunities_to_reduce_waste' => [],
                'total_estimated_value_at_risk' => 0
            ]
        ];
        
        foreach ($data['drug_usage'] as $drugName => $usage) {
            // Simple projection: assume 10% growth per month
            $avgMonthlyUsage = ($usage['total_quantity'] / ($data['period_days'] / 30));
            $monthlyPredictions = [];
            
            for ($i = 1; $i <= 12; $i++) {
                $date = Carbon::now()->addMonths($i);
                $predictedQuantity = round($avgMonthlyUsage * (1 + (0.1 * $i))); // 10% growth per month
                $reorderPoint = round($predictedQuantity * 0.5); // 50% of predicted quantity
                $recommendedOrder = round($predictedQuantity * 1.2); // 20% buffer
                
                $monthlyPredictions[] = [
                    'month' => $date->format('F'),
                    'year' => $date->year,
                    'predicted_quantity' => $predictedQuantity,
                    'reorder_point' => $reorderPoint,
                    'recommended_order_quantity' => $recommendedOrder
                ];
            }
            
            $predictions['predictions'][] = [
                'drug_name' => $drugName,
                'monthly_predictions' => $monthlyPredictions,
                'risk_factors' => ['AI analysis failed - using fallback projection'],
                'notes' => 'Based on historical usage with 10% monthly growth projection'
            ];
        }
        
        return $predictions;
    }
    
    /**
     * Store predictions in database
     *
     * @param array $predictions
     * @return bool
     */
    public function storePredictions(array $predictions)
    {
        try {
            // Clear existing predictions
            InventoryPrediction::truncate();
            
            // Store new predictions
            if (isset($predictions['predictions'])) {
                foreach ($predictions['predictions'] as $prediction) {
                    foreach ($prediction['monthly_predictions'] as $monthlyPrediction) {
                        InventoryPrediction::create([
                            'drug_name' => $prediction['drug_name'],
                            'month' => $monthlyPrediction['month'],
                            'year' => $monthlyPrediction['year'],
                            'predicted_quantity' => $monthlyPrediction['predicted_quantity'],
                            'reorder_point' => $monthlyPrediction['reorder_point'],
                            'recommended_order_quantity' => $monthlyPrediction['recommended_order_quantity'],
                            'risk_factors' => $prediction['risk_factors'],
                            'notes' => $prediction['notes'],
                            'generated_at' => Carbon::now(),
                        ]);
                    }
                }
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Error storing inventory predictions: ' . $e->getMessage());
            return false;
        }
    }
}