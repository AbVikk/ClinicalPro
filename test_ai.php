<?php
require_once 'vendor/autoload.php';

use OpenAI\Laravel\Facades\OpenAI;

try {
    echo "Testing OpenAI client configuration...\n";
    echo "API Key: AIzaSyAgpnVuYErXpQjw200UE_OyYf1CcB0VjKM\n";
    echo "Base URI: https://generativelanguage.googleapis.com/v1beta/\n";
    
    // Try a simple request using the Laravel facade
    echo "Sending test request using Laravel facade...\n";
    $response = OpenAI::chat()->create([
        'model' => 'gemini-pro',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => 'Say hello!']
        ],
        'max_tokens' => 50,
    ]);
    
    echo "Success! Response:\n";
    echo $response->choices[0]->message->content . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Error Code: " . $e->getCode() . "\n";
    echo "Error Type: " . get_class($e) . "\n";
}