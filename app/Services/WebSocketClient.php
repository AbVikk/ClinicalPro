<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class WebSocketClient
{
    /**
     * Send a message to a specific Redis channel.
     * The Node.js WebSocket server is listening (psubscribe *) 
     * and will forward this to the browser.
     *
     * @param string $channel  The private channel name (e.g., 'doctor-alerts.5')
     * @param string $event    The event name (e.g., 'DoctorAlertEvent')
     * @param array $data      The payload data
     * @return bool
     */
    public function sendToChannel($channel, $event, $data)
    {
        try {
            // Prepare the payload exactly how websocket-server.js expects it
            $payload = json_encode([
                'event' => $event,
                'data' => $data
            ]);

            // Publish directly to Redis
            Redis::publish($channel, $payload);
            
            return true;
            
        } catch (\Exception $e) {
            Log::error("[WebSocketClient] Failed to publish to Redis: " . $e->getMessage());
            return false;
        }
    }
}