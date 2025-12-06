<?php

namespace App\Services;

use App\Models\User;
use App\Models\Drug;
use App\Models\PharmacyOrder;
use App\Models\PharmacyOrderItem;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ClinicInventory;
use App\Models\DrugBatch;
use App\Models\StockTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PharmacyService
{
    /**
     * Search for drugs based on user role scope.
     */
    public function searchDrugs(string $query, User $user, int $limit = 20)
    {
        // Base query for the drug catalog
        $drugQuery = Drug::where('name', 'like', '%' . $query . '%')
            ->limit($limit);

        $drugs = $drugQuery->get();

        // Calculate stock based on role
        $results = $drugs->map(function ($drug) use ($user) {
            $stockLevel = 0;

            if ($user->role === 'primary_pharmacist') {
                // Global Stock: Sum of all batches in all clinics
                $stockLevel = ClinicInventory::whereHas('batch', function ($q) use ($drug) {
                    $q->where('drug_id', $drug->id);
                })->sum('stock_level');
            } elseif ($user->clinic_id) {
                // Local Stock: Sum of batches in THIS clinic only
                $stockLevel = ClinicInventory::where('clinic_id', $user->clinic_id)
                    ->whereHas('batch', function ($q) use ($drug) {
                        $q->where('drug_id', $drug->id);
                    })->sum('stock_level');
            }

            return [
                'id' => (int) $drug->id,
                'name' => $drug->name,
                'price' => (float) $drug->unit_price,
                'stock' => (int) $stockLevel,
                'category' => (string) $drug->category,
                'strength' => (string) $drug->strength_mg,
                'is_controlled' => (bool) $drug->is_controlled
            ];
        });

        return $results;
    }

    /**
     * Process a sale transaction atomically.
     */
    public function processSale(User $pharmacist, array $data)
    {
        return DB::transaction(function () use ($pharmacist, $data) {
            
            // 0. SECURITY CHECK: Pre-validate Stock
            foreach ($data['items'] as $item) {
                $currentStock = $this->checkStock($item['id'], $pharmacist);
                if ($currentStock < $item['qty']) {
                    throw new \Exception("Insufficient stock for {$item['name']}. Available: {$currentStock}, Requested: {$item['qty']}");
                }
            }

            // 1. Create Pharmacy Order
            $order = new PharmacyOrder();
            $order->pharmacist_id = $pharmacist->id;
            $order->clinic_id = ($pharmacist->role !== 'primary_pharmacist') ? $pharmacist->clinic_id : ($pharmacist->clinic_id ?? 1); 
            $order->patient_id = $data['patient_id'] ?? null;
            $order->prescription_id = $data['prescription_id'] ?? null;
            $order->total_amount = $data['total'];
            
            $isPaystack = ($data['payment_method'] === Payment::METHOD_PAYSTACK);
            $order->status = $isPaystack ? 'pending' : 'completed';
            $order->save();

            // 2. Create Payment Record
            $payment = new Payment();
            $payment->user_id = $data['patient_id'] ?? $pharmacist->id; 
            $payment->order_id = $order->id;
            $payment->clinic_id = $order->clinic_id;
            $payment->amount = $data['total'];
            $payment->method = $this->mapPaymentMethod($data['payment_method']);
            $payment->status = $isPaystack ? Payment::STATUS_PENDING : Payment::STATUS_PAID;
            $payment->reference = 'PHARM-' . $order->id . '-' . time();
            $payment->transaction_date = now();
            $payment->save();

            // 3. Process Inventory & Items (Only if paid immediately)
            if ($payment->status === Payment::STATUS_PAID) {
                $this->finalizeOrderItems($order, $data['items'], $pharmacist);
            }
            
            // 4. GLOBAL UPDATE: Clear Caches
            $this->clearSystemCaches();

            return [
                'order' => $order,
                'payment' => $payment
            ];
        });
    }

    private function mapPaymentMethod(string $method): string
    {
        return match ($method) {
            'cash' => Payment::METHOD_CASH,
            'pos' => Payment::METHOD_POS,
            'transfer' => Payment::METHOD_BANK_TRANSFER,
            'paystack' => Payment::METHOD_PAYSTACK,
            default => Payment::METHOD_CASH,
        };
    }

    public function finalizeOrderItems(PharmacyOrder $order, array $items, User $pharmacist)
    {
        foreach ($items as $item) {
            $orderItem = new PharmacyOrderItem();
            $orderItem->pharmacy_order_id = $order->id;
            $orderItem->drug_id = $item['id'];
            $orderItem->quantity = $item['qty'];
            $orderItem->unit_price = $item['price'];
            $orderItem->total_price = round($item['qty'] * $item['price'], 2);
            $orderItem->save();

            $this->deductStock($item['id'], $item['qty'], $pharmacist);

            if ($order->prescription_id) {
                $this->updatePrescriptionItemStatus($order->prescription_id, $item['id']);
            }
        }

        if ($order->prescription_id) {
            $this->checkPrescriptionCompletion($order->prescription_id);
        }
    }

    private function checkStock($drugId, User $user)
    {
        if ($user->role === 'primary_pharmacist') {
            return ClinicInventory::whereHas('batch', fn($q) => $q->where('drug_id', $drugId))->sum('stock_level');
        } elseif ($user->clinic_id) {
            return ClinicInventory::where('clinic_id', $user->clinic_id)
                ->whereHas('batch', fn($q) => $q->where('drug_id', $drugId))->sum('stock_level');
        }
        return 0;
    }

    private function deductStock($drugId, $qty, User $user)
    {
        // FIX: Join with drug_batches to order by expiry_date
        $query = ClinicInventory::join('drug_batches', 'clinic_inventories.batch_id', '=', 'drug_batches.id')
            ->where('drug_batches.drug_id', $drugId)
            ->where('clinic_inventories.stock_level', '>', 0)
            ->orderBy('drug_batches.expiry_date', 'asc') // FEFO Logic
            ->select('clinic_inventories.*'); // Important to select only inventory fields

        if ($user->role !== 'primary_pharmacist' && $user->clinic_id) {
            $query->where('clinic_inventories.clinic_id', $user->clinic_id);
        }

        $inventories = $query->get();
        $remainingQty = $qty;

        foreach ($inventories as $inventory) {
            if ($remainingQty <= 0) break;

            $deduct = min($remainingQty, $inventory->stock_level);
            $inventory->decrement('stock_level', $deduct);
            $remainingQty -= $deduct;
        }
        
        if ($remainingQty > 0) {
            Log::warning("Stock anomaly: Oversold drug ID {$drugId} by {$remainingQty}");
        }
    }

    private function updatePrescriptionItemStatus($prescriptionId, $drugId)
    {
        PrescriptionItem::where('prescription_id', $prescriptionId)
            ->where('drug_id', $drugId)
            ->update(['fulfillment_status' => 'dispensed']);
    }

    private function checkPrescriptionCompletion($prescriptionId)
    {
        $pendingCount = PrescriptionItem::where('prescription_id', $prescriptionId)
            ->where('fulfillment_status', '!=', 'dispensed')
            ->count();

        if ($pendingCount === 0) {
            Prescription::where('id', $prescriptionId)->update(['status' => 'filled']);
        }
    }
    
    private function clearSystemCaches()
    {
        Cache::forget('admin_stats_total_payments_month');
        Cache::forget('admin_stats_total_disbursements_month');
    }

    public function getDashboardMetrics(User $user)
    {
        $clinicId = ($user->role !== 'primary_pharmacist') ? $user->clinic_id : null;

        $prescriptionQuery = Prescription::where('status', 'active');
        $orderQuery = PharmacyOrder::where('status', 'completed');

        if ($clinicId) {
            $lowStockCount = ClinicInventory::where('clinic_id', $clinicId)
                ->where('stock_level', '<', 50)
                ->count();
            $orderQuery->where('clinic_id', $clinicId);
        } else {
            $lowStockCount = Drug::whereHas('batches', function($q) {
                $q->where('received_quantity', '<', 50);
            })->count();
        }

        $totalSales = $orderQuery->sum('total_amount');
        $salesCount = $orderQuery->count();
        $activeScripts = $prescriptionQuery->count();
        
        return [
            'totalDrugs' => Drug::count(),
            'totalBatches' => DrugBatch::count(),
            'activePrescriptions' => $activeScripts,
            'lowStockItems' => $lowStockCount,
            'expiringSoon' => DrugBatch::where('expiry_date', '<=', now()->addDays(30))->count(),
            'totalSalesAmount' => $totalSales,
            'totalSalesCount' => $salesCount,
            'pendingRequests' => StockTransfer::where('status', 'requested')->count()
        ];
    }
}