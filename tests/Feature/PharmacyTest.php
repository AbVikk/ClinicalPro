<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Drug;
use App\Models\Clinic;
use Database\Seeders\DrugCategoriesTableSeeder;
use Database\Seeders\DrugMgTableSeeder;
use Database\Seeders\CentralWarehouseSeeder;
use PHPUnit\Framework\Attributes\Test;

class PharmacyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed the required data for pharmacy tests
        $this->seed(DrugCategoriesTableSeeder::class);
        $this->seed(DrugMgTableSeeder::class);
        $this->seed(CentralWarehouseSeeder::class);
    }

    #[Test]
    public function it_can_create_a_drug()
    {
        // Test that the database seeding worked
        $this->assertDatabaseHas('drug_categories', ['name' => 'Antibiotics']);
        $this->assertDatabaseHas('drug_mg', ['mg_value' => '500mg']);
        
        // Create a user with admin role (since pharmacy routes are defined in admin.php and require admin role)
        $user = User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Acting as the admin user - make a simple request first to initialize state
        $this->actingAs($user)->get('/admin/dashboard');

        // Acting as the admin user
        $response = $this->actingAs($user)->post('/admin/pharmacy/drugs/create', [
            'name' => 'Test Drug',
            'generic_name' => 'Test Generic',
            'category' => 'Antibiotics', // This now exists because of seeding
            'strength_mg' => '500mg', // This now exists because of seeding
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
        ]);

        // Check for validation errors
        $response->assertSessionHasNoErrors();

        // Check that the response is a redirect (302)
        $response->assertStatus(302);

        // Assert the drug was created in the database
        $this->assertDatabaseHas('drugs', [
            'name' => 'Test Drug',
            'category' => 'Antibiotics',
        ]);
    }

    #[Test]
    public function it_can_receive_stock()
    {
        // Create a user with admin role (since pharmacy routes are defined in admin.php and require admin role)
        $user = User::create([
            'name' => 'Test Admin 2',
            'email' => 'admin2@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Acting as the admin user - make a simple request first to initialize state
        $this->actingAs($user)->get('/admin/dashboard');

        // Create a drug
        $drug = Drug::create([
            'name' => 'Test Drug 2',
            'category' => 'Antibiotics', // This now exists because of seeding
            'strength_mg' => '200mg', // This now exists because of seeding
            'unit_price' => 30.00,
            'is_controlled' => false,
        ]);

        // Acting as the admin user
        $response = $this->actingAs($user)->post('/admin/pharmacy/stock/receive', [
            'drug_id' => $drug->id,
            'supplier_id' => null,
            'received_quantity' => 100,
            'expiry_date' => '2026-12-31',
        ]);

        // Check for validation errors
        $response->assertSessionHasNoErrors();

        // Check that the response is a successful creation (201)
        $response->assertStatus(201);

        // Assert the drug batch was created in the database
        $this->assertDatabaseHas('drug_batches', [
            'drug_id' => $drug->id,
            'received_quantity' => 100,
        ]);
    }
}