<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Category;
use App\Models\Department;

class Doctor extends Model
{
    use HasFactory;
    
    protected $table = 'doctors_new';
    
    protected $fillable = [
        'user_id',
        'doctor_id',
        'specialization',
        'department_id',
        'category_id',
        'license_number',
        'proof_of_identity',
        'status',
        'availability',
        'medical_school',
        'residency',
        'fellowship',
        'years_of_experience',
        'bio',
    ];

    protected $casts = [
        'availability' => 'array',
        'years_of_experience' => 'integer',
    ];

    // Define the route key name for route model binding
    public function getRouteKeyName()
    {
        return 'user_id';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_id');
    }
}