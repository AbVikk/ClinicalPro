<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Doctor;

try {
    $doctor = Doctor::where('status', 'Verified')->first();
    if ($doctor) {
        echo "Doctor ID: " . $doctor->id . "\n";
        echo "Doctor user_id: " . $doctor->user_id . "\n";
        echo "Doctor status: " . $doctor->status . "\n";
        echo "Doctor availability: " . json_encode($doctor->availability) . "\n";
        
        // Check if user exists
        if ($doctor->user) {
            echo "User name: " . $doctor->user->name . "\n";
        } else {
            echo "No user relationship\n";
        }
    } else {
        echo "No verified doctors found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}