<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use App\Services\PaymentService;

class PaystackWebhookController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle Paystack webhook events
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleWebhook(Request $request)
    {
        // ✅ Step 1: Get signature header from Paystack
        $signature = $request->header('X-Paystack-Signature');

        // ✅ Step 2: Compare with your secret key
        $secret = config('services.paystack.secret_key');
        $computedSignature = hash_hmac('sha512', $request->getContent(), $secret);

        // Use hash_equals to prevent timing attacks
        if (!$signature || !hash_equals($computedSignature, $signature)) {
            Log::warning('⚠️ Invalid Paystack webhook signature.');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // Get the event data
        $payload = $request->all();
        $event = $payload['event'] ?? null;
        
        // Log the webhook event for debugging
        Log::info('Paystack webhook event received: ' . $event, $payload);
        
        // Handle different event types
        switch ($event) {
            case 'charge.success':
                return $this->handleChargeSuccess($payload);
            case 'transfer.success':
                return $this->handleTransferSuccess($payload);
            case 'invoice.success':
                return $this->handleInvoiceSuccess($payload);
            default:
                // Event not handled
                return response()->json(['message' => 'Event not handled'], 200);
        }
    }
    
    /**
     * Handle successful charge events
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function handleChargeSuccess($payload)
    {
        $data = $payload['data'] ?? [];
        $reference = $data['reference'] ?? null;
        
        if (!$reference) {
            return response()->json(['error' => 'Reference not found'], 400);
        }
        
        // Check if payment already exists
        $payment = Payment::where('reference', $reference)->first();
        
        if ($payment) {
            // Payment already exists, update if necessary
            $payment->update([
                'status' => 'paid',
                'method' => 'card_online', // Assuming card payment for charge.success
            ]);
        } else {
            // Create new payment record
            $payment = Payment::create([
                'user_id' => $data['metadata']['user_id'] ?? $data['metadata']['admin_id'] ?? null,
                'amount' => $data['amount'] / 100, // Convert from kobo to NGN
                'method' => 'card_online',
                'status' => 'paid',
                'reference' => $reference,
                'transaction_date' => now(),
                'clinic_id' => $data['metadata']['clinic_id'] ?? null,
                'consultation_id' => $data['metadata']['consultation_id'] ?? null,
            ]);
        }
        
        // If this payment is for a consultation, finalize the appointment
        if ($payment->consultation_id) {
            Log::info("Finalizing appointment for consultation: " . $payment->consultation_id);
            try {
                $this->paymentService->finalizeAppointment($payment);
            } catch (\Exception $e) {
                Log::error("Error finalizing appointment: " . $e->getMessage());
            }
        }
        
        Log::info("✅ Payment confirmed via webhook: {$reference}");
        return response()->json(['message' => 'Charge success handled'], 200);
    }
    
    /**
     * Handle successful transfer events (for DVA deposits)
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function handleTransferSuccess($payload)
    {
        $data = $payload['data'] ?? [];
        $reference = $data['reference'] ?? null;
        
        if (!$reference) {
            return response()->json(['error' => 'Reference not found'], 400);
        }
        
        // For DVA deposits, we might want to credit the hospital wallet
        // This would depend on your specific implementation
        
        // Log the transfer success for now
        Log::info('Transfer success event received', $data);
        
        return response()->json(['message' => 'Transfer success handled'], 200);
    }
    
    /**
     * Handle successful invoice events
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function handleInvoiceSuccess($payload)
    {
        $data = $payload['data'] ?? [];
        $reference = $data['reference'] ?? null;
        
        if (!$reference) {
            return response()->json(['error' => 'Reference not found'], 400);
        }
        
        // Handle invoice success event
        Log::info('Invoice success event received', $data);
        
        return response()->json(['message' => 'Invoice success handled'], 200);
    }
}