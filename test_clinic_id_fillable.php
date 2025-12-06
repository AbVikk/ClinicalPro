<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Clinic;

// Test if clinic_id is now fillable
try {
    // Create a clinic first
    $clinic = Clinic::create([
        'name' => 'Test Clinic',
        'address' => '123 Test Street',
        'is_physical' => true
    ]);
    
    // Try to create a user with clinic_id
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'role' => 'clinic_pharmacist',
        'clinic_id' => $clinic->id
    ]);
    
    echo "SUCCESS: User created with clinic_id\n";
    echo "User ID: " . $user->id . "\n";
    echo "Clinic ID: " . $user->clinic_id . "\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}