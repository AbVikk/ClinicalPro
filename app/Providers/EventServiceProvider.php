<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Broadcast; // <-- Make sure this is imported

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Your other events can go here
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        // --- THIS IS THE FINAL FIX ---
        
        // This tells Laravel to create the "/broadcasting/auth" URL
        // AND to make sure it uses the 'web' and 'auth' middleware.
        Broadcast::routes(["middleware" => ["web", "auth"]]);

        // This tells the "security guard" at that URL 
        // which rules to use (from our channels.php file).
        require base_path('routes/channels.php');
        
        // --- END OF FIX ---
    }
}