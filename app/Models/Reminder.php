<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // <-- We'll use this trait for sending notifications!

class Reminder extends Model
{
    use HasFactory, Notifiable; // Use Notifiable trait

    protected $fillable = [
        'creator_id',
        'user_id',
        'scheduled_at',
        'message',
        'consultation_id',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    // The user who created the reminder
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    // The user who should receive the reminder notification
    // We use the Notifiable trait on this model, but we usually route 
    // the notification to the target User model for delivery.
    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // The consultation this reminder is related to (optional)
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    /**
     * Required by the Notifiable trait for sending notifications.
     * We want to send the notification to the specific user linked by user_id.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->user->email; // Or whatever channel you plan to use
    }
}