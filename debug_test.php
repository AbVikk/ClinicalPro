<?php

require_once __DIR__.'/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Category;

echo "Debugging test environment...\n";

// Test database connection
try {
    $count = User::count();
    echo "✓ Database connection works. Users count: " . $count . "\n";
} catch (Exception $e) {
    echo "✗ Database connection error: " . $e->getMessage() . "\n";
    exit(1);
}

// Test if we can create models
try {
    $timestamp = time();
    
    // Create department
    $department = Department::create([
        'name' => 'Debug Department ' . $timestamp,
        'description' => 'Debug Department Description'
    ]);
    echo "✓ Created department\n";
    
    // Create category
    $category = Category::create([
        'name' => 'Debug Category ' . $timestamp,
        'description' => 'Debug Category Description'
    ]);
    echo "✓ Created category\n";
    
    // Create user
    $user = User::create([
        'name' => 'Debug User ' . $timestamp,
        'email' => 'debug' . $timestamp . '@example.com',
        'password' => bcrypt('password'),
        'role' => 'doctor',
        'phone' => '1234567890'
    ]);
    echo "✓ Created user\n";
    
    // Create doctor
    $doctor = Doctor::create([
        'user_id' => $user->id,
        'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
        'license_number' => 'DEBUG123',
        'category_id' => $category->id,
        'department_id' => $department->id,
        'status' => 'verified',
    ]);
    echo "✓ Created doctor\n";
    
    // Verify doctor was created
    $doctorCount = Doctor::where('license_number', 'DEBUG123')->count();
    echo "✓ Doctor count with license DEBUG123: " . $doctorCount . "\n";
    
    // Clean up
    $doctor->delete();
    $user->delete();
    $category->delete();
    $department->delete();
    
    echo "SUCCESS: All tests passed!\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "TRACE: " . $e->getTraceAsString() . "\n";
}