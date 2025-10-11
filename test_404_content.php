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
    
    // Create a request to the templates route with proper session
    $request = \Illuminate\Http\Request::create('/admin/prescriptions/templates', 'GET');
    
    // Process the request through the router
    try {
        $response = $app->handle($request);
        
        // If it's not a redirect, show the content
        if (!$response->isRedirect()) {
            $content = $response->getContent();
            // Save content to a file for analysis
            file_put_contents('response_content.html', $content);
            echo "Content saved to response_content.html\n";
            echo "Content length: " . strlen($content) . " characters\n";
            
            // Check if it contains typical 404 content
            if (strpos($content, '404') !== false || strpos($content, 'Not Found') !== false) {
                echo "Content appears to be a 404 page\n";
            }
            
            // Show first 1000 characters
            echo "Content preview:\n" . substr($content, 0, 1000) . "\n...\n";
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