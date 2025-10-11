<?php
require_once 'vendor/autoload.php';

// Bootstrap the application
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Simulate an authenticated admin user
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Log in as admin
$admin = User::where('role', 'admin')->first();
if ($admin) {
    Auth::login($admin);
    echo "Logged in as admin: " . $admin->email . "\n";
    
    // Try to access the route
    try {
        $response = app()->handle(
            \Illuminate\Http\Request::create('/admin/prescriptions/templates', 'GET')
        );
        
        echo "Status Code: " . $response->getStatusCode() . "\n";
        echo "Content Type: " . $response->headers->get('content-type') . "\n";
        
        // If it's a redirect, show where it's redirecting to
        if ($response->isRedirect()) {
            echo "Redirecting to: " . $response->headers->get('location') . "\n";
        } else {
            echo "Response Content Length: " . strlen($response->getContent()) . " characters\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No admin user found\n";
}
?>