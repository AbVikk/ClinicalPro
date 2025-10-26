<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Drug;
use App\Models\Clinic;

class PharmacyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_drug()
    {
        // Create a primary pharmacist user
        $user = User::create([
            'name' => 'Test Pharmacist',
            'email' => 'pharmacist@test.com',
            'password' => bcrypt('password'),
            'role' => 'primary_pharmacist'
        ]);

        // Acting as the primary pharmacist
        $this->actingAs($user);

        // Test data
        $data = [
            'name' => 'Test Drug',
            'generic_name' => 'Test Generic',
            'category' => 'Antibiotics',
            'strength_mg' => '500mg',
            'medicine_type' => 'OTC',
            'description' => 'Test description',
            'medicine_form' => 'Tablet',
            'manufacturer' => 'Test Manufacturer',
            'supplier' => 'Test Supplier',
            'expiry_date' => '2026-12-31',
            'batch_number' => 'BATCH001',
            'dosage' => 'Take one tablet twice daily',
            'side_effects' => 'May cause drowsiness',
            'precautions' => 'Avoid alcohol',
            'initial_quantity' => 100,
            'reorder_level' => 20,
            'maximum_level' => 200,
            'purchase_price' => 10.00,
            'selling_price' => 25.00,
            'tax_rate' => 5.0,
            'storage_conditions' => ['Room Temperature'],
            'is_active' => true,
        ];

        // Call the create drug endpoint
        $response = $this->post(route('admin.pharmacy.drugs.create'), $data);

        // Assert the response is a redirect (as the controller returns a redirect)
        $response->assertRedirect(route('admin.pharmacy.dashboard'));

        // Assert the drug was created in the database
        $this->assertDatabaseHas('drugs', [
            'name' => 'Test Drug',
            'category' => 'Antibiotics',
        ]);
    }

    /** @test */
    public function it_can_receive_stock()
    {
        // Create a primary pharmacist user
        $user = User::create([
            'name' => 'Test Pharmacist 2',
            'email' => 'pharmacist2@test.com',
            'password' => bcrypt('password'),
            'role' => 'primary_pharmacist'
        ]);

        // Create a drug
        $drug = Drug::create([
            'name' => 'Test Drug 2',
            'category' => 'Test Category 2',
            'strength_mg' => '200mg',
            'unit_price' => 30.00,
            'is_controlled' => false,
        ]);

        // Create a central warehouse
        $warehouse = Clinic::create([
            'name' => 'Central Warehouse',
            'address' => 'Test Address',
            'is_physical' => true,
            'is_warehouse' => true,
        ]);

        // Acting as the primary pharmacist
        $this->actingAs($user);

        // Test data
        $data = [
            'drug_id' => $drug->id,
            'supplier_id' => null,
            'received_quantity' => 100,
            'expiry_date' => '2026-12-31',
        ];

        // Call the receive stock endpoint
        $response = $this->post(route('admin.pharmacy.stock.receive'), $data);

        // Assert the response is JSON with 201 status
        $response->assertStatus(201);

        // Assert the drug batch was created in the database
        $this->assertDatabaseHas('drug_batches', [
            'drug_id' => $drug->id,
            'received_quantity' => 100,
        ]);
    }
}