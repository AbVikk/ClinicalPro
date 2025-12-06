<?php

require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Hospital;

echo "Checking Hospitals in Database\n";
echo "=============================\n\n";

// Check if we have hospitals
$hospitals = Hospital::all();
if ($hospitals->count() > 0) {
    echo "Existing Hospitals:\n";
    foreach ($hospitals as $hospital) {
        echo "- {$hospital->name} (ID: {$hospital->id}, Domain: {$hospital->domain_prefix})\n";
    }
} else {
    echo "No hospitals found in the database.\n";
}

echo "\n";