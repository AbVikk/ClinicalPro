<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Doctor;
use App\Models\User;

try {
    $doctors = Doctor::all();
    echo "Total doctors: " . $doctors->count() . "\n";
    
    foreach ($doctors as $doctor) {
        echo "Doctor ID: " . $doctor->id . "\n";
        echo "Doctor user_id: " . $doctor->user_id . "\n";
        echo "Doctor status: " . $doctor->status . "\n";
        
        $user = User::find($doctor->user_id);
        if ($user) {
            echo "User name: " . $user->name . "\n";
        } else {
            echo "No user found for this doctor\n";
        }
        
        echo "---\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}