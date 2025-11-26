<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'token',
        'email',
        'role',
        'used',
        'expires_at',
        'invited_by', // <--- Restored
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Get the user that owns the invitation (if registered).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }

    /**
     * Get the admin/user who sent this invitation.
     */
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    /**
     * Check if the invitation is valid (not used and not expired).
     */
    public function isValid(): bool
    {
        return $this->used === false && 
               ($this->expires_at === null || $this->expires_at->isFuture());
    }

    /**
     * Mark the invitation as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['used' => true]);
    }
}