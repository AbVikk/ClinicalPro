<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class Notification extends Model
{
    use Auditable, BelongsToHospital;
    protected $fillable = [
        'user_id',
        'hospital_id',
        'type',
        'message',
        'is_read',
        'channel',
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