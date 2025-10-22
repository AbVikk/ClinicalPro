<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'blood_group',
        'lab_tests',
        'complaints',
        'diagnosis',
        'advice',
        'follow_up_date',
        'follow_up_time',
    ];

    protected $casts = [
        'lab_tests' => 'array',
        'complaints' => 'array',
        'diagnosis' => 'array',
        'follow_up_date' => 'date',
        'follow_up_time' => 'datetime',
    ];

    // Accessor to get lab tests with file information
    public function getLabTestsWithFilesAttribute()
    {
        $labTests = $this->lab_tests ?? [];
        $result = [];
        
        foreach ($labTests as $labTest) {
            if (is_string($labTest)) {
                // Old format - just text
                $result[] = [
                    'name' => $labTest,
                    'file_path' => null
                ];
            } elseif (is_array($labTest)) {
                // New format - with file info
                $result[] = $labTest;
            }
        }
        
        return $result;
    }

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function vitals()
    {
        // Fix: Use the correct foreign key
        return $this->hasOne(Vitals::class, 'appointment_id', 'appointment_id');
    }

    public function clinicalNote()
    {
        // Fix: Use the correct foreign key
        return $this->hasOne(ClinicalNote::class, 'appointment_id', 'appointment_id');
    }

    public function medications()
    {
        // Fix: Use the correct foreign key
        return $this->hasMany(Medication::class, 'appointment_id', 'appointment_id');
    }
}