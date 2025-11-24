# Real-time Alert System Setup

This document explains how to set up and use the real-time alert system for the healthcare application.

## Overview

The real-time alert system uses WebSockets to provide instant notifications to doctors when:
1. A new appointment request is created
2. A patient's vitals have been recorded by a nurse

## Components

1. **Laravel Backend**: Handles event creation and broadcasting
2. **Node.js WebSocket Server**: Manages real-time connections
3. **Frontend JavaScript**: Listens for and displays alerts

## Setup Instructions

### 1. Install Dependencies

```bash
# Install Laravel Pusher package (for broadcasting interface)
composer require pusher/pusher-php-server

# Install Node.js WebSocket dependencies
npm install ws
```

### 2. Configure Environment

Add the following to your `.env` file:

```ini
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=my-app-id-12345
PUSHER_APP_KEY=my-app-key-12345
PUSHER_APP_SECRET=my-app-secret-12345
PUSHER_APP_CLUSTER=mt1

# This tells Laravel to send messages to our OWN server, not Pusher.com
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
```

### 3. Configure Broadcasting

Update `config/broadcasting.php` to include the Pusher configuration with our custom settings:

```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'encrypted' => false,
        'useTLS' => false,
    ],
],
```

### 4. Start the Servers

You need to run two servers:

1. **Laravel Development Server**:
   ```bash
   php artisan serve
   ```

2. **WebSocket Server**:
   ```bash
   node websocket-server.js
   ```

### 5. Frontend Integration

The frontend JavaScript is already included in `resources/views/doctor/sidemenu.blade.php`. It automatically connects authenticated doctors to their private channels and displays alerts.

## How It Works

1. When an event occurs (new appointment or vitals recorded), Laravel creates a `DoctorAlert` event
2. The event is broadcast to a private channel specific to the doctor (e.g., `doctor-channel.5` for doctor ID 5)
3. Our custom `WebSocketClient` writes the message to a queue file in `storage/app/websocket_queue`
4. The Node.js WebSocket server monitors this directory and broadcasts messages to connected clients
5. The doctor's browser receives the message and displays an alert

## Testing

To test the system:

1. Start both servers
2. Log in as a doctor
3. Trigger an event (create an appointment or record vitals)
4. The doctor should receive an instant alert

## Troubleshooting

1. **No alerts received**: Check that both servers are running
2. **Connection errors**: Verify the WebSocket server is listening on port 6001
3. **Authentication issues**: Ensure the doctor is logged in and the channel authorization is working