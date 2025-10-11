<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Invitation extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'token',
        'email',
        'role',
        'used',
        'expires_at',
    ];
    
    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];
    
    /**
     * Get the user that owns the invitation.
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
    
    /**
     * Check if the invitation is still valid
     */
    public function isValid(): bool
    {
        // Check if the invitation has been used OR if it has expired
        return $this->used === false && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }
    
    /**
     * Mark the invitation as used
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }
}