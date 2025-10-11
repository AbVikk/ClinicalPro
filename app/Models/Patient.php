<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'user_id', // Foreign key to users table
        'name',
        'email',
        'phone',
        'date_of_birth',
        'address',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }
}