<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToHospital;

class AuditLog extends Model
{
    use BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'user_id', 
        'action', 
        'model_type', 
        'model_id', 
        'details', 
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}