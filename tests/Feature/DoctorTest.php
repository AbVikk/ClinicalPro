<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Category;
use PHPUnit\Framework\Attributes\Test;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_doctor_with_all_fields()
    {
        // Create required related models
        $department = Department::factory()->create();
        $category = Category::factory()->create();
        
        // Create an admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);
        
        // Data for the new doctor (only required fields)
        $doctorData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'license_number' => 'DOC12345',
            'specialization_id' => $category->id,
            'department_id' => $department->id,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        // Create the doctor using the named route
        $response = $this->actingAs($admin)->post(route('admin.doctor.store'), $doctorData);
        
        // Should redirect after successful creation
        $response->assertStatus(302);
        
        // Assert the user was created with the correct data
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'name' => 'John Doe',
            'role' => 'doctor'
        ]);
        
        // Assert the doctor was created with the correct data
        $this->assertDatabaseHas('doctors_new', [
            'license_number' => 'DOC12345',
        ]);
    }
}