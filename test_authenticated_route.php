<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Start a session
session_start();

// Log in as admin
$admin = User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    echo "Logged in as admin: " . $admin->email . " (ID: " . $admin->id . ")\n";
    echo "Auth check: " . (Auth::check() ? 'true' : 'false') . "\n";
    echo "Auth user ID: " . (Auth::id() ?: 'null') . "\n";
    
    // Create a request to the templates route with proper session
    $request = \Illuminate\Http\Request::create('/admin/prescriptions/templates', 'GET');
    
    // Process the request through the router
    try {
        $response = $app->handle($request);
        echo "Status Code: " . $response->getStatusCode() . "\n";
        
        // If it's not a redirect, show a portion of the content
        if (!$response->isRedirect()) {
            $content = $response->getContent();
            echo "Content length: " . strlen($content) . " characters\n";
        } else {
            echo "Redirecting to: " . $response->headers->get('location') . "\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No admin user found\n";
}
?>