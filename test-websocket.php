<?php

require_once 'vendor/autoload.php';

use App\Services\WebSocketClient;

// Create a test WebSocket client
$client = new WebSocketClient();

// Send a test message
$success = $client->sendToChannel(
    'doctor-channel.1',
    'DoctorAlert',
    ['message' => 'Test alert for doctor 1']
);

if ($success) {
    echo "Test message sent successfully!\n";
} else {
    echo "Failed to send test message.\n";
}

echo "Check the storage/app/websocket_queue directory for the message file.\n";