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

// Get the doctor ID (assuming the first doctor in the database)
$doctor = \App\Models\User::where('role', 'doctor')->first();
if (!$doctor) {
    echo "No doctor found in the database.\n";
    exit(1);
}

$doctorId = $doctor->id;
echo "Doctor ID: " . $doctorId . "\n";

// Count pending appointments
$pendingCount = \App\Models\Appointment::where('doctor_id', $doctorId)
    ->whereIn('status', ['pending', 'new'])
    ->count();

echo "Pending appointments count: " . $pendingCount . "\n";

// List all appointments for this doctor
$appointments = \App\Models\Appointment::where('doctor_id', $doctorId)
    ->orderBy('created_at', 'desc')
    ->get();

echo "Total appointments for this doctor: " . $appointments->count() . "\n";
echo "Appointment statuses:\n";

$statusCounts = [];
foreach ($appointments as $appointment) {
    $status = $appointment->status;
    if (!isset($statusCounts[$status])) {
        $statusCounts[$status] = 0;
    }
    $statusCounts[$status]++;
    
    echo "  - ID: " . $appointment->id . ", Status: " . $status . ", Created: " . $appointment->created_at . "\n";
}

echo "\nStatus summary for this doctor:\n";
foreach ($statusCounts as $status => $count) {
    echo "  $status: $count\n";
}

// Check all appointments in the system
echo "\n--- All appointments in system ---\n";
$allAppointments = \App\Models\Appointment::orderBy('created_at', 'desc')->get();
echo "Total appointments in system: " . $allAppointments->count() . "\n";

$allStatusCounts = [];
$doctorCounts = [];
foreach ($allAppointments as $appointment) {
    // Status counts
    $status = $appointment->status;
    if (!isset($allStatusCounts[$status])) {
        $allStatusCounts[$status] = 0;
    }
    $allStatusCounts[$status]++;
    
    // Doctor counts
    $docId = $appointment->doctor_id;
    if (!isset($doctorCounts[$docId])) {
        $doctorCounts[$docId] = 0;
    }
    $doctorCounts[$docId]++;
}

echo "\nStatus summary for all appointments:\n";
foreach ($allStatusCounts as $status => $count) {
    echo "  $status: $count\n";
}

echo "\nAppointments per doctor:\n";
foreach ($doctorCounts as $docId => $count) {
    $doc = \App\Models\User::find($docId);
    $docName = $doc ? $doc->name : 'Unknown';
    echo "  Doctor ID $docId ($docName): $count appointments\n";
}

// Check notifications
echo "\n--- Notifications ---\n";
$notifications = \App\Models\Notification::where('user_id', $doctorId)->orderBy('created_at', 'desc')->get();
echo "Total notifications for doctor ID $doctorId: " . $notifications->count() . "\n";
echo "Unread notifications for doctor ID $doctorId: " . \App\Models\Notification::where('user_id', $doctorId)->where('is_read', false)->count() . "\n";

foreach ($notifications as $notification) {
    echo "  - ID: " . $notification->id . ", Type: " . $notification->type . ", Message: " . $notification->message . ", Read: " . ($notification->is_read ? 'Yes' : 'No') . ", Created: " . $notification->created_at . "\n";
}

// Check consultations and payments
echo "\n--- Consultations and Payments ---\n";
$consultations = \App\Models\Consultation::all();
echo "Total consultations: " . $consultations->count() . "\n";
$payments = \App\Models\Payment::all();
echo "Total payments: " . $payments->count() . "\n";

foreach ($consultations as $consultation) {
    echo "  Consultation ID: " . $consultation->id . ", Patient ID: " . $consultation->patient_id . ", Doctor ID: " . $consultation->doctor_id . ", Status: " . $consultation->status . ", Created: " . $consultation->created_at . "\n";
    
    // Check associated payments
    $consultationPayments = $consultation->payments;
    if ($consultationPayments->count() > 0) {
        foreach ($consultationPayments as $payment) {
            echo "    Payment ID: " . $payment->id . ", Reference: " . $payment->reference . ", Status: " . $payment->status . ", Amount: " . $payment->amount . "\n";
        }
    } else {
        echo "    No associated payments\n";
    }
}

echo "\nPayments without consultations:\n";
$standalonePayments = \App\Models\Payment::whereNull('consultation_id')->get();
foreach ($standalonePayments as $payment) {
    echo "  Payment ID: " . $payment->id . ", Reference: " . $payment->reference . ", Status: " . $payment->status . ", Amount: " . $payment->amount . ", User ID: " . $payment->user_id . "\n";
}