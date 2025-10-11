<?php
require_once 'vendor/autoload.php';

// Create a simple test to check if we have admin users
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if we have admin users
$admins = \App\Models\User::where('role', 'admin')->count();
echo "Admin users count: " . $admins . "\n";

if ($admins > 0) {
    $admin = \App\Models\User::where('role', 'admin')->first();
    echo "First admin user ID: " . $admin->id . "\n";
    echo "First admin user email: " . $admin->email . "\n";
} else {
    echo "No admin users found\n";
}
?>