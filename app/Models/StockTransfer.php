<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;
use App\Traits\BelongsToHospital;

class StockTransfer extends Model
{
    use HasFactory, Auditable, BelongsToHospital;

    protected $fillable = [
        'hospital_id',
        'batch_id',
        'source_id',
        'destination_id',
        'quantity',
        'status', // requested, shipped, received, rejected
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'created_at' => 'datetime', // <--- Ensures ->format() works
        'updated_at' => 'datetime',
    ];

    // --- Relationships ---

    public function batch()
    {
        return $this->belongsTo(DrugBatch::class, 'batch_id');
    }

    public function sourceClinic()
    {
        return $this->belongsTo(Clinic::class, 'source_id');
    }

    public function destinationClinic()
    {
        return $this->belongsTo(Clinic::class, 'destination_id');
    }
}