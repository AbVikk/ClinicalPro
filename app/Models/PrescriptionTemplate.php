<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToHospital;

class PrescriptionTemplate extends Model
{
    use HasFactory, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'name',
        'description',
        'created_by',
        'medications',
        'diagnosis',
        'notes',
        'usage_count',
    ];

    protected $casts = [
        'medications' => 'array',
        'usage_count' => 'integer',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}