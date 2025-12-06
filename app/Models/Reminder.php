<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\BelongsToHospital;

class Reminder extends Model
{
    use HasFactory, Notifiable, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function user() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

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
        return $this->user->email;
    }
}