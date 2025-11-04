<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reminder; // Import the Reminder model

class ReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    /**
     * Create a new notification instance.
     */
    public function __construct(Reminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     * We'll use the 'database' channel for in-app popups/lists.
     */
    public function via($notifiable)
    {
        return ['database']; 
        // You could add 'mail' or 'vonage' (for SMS) here later!
    }

    /**
     * Get the array representation of the notification for storage in the database.
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'reminder',
            'message' => '⏰ Reminder: ' . $this->reminder->message,
            'scheduled_at' => $this->reminder->scheduled_at->toDateTimeString(),
            'reminder_id' => $this->reminder->id,
            'creator_id' => $this->reminder->creator_id,
        ];
    }
}