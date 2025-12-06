<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class Hospital extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'domain_prefix',
        'address',
        'contact_email',
        'phone',
        'logo',
        'subscription_plan',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // --- Relationships ---

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function clinics()
    {
        return $this->hasMany(Clinic::class);
    }

    public function drugs()
    {
        return $this->hasMany(Drug::class);
    }

    public function patients()
    {
        // Users with role 'patient' belonging to this hospital
        return $this->hasMany(User::class)->where('role', 'patient');
    }
}