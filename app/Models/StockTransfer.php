<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id',
        'source_id',
        'destination_id',
        'quantity',
        'status',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function batch()
    {
        return $this->belongsTo(DrugBatch::class, 'batch_id');
    }

    public function source()
    {
        return $this->belongsTo(Clinic::class, 'source_id');
    }

    public function destination()
    {
        return $this->belongsTo(Clinic::class, 'destination_id');
    }
}