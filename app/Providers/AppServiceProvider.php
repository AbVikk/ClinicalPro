<?php

namespace App\Providers;

// --- ADD THESE 'USE' STATEMENTS AT THE TOP ---
use Gemini\Client;
use Gemini\Factory as GeminiFactory;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // This is our manual binding logic [1]
        $this->app->singleton(Client::class, function ($app) {
            
            $apiKey = env('OPENAI_API_KEY');
            
            // We get the URL. If it's missing from.env, we provide a default.
            $baseUrl = env('OPENAI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta/'); 
            
            $httpClient = new GuzzleClient(['timeout' => 60]);
            
            return (new GeminiFactory())
                ->withApiKey($apiKey)
                ->withBaseUrl($baseUrl) 
                ->withHttpClient($httpClient)
                ->make();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Model::shouldBeStrict(!app()->isProduction());
    }
}