<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Drug;
use App\Models\Clinic;

// Simple verification script to test our fixes
echo "Verifying test fixes...\n";

try {
    // Test 1: Check if we can create a clinic
    $clinic = Clinic::updateOrCreate(
        ['name' => 'Test Clinic'],
        [
            'address' => '123 Test Street, Lagos', 
            'is_physical' => true
        ]
    );
    echo "✓ Clinic creation/update works\n";
    
    // Test 2: Check if we can create a user with clinic_id (our original fix)
    $pharmacist = User::updateOrCreate(
        ['email' => 'pharm@test.com'],
        [
            'name' => 'Test Pharmacist',
            'password' => bcrypt('password'),
            'role' => 'clinic_pharmacist',
            'clinic_id' => $clinic->id
        ]
    );
    echo "✓ User creation with clinic_id works (MassAssignmentException fixed)\n";
    
    // Test 3: Check if we can create a drug with all required fields (our new fix)
    $drug = Drug::create([
        'name' => 'Paracetamol',
        'category' => 'Analgesic',
        'strength_mg' => '500mg',
        'unit_price' => 100.00,
        'is_controlled' => false
    ]);
    echo "✓ Drug creation with all required fields works (QueryException fixed)\n";
    
    // Clean up
    $drug->delete();
    // Note: Not deleting clinic and user to avoid issues with other tests
    
    echo "SUCCESS: All fixes verified!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}