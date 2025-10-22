<?php
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Payment model constants:\n";
echo "METHODS: " . json_encode(\App\Models\Payment::METHODS) . "\n";
echo "STATUSES: " . json_encode(\App\Models\Payment::STATUSES) . "\n";

// Try to create a payment with 'pending' status to see if it fails
echo "\nTesting payment creation with 'pending' status...\n";
try {
    $payment = new \App\Models\Payment();
    $payment->user_id = 1;
    $payment->amount = 100.00;
    $payment->method = 'card_online';
    $payment->status = 'pending';
    $payment->reference = 'TEST-' . time();
    $payment->transaction_date = now();
    $payment->save();
    echo "Payment created successfully with 'pending' status\n";
    echo "Payment ID: " . $payment->id . "\n";
    
    // Delete the test payment
    $payment->delete();
} catch (Exception $e) {
    echo "Error creating payment with 'pending' status: " . $e->getMessage() . "\n";
}

// Try to create a payment with 'pending_cash_verification' status
echo "\nTesting payment creation with 'pending_cash_verification' status...\n";
try {
    $payment = new \App\Models\Payment();
    $payment->user_id = 1;
    $payment->amount = 100.00;
    $payment->method = 'card_online';
    $payment->status = 'pending_cash_verification';
    $payment->reference = 'TEST-' . time();
    $payment->transaction_date = now();
    $payment->save();
    echo "Payment created successfully with 'pending_cash_verification' status\n";
    echo "Payment ID: " . $payment->id . "\n";
    
    // Delete the test payment
    $payment->delete();
} catch (Exception $e) {
    echo "Error creating payment with 'pending_cash_verification' status: " . $e->getMessage() . "\n";
}