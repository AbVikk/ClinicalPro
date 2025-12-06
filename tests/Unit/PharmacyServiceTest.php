<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Drug;
use App\Models\DrugBatch;
use App\Models\Clinic;
use App\Models\ClinicInventory;
use App\Models\Payment;
use App\Services\PharmacyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PharmacyServiceTest extends TestCase
{
    use RefreshDatabase; // Resets DB after every test

    protected $pharmacyService;
    protected $pharmacist;
    protected $drug;
    protected $clinic;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pharmacyService = new PharmacyService();

        // 1. Setup World
        // FIX: Added 'address' to satisfy database strictness
        $this->clinic = Clinic::updateOrCreate(
            ['name' => 'Test Clinic'],
            [
                'address' => '123 Test Street, Lagos', 
                'is_physical' => true
            ]
        );
        
        $this->pharmacist = User::updateOrCreate(
            ['email' => 'pharm@test.com'],
            [
                'name' => 'Test Pharmacist',
                'password' => bcrypt('password'),
                'role' => 'clinic_pharmacist',
                'clinic_id' => $this->clinic->id
            ]
        );

        $this->drug = Drug::create([
            'name' => 'Paracetamol',
            'category' => 'Analgesic',
            'strength_mg' => '500mg',
            'unit_price' => 100.00, // ₦100
            'is_controlled' => false
        ]);

        // 2. Add Stock (Batch of 50)
        $batch = DrugBatch::create([
            'drug_id' => $this->drug->id,
            'batch_uuid' => 'BATCH-' . uniqid(),
            'received_quantity' => 50,
            'expiry_date' => now()->addYear()
        ]);

        ClinicInventory::updateOrCreate(
            ['clinic_id' => $this->clinic->id, 'batch_id' => $batch->id],
            ['stock_level' => 50]
        );
    }

    #[Test]
    public function it_calculates_local_stock_correctly()
    {
        // Act
        $results = $this->pharmacyService->searchDrugs('Paracetamol', $this->pharmacist);

        // Assert
        $this->assertEquals(50, $results->first()['stock']);
    }

    #[Test]
    public function it_processes_a_sale_and_deducts_stock()
    {
        // Arrange: Sell 5 items
        $saleData = [
            'patient_id' => null,
            'payment_method' => 'cash',
            'total' => 500,
            'items' => [
                [
                    'id' => $this->drug->id,
                    'name' => $this->drug->name,
                    'qty' => 5,
                    'price' => 100
                ]
            ]
        ];

        // Act
        $result = $this->pharmacyService->processSale($this->pharmacist, $saleData);

        // Assert: Order Created
        $this->assertDatabaseHas('pharmacy_orders', ['id' => $result['order']->id, 'status' => 'completed']);
        
        // Assert: Payment Recorded
        $this->assertDatabaseHas('payments', ['amount' => 500, 'status' => 'paid']);

        // Assert: Stock Deducted (50 - 5 = 45)
        $this->assertDatabaseHas('clinic_inventories', [
            'clinic_id' => $this->clinic->id,
            'stock_level' => 45
        ]);
    }

    #[Test]
    public function it_prevents_selling_more_than_available_stock()
    {
        $this->expectException(\Exception::class);

        // Arrange: Try to sell 51 items (We only have 50)
        $saleData = [
            'payment_method' => 'cash',
            'total' => 5100,
            'items' => [
                [
                    'id' => $this->drug->id,
                    'name' => $this->drug->name,
                    'qty' => 51, // <--- Too many!
                    'price' => 100
                ]
            ]
        ];

        // Act
        $this->pharmacyService->processSale($this->pharmacist, $saleData);
        
        // Assert: The Transaction should roll back, stock should remain 50
        $this->assertDatabaseHas('clinic_inventories', ['stock_level' => 50]);
    }
}