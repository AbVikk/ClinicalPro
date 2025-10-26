<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Clinic extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'is_physical',
        'is_warehouse',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_physical' => 'boolean',
        'is_warehouse' => 'boolean',
    ];

    /**
     * Get the users associated with this clinic.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the consultations that took place at this clinic.
     */
    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'location_id');
    }

    /**
     * Get the payments associated with this clinic.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the disbursements associated with this clinic.
     */
    public function disbursements()
    {
        return $this->hasMany(Disbursement::class);
    }
}