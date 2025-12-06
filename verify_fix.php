<?php

require_once __DIR__.'/bootstrap/app.php';

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Clinic;

try {
    // Test 1: Check if clinic_id is in the fillable array
    $user = new User();
    $fillable = $user->getFillable();
    
    if (in_array('clinic_id', $fillable)) {
        echo "PASS: clinic_id is in the fillable array\n";
    } else {
        echo "FAIL: clinic_id is NOT in the fillable array\n";
        print_r($fillable);
        exit(1);
    }
    
    // Test 2: Try to create a user with clinic_id (this was failing before)
    $clinic = Clinic::create([
        'name' => 'Test Clinic',
        'address' => '123 Test Street, Lagos',
        'is_physical' => true
    ]);
    
    $pharmacist = User::create([
        'name' => 'Test Pharmacist',
        'email' => 'pharm@test.com',
        'password' => bcrypt('password'),
        'role' => 'clinic_pharmacist',
        'clinic_id' => $clinic->id
    ]);
    
    echo "PASS: Successfully created user with clinic_id\n";
    echo "User ID: " . $pharmacist->id . "\n";
    echo "Clinic ID: " . $pharmacist->clinic_id . "\n";
    
    // Clean up
    $pharmacist->delete();
    $clinic->delete();
    
    echo "SUCCESS: All tests passed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
    exit(1);
}