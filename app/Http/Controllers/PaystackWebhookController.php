<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\PaymentService;

class PaystackWebhookController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handleWebhook(Request $request)
    {
        // 1. Verify Signature
        $secret = config('services.paystack.secret_key');
        $signature = $request->header('x-paystack-signature');
        $payload = $request->getContent();

        if (!$signature || hash_hmac('sha512', $payload, $secret) !== $signature) {
            Log::warning("[PaystackWebhook] Invalid Signature.");
            return response()->json(['status' => 'error', 'message' => 'Invalid Signature'], 401);
        }

        // 2. Handle Event
        $event = $request->input('event');
        
        Log::info("[PaystackWebhook] Processing Event: " . $event);

        if ($event === 'charge.success') {
            $reference = $request->input('data.reference');
            
            try {
                // Delegate to the Service to handle DB updates, Appointments, etc.
                $this->paymentService->handleVerification($reference);
                Log::info("[PaystackWebhook] Successfully processed: " . $reference);
                return response()->json(['status' => 'success'], 200);
            } catch (\Exception $e) {
                Log::error("[PaystackWebhook] Error processing: " . $e->getMessage());
                return response()->json(['status' => 'error'], 500);
            }
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}