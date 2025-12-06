<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Models\Hospital;
use App\Models\User;

echo "Testing Multitenancy Implementation\n";
echo "==================================\n\n";

// Check if we have hospitals
$hospitals = Hospital::all();
echo "Existing Hospitals:\n";
foreach ($hospitals as $hospital) {
    echo "- {$hospital->name} (ID: {$hospital->id}, Domain: {$hospital->domain_prefix})\n";
}

echo "\n";

// Check if we have a super admin user
$superAdmin = User::where('role', 'super_admin')->first();
if ($superAdmin) {
    echo "Super Admin User Found:\n";
    echo "- Name: {$superAdmin->name}\n";
    echo "- Email: {$superAdmin->email}\n";
} else {
    echo "No Super Admin User Found\n";
}

echo "\nTest completed successfully!\n";