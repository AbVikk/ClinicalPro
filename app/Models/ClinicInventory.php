<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClinicInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'clinic_id',
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