<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class LeaveRequest extends Model
{
    use Auditable, BelongsToHospital;

    protected $fillable = [
        'user_id',
        'hospital_id',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];
    
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the leave request.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}