<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check if we're in CLI mode
if (php_sapi_name() !== 'cli') {
    die('This script can only be run from the command line.');
}

echo "Creating a test appointment...\n";

// Get a patient (create one if none exists)
$patient = \App\Models\User::where('role', 'patient')->first();
if (!$patient) {
    echo "No patient found, creating a test patient...\n";
    $patient = new \App\Models\User();
    $patient->name = 'Test Patient';
    $patient->email = 'test.patient@example.com';
    $patient->phone = '1234567890';
    $patient->role = 'patient';
    $patient->user_id = 'PAT-TEST-' . time();
    $patient->password = bcrypt('password');
    $patient->save();
    echo "Created patient with ID: " . $patient->id . "\n";
}

// Get a doctor (create one if none exists)
$doctor = \App\Models\User::where('role', 'doctor')->first();
if (!$doctor) {
    echo "No doctor found, creating a test doctor...\n";
    $doctor = new \App\Models\User();
    $doctor->name = 'Test Doctor';
    $doctor->email = 'test.doctor@example.com';
    $doctor->phone = '0987654321';
    $doctor->role = 'doctor';
    $doctor->user_id = 'DOC-TEST-' . time();
    $doctor->password = bcrypt('password');
    $doctor->save();
    echo "Created doctor with ID: " . $doctor->id . "\n";
    
    // Also create doctor record
    $doctorRecord = new \App\Models\Doctor();
    $doctorRecord->user_id = $doctor->id;
    $doctorRecord->status = 'Verified';
    $doctorRecord->save();
}

// Get a service (create one if none exists)
$service = \App\Models\Service::first();
if (!$service) {
    echo "No service found, creating a test service...\n";
    $service = new \App\Models\Service();
    $service->service_name = 'General Consultation';
    $service->description = 'General medical consultation';
    $service->price_amount = 5000.00;
    $service->is_active = true;
    $service->save();
    echo "Created service with ID: " . $service->id . "\n";
}

// Create a consultation
$consultation = new \App\Models\Consultation();
$consultation->patient_id = $patient->id;
$consultation->doctor_id = $doctor->id;
$consultation->location_id = 1;
$consultation->delivery_channel = 'virtual';
$consultation->service_type = $service->service_name;
$consultation->fee = $service->price_amount;
$consultation->status = 'scheduled';
$consultation->start_time = now()->addDay();
$consultation->save();
echo "Created consultation with ID: " . $consultation->id . "\n";

// Create a payment
$payment = new \App\Models\Payment();
$payment->user_id = $patient->id;
$payment->consultation_id = $consultation->id;
$payment->clinic_id = 1;
$payment->amount = $service->price_amount;
$payment->method = 'card_online';
$payment->status = 'pending_cash_verification';
$payment->reference = 'CONS-' . $consultation->id . '-' . time();
$payment->transaction_date = now();
$payment->save();
echo "Created payment with ID: " . $payment->id . " and reference: " . $payment->reference . "\n";

echo "Test appointment setup complete!\n";
echo "Now simulating successful payment...\n";

// Simulate successful payment by updating payment status to 'paid'
$payment->status = 'paid';
$payment->save();
echo "Payment marked as paid.\n";

// Now create the appointment (this is what should happen in the PaymentController)
$appointment = new \App\Models\Appointment();
$appointment->patient_id = $patient->id;
$appointment->doctor_id = $doctor->id;
$appointment->appointment_time = $consultation->start_time;
$appointment->notes = 'Test appointment';
$appointment->type = 'telehealth';
$appointment->status = 'pending'; // This is the key - should be 'pending' for doctor approval
$appointment->save();
echo "Created appointment with ID: " . $appointment->id . " and status: " . $appointment->status . "\n";

// Create a notification for the doctor
$notification = new \App\Models\Notification();
$notification->user_id = $doctor->id;
$notification->type = 'appointment';
$notification->message = "New appointment request: {$patient->name} scheduled for " . $appointment->appointment_time->format('M d, Y g:i A');
$notification->is_read = false;
$notification->channel = 'database';
$notification->save();
echo "Created notification with ID: " . $notification->id . "\n";

echo "Test appointment creation complete!\n";
echo "Doctor ID: " . $doctor->id . "\n";
echo "Appointment ID: " . $appointment->id . "\n";
echo "Appointment status: " . $appointment->status . "\n";