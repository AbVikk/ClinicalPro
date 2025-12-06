<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class ClinicInventory extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'batch_id',
        'clinic_id',
        'hospital_id',
        'stock_level',
        'reorder_point',
    ];

    protected $casts = [
        'stock_level' => 'integer',
        'reorder_point' => 'integer',
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(DrugBatch::class, 'batch_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function drug()
    {
        return $this->hasOneThrough(Drug::class, DrugBatch::class, 'id', 'id', 'batch_id', 'drug_id');
    }
}