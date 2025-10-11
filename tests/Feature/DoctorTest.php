<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Department;
use App\Models\Category;

class DoctorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_doctor_with_all_fields()
    {
        // Create required related models
        $department = Department::factory()->create();
        $category = Category::factory()->create();
        
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin'
        ]);
        
        // Data for the new doctor
        $doctorData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'date_of_birth' => '1980-01-01',
            'gender' => 'male',
            'address' => '123 Main St',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'USA',
            'license_number' => 'DOC12345',
            'specialization_id' => $category->id,
            'department_id' => $department->id,
            'medical_school' => 'Harvard Medical School',
            'residency' => 'General Hospital',
            'fellowship' => 'Cardiology',
            'years_of_experience' => 10,
            'status' => 'active',
            'bio' => 'Experienced cardiologist',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        // Acting as admin, submit the form
        $response = $this->actingAs($admin)->post(route('admin.doctor.store'), $doctorData);
        
        // Assert the doctor was created
        $this->assertDatabaseHas('users', [
            'email' => 'john.doe@example.com',
            'name' => 'John Doe',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'USA',
        ]);
        
        $this->assertDatabaseHas('doctors_new', [
            'license_number' => 'DOC12345',
            'medical_school' => 'Harvard Medical School',
            'residency' => 'General Hospital',
            'fellowship' => 'Cardiology',
            'years_of_experience' => 10,
        ]);
        
        $response->assertRedirect(route('admin.doctor.index'));
        $response->assertSessionHas('success');
    }
}