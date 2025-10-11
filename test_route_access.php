<?php
require_once 'vendor/autoload.php';

// Create a simple test to check if the route exists
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if the route exists
$routeCollection = app('router')->getRoutes();
$route = $routeCollection->getByName('admin.prescriptions.templates');

if ($route) {
    echo "Route found:\n";
    echo "URI: " . $route->uri() . "\n";
    echo "Methods: " . implode(', ', $route->methods()) . "\n";
    echo "Middleware: " . implode(', ', $route->middleware()) . "\n";
} else {
    echo "Route not found\n";
}
?>