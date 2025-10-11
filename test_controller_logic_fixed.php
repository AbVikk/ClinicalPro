<?php

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Doctor;
use Carbon\Carbon;

try {
    // Simulate a date input with the correct format from datetimepicker
    $date = 'Friday 03 October 2025 - 10:00';
    echo "Testing with date: " . $date . "\n";
    
    try {
        // Parse the date to get the day of week
        // The datetimepicker format is 'dddd DD MMMM YYYY - HH:mm'
        $appointmentDate = Carbon::createFromFormat('l d F Y - H:i', $date);
        $dayOfWeek = $appointmentDate->format('l'); // e.g., 'Monday', 'Tuesday', etc.
        
        echo "Parsed day of week: " . $dayOfWeek . "\n";
        echo "Parsed date: " . $appointmentDate->format('Y-m-d H:i:s') . "\n";
    } catch (\Exception $e) {
        echo "Date parsing error: " . $e->getMessage() . "\n";
        exit;
    }
    
    // Get doctors with their availability
    $doctors = Doctor::with('user')
        ->where('status', 'Verified')
        ->get();
        
    echo "Total verified doctors found: " . $doctors->count() . "\n";
    
    $filteredDoctors = $doctors->filter(function ($doctor) use ($dayOfWeek, $appointmentDate) {
        echo "Checking doctor: " . $doctor->user->name . " (ID: " . $doctor->id . ")\n";
        
        // Check if doctor has availability data
        if (!$doctor->availability) {
            echo "Doctor has no availability data, assuming available\n";
            return true; // If no availability set, assume available
        }
        
        $availability = $doctor->availability; // This is already an array due to casting
        
        echo "Doctor availability: " . json_encode($availability) . "\n";
        
        // Check if doctor is available on this day
        if (isset($availability[$dayOfWeek])) {
            $dayAvailability = $availability[$dayOfWeek];
            
            echo "Day availability for " . $dayOfWeek . ": " . json_encode($dayAvailability) . "\n";
            
            // Check if time slots are available
            if (isset($dayAvailability['slots']) && !empty($dayAvailability['slots'])) {
                echo "Doctor has available slots\n";
                return true;
            }
            
            // If it's a full day availability
            if (isset($dayAvailability['available']) && $dayAvailability['available']) {
                echo "Doctor is available for full day\n";
                return true;
            }
        }
        
        echo "Doctor is not available on " . $dayOfWeek . "\n";
        return false;
    })
    ->map(function ($doctor) {
        return $doctor->user;
    });
    
    echo "Filtered doctors count: " . $filteredDoctors->count() . "\n";
    
    foreach ($filteredDoctors as $user) {
        echo "Available doctor: " . $user->name . " (ID: " . $user->id . ")\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}