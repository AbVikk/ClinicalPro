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
        'medication_name',
        'type',
        'dosage',
        'duration',
        'use_pattern',
        'instructions',
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

    // Automatically generate dosage_instructions from individual fields
    public function setDosageInstructionsAttribute($value)
    {
        // If a value is explicitly set, use it
        if ($value !== null) {
            $this->attributes['dosage_instructions'] = $value;
        }
        // Otherwise, generate it from individual fields
        else {
            $this->attributes['dosage_instructions'] = sprintf(
                "%s||%s||%s||%s||%s",
                $this->dosage ?? '',
                $this->type ?? '',
                $this->duration ?? '',
                $this->use_pattern ?? '',
                $this->instructions ?? ''
            );
        }
    }

    // Ensure dosage_instructions is always populated
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // If dosage_instructions is not set, generate it from individual fields
            if (empty($model->dosage_instructions)) {
                $model->dosage_instructions = sprintf(
                    "%s||%s||%s||%s||%s",
                    $model->dosage ?? '',
                    $model->type ?? '',
                    $model->duration ?? '',
                    $model->use_pattern ?? '',
                    $model->instructions ?? ''
                );
            }
        });
    }
}