<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AiAssistantService;
use Gemini\Client as GeminiClient;
use Gemini\Factory as GeminiFactory;

class TestAiService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-ai-service';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the AI service to check if it\'s working correctly';

    /**
     * Execute the console command.
     */
    public function handle(AiAssistantService $aiService)
    {
        $this->info('Testing AI service...');
        
        // First, let's list available models
        $this->info('Listing available models...');
        try {
            $apiKey = env('OPENAI_API_KEY');
            $client = (new GeminiFactory())
                ->withApiKey($apiKey)
                ->withBaseUrl(env('OPENAI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/'))
                ->make();
            
            $models = $client->models()->list();
            $this->info('Available models:');
            foreach ($models->models as $model) {
                $this->line('- ' . $model->name);
            }
        } catch (\Exception $e) {
            $this->error('Failed to list models: ' . $e->getMessage());
        }
        
        // Test general chat response
        $this->info('Testing general chat response...');
        $response = $aiService->getGeneralChatResponse('Say hello!');
        
        if ($response) {
            $this->info('Success! Response: ' . $response);
        } else {
            $this->error('Failed to get response from AI service');
        }
        
        // Test a simple structured scheduling query
        $this->info('Testing simple structured scheduling query...');
        $contextData = [
            'specializations' => ['Cardiology']
        ];
        $response = $aiService->getStructuredSchedulingQuery('Find a cardiologist tomorrow at 10 AM', $contextData);
        
        if ($response) {
            $this->info('Success! Response: ' . json_encode($response, JSON_PRETTY_PRINT));
        } else {
            $this->error('Failed to get structured scheduling query from AI service');
        }
    }
}