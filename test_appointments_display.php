<?php
require_once 'vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

// Load environment variables
$env = parse_ini_file('.env');

// Create a service container
$container = new Container();

// Create a database capsule
$capsule = new Capsule($container);
$capsule->addConnection([
    'driver' => 'mysql',
    'host' => $env['DB_HOST'] ?? 'localhost',
    'database' => $env['DB_DATABASE'] ?? 'healthcare_db',
    'username' => $env['DB_USERNAME'] ?? 'health_user',
    'password' => $env['DB_PASSWORD'] ?? '@2024victor',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test fetching and categorizing appointments
try {
    // Get a doctor
    $doctor = Capsule::table('users')->where('role', 'doctor')->first();
    
    if ($doctor) {
        // Get all appointments for this doctor
        $allAppointments = Capsule::table('appointments')
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_time', 'asc')
            ->get();
        
        echo "Total appointments for doctor ID {$doctor->id}: " . count($allAppointments) . "\n\n";
        
        // Categorize appointments
        $upcoming = [];
        $cancelled = [];
        $completed = [];
        
        foreach ($allAppointments as $appointment) {
            $appointmentTime = new DateTime($appointment->appointment_time);
            $now = new DateTime();
            
            if ($appointment->status === 'cancelled') {
                $cancelled[] = $appointment;
            } elseif ($appointment->status === 'completed') {
                $completed[] = $appointment;
            } elseif (($appointment->status === 'pending' || $appointment->status === 'new' || $appointment->status === 'confirmed') && 
                      $appointmentTime > $now) {
                $upcoming[] = $appointment;
            }
        }
        
        echo "Upcoming appointments: " . count($upcoming) . "\n";
        foreach ($upcoming as $appointment) {
            echo "  - ID: {$appointment->id}, Status: {$appointment->status}, Time: {$appointment->appointment_time}\n";
        }
        
        echo "\nCancelled appointments: " . count($cancelled) . "\n";
        foreach ($cancelled as $appointment) {
            echo "  - ID: {$appointment->id}, Status: {$appointment->status}, Time: {$appointment->appointment_time}\n";
        }
        
        echo "\nCompleted appointments: " . count($completed) . "\n";
        foreach ($completed as $appointment) {
            echo "  - ID: {$appointment->id}, Status: {$appointment->status}, Time: {$appointment->appointment_time}\n";
        }
    } else {
        echo "No doctors found in the database\n";
    }
} catch (Exception $e) {
    echo "Error fetching appointments: " . $e->getMessage() . "\n";
}