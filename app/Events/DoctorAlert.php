<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel; // <-- This is correct
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // <-- This is correct
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DoctorAlert implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    private $doctorId;

    /**
     * Create a new event instance.
     */
    public function __construct($doctorId, $message)
    {
        $this->doctorId = $doctorId;
        $this->message = $message; // <-- THIS IS THE FIX (-> instead of .)
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // This is correct
        return [
            new PrivateChannel('doctor-alerts.' . $this->doctorId),
        ];
    }

    /**
     * The name of the event to broadcast.
     */
    public function broadcastAs()
    {
        // This is correct
        return 'DoctorAlertEvent';
    }

    /**
     * Prepare the data to be broadcasted.
     */
    public function broadcastWith()
    {
        // This is also correct
        return [
            'message' => $this->message
        ];
    }
}