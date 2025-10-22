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
    'driver'    => 'mysql',
    'host'      => $env['DB_HOST'],
    'database'  => $env['DB_DATABASE'],
    'username' => $env['DB_USERNAME'],
    'password' => $env['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

$capsule->setEventDispatcher(new Dispatcher($container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Test accepting an appointment
try {
    // Get a pending appointment
    $appointment = Capsule::table('appointments')->where('status', 'pending')->first();
    
    if ($appointment) {
        // Update the appointment status to 'confirmed'
        Capsule::table('appointments')->where('id', $appointment->id)->update([
            'status' => 'confirmed',
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        echo "Appointment ID {$appointment->id} accepted successfully\n";
    } else {
        echo "No pending appointments found\n";
    }
} catch (Exception $e) {
    echo "Error accepting appointment: " . $e->getMessage() . "\n";
}