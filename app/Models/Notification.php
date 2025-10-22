<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type', // e.g., 'appointment', 'message', 'payment'
        'message',
        'is_read',
        'channel', // e.g., 'sms', 'email', 'database'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Accessor to check if notification is read
    public function getIsReadAttribute($value)
    {
        return (bool) $value;
    }
    
    // Mutator to set read status
    public function setIsReadAttribute($value)
    {
        $this->attributes['is_read'] = (bool) $value;
    }
}