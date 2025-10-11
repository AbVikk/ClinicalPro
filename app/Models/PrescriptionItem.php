<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrescriptionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_id',
        'drug_id',
        'quantity',
        'dosage_instructions',
        'fulfillment_status',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Set default values
    protected $attributes = [
        'quantity' => 1, // Default quantity to 1
    ];

    // Relationships
    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function drug()
    {
        return $this->belongsTo(Drug::class);
    }
}