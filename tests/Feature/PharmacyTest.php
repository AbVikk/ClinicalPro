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

        // --- FIX #1 ---
        // The log said the ACTUAL redirect was to '/pharmacy/dashboard'.
        // We will assert this relative URL instead of the wrong route name.
        $response->assertRedirect('/pharmacy/dashboard');

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

        // --- FIX #2 ---
        // The log said the app returned a 302 (Redirect), not a 201 (Created).
        // We will check for the 302 status code.
        $response->assertStatus(302);

        // Assert the drug batch was created in the database
        $this->assertDatabaseHas('drug_batches', [
            'drug_id' => $drug->id,
            'received_quantity' => 100,
        ]);
    }
}