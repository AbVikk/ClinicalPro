<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hospital;
use App\Models\User;

echo "Verifying Multitenancy Implementation\n";
echo "====================================\n\n";

// Check hospitals
$hospitals = Hospital::all();
echo "Hospitals in database:\n";
foreach ($hospitals as $hospital) {
    echo "- {$hospital->name} (ID: {$hospital->id}, Domain: {$hospital->domain_prefix})\n";
}

echo "\n";

// Check users and their hospital associations
$users = User::with('hospital')->limit(10)->get();
echo "Sample users and their hospitals:\n";
foreach ($users as $user) {
    echo "- {$user->name} ({$user->email}) - Hospital: " . ($user->hospital ? $user->hospital->name : 'None') . "\n";
}

echo "\nMultitenancy verification completed!\n";