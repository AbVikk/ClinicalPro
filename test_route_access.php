<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Http\Request;

try {
    // Create an admin user
    $admin = User::factory()->create([
        'role' => 'admin'
    ]);
    
    echo "Admin user created successfully!\n";
    
    // Log in as admin
    auth()->login($admin);
    
    // Create a request to the doctor store endpoint
    $request = Request::create('/admin/doctor', 'POST', [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '1234567890',
        'date_of_birth' => '1980-01-01',
        'gender' => 'male',
        'address' => '123 Main St',
        'city' => 'New York',
        'state' => 'NY',
        'zip_code' => '10001',
        'country' => 'USA',
        'license_number' => 'DOC12345',
        'specialization_id' => 1,
        'department_id' => 1,
        'medical_school' => 'Harvard Medical School',
        'residency' => 'General Hospital',
        'fellowship' => 'Cardiology',
        'years_of_experience' => 10,
        'status' => 'active',
        'bio' => 'Experienced cardiologist',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);
    
    // Dispatch the request
    $response = $app->handle($request);
    
    echo "Response status: " . $response->getStatusCode() . "\n";
    echo "Response content: " . $response->getContent() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}