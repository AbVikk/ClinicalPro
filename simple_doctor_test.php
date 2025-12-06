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

echo "Testing Doctor creation...\n";

try {
    // Create required related models with unique names
    $timestamp = time();
    $department = Department::create([
        'name' => 'Test Department ' . $timestamp,
        'description' => 'Test Department Description'
    ]);
    echo "✓ Created department\n";
    
    $category = Category::create([
        'name' => 'Test Category ' . $timestamp,
        'description' => 'Test Category Description'
    ]);
    echo "✓ Created category\n";
    
    // Create a user with unique email
    $user = User::create([
        'name' => 'Doctor User ' . $timestamp,
        'email' => 'doctor' . $timestamp . '@example.com',
        'password' => bcrypt('password'),
        'role' => 'doctor',
        'phone' => '1234567890'
    ]);
    echo "✓ Created user\n";
    
    // Try to create the doctor
    $doctor = Doctor::create([
        'user_id' => $user->id,
        'doctor_id' => 'DOC' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
        'license_number' => 'DOC12345',
        'category_id' => $category->id,
        'department_id' => $department->id,
        'status' => 'verified',
    ]);
    
    echo "✓ Created doctor with license number: " . $doctor->license_number . "\n";
    echo "SUCCESS: Doctor creation works!\n";
    
    // Clean up
    $doctor->delete();
    $user->delete();
    $category->delete();
    $department->delete();
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}