<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'department_head_id',
        'about',
        'history',
        'goals',
        'location',
        'contact',
        'email',
        'description',
    ];
    
    // Relationships
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    /**
     * Get the HOD (Head of Department) for this department.
     */
    public function head()
    {
        return $this->belongsTo(User::class, 'department_head_id');
    }

    /**
     * Get all staff members in this department.
     */
    public function staff()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    /**
     * Get doctors in this department.
     */
    public function doctorsInDepartment()
    {
        return $this->hasMany(User::class, 'department_id')->where('role', User::ROLE_DOCTOR);
    }

    /**
     * Get clinic staff (nurses) in this department.
     */
    public function clinicStaff()
    {
        return $this->hasMany(User::class, 'department_id')->where('role', User::ROLE_NURSE);
    }
    
    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        // When deleting a department, set department_id to null for associated users
        static::deleting(function ($department) {
            // Remove department association from users
            $department->staff()->update(['department_id' => null]);
            
            // Remove department head association
            $department->update(['department_head_id' => null]);
        });
    }
}