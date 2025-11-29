<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;
    protected $provider;

    public function __construct()
    {
        // We load these from .env via config
        $this->apiKey = config('services.sms.key'); 
        $this->senderId = config('services.sms.sender_id', 'ClinicalPro');
        $this->baseUrl = config('services.sms.url', 'https://api.ng.termii.com/api/sms/send'); 
        $this->provider = config('services.sms.provider', 'log'); // Default to 'log' for safety
    }

    /**
     * Send a message (SMS or WhatsApp).
     *
     * @param string $phone The user's input phone (e.g., 0803...)
     * @param string $message The text content
     * @param string $channel 'sms' or 'whatsapp'
     * @return bool
     */
    public function send(string $phone, string $message, string $channel = 'sms'): bool
    {
        // 1. SMART FORMATTING: Convert 080... to 23480...
        $formattedPhone = $this->formatPhoneNumber($phone);

        // 2. LOG DRIVER (For Localhost Testing)
        // If you haven't paid for SMS yet, this lets you see it in laravel.log
        if ($this->provider === 'log') {
            Log::info("[SmsService] 📱 MOCK {$channel} sent to {$formattedPhone}: \"{$message}\"");
            return true;
        }

        // 3. PRODUCTION DRIVER (e.g., Termii)
        try {
            // This payload structure works for Termii. 
            // If you use Twilio, the keys just need to change slightly.
            $payload = [
                "to" => $formattedPhone,
                "from" => $this->senderId,
                "sms" => $message,
                "type" => "plain",
                "channel" => $channel === 'whatsapp' ? 'whatsapp' : 'generic',
                "api_key" => $this->apiKey,
            ];

            $response = Http::post($this->baseUrl, $payload);

            if ($response->successful()) {
                Log::info("[SmsService] ✅ {$channel} sent successfully to {$formattedPhone}");
                return true;
            } else {
                Log::error("[SmsService] ❌ Failed: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("[SmsService] 💥 Exception: " . $e->getMessage());
            return false;
        }
    }

    /**
     * SMART FORMATTER: Handles the 080 -> 234 conversion
     */
    private function formatPhoneNumber($phone)
    {
        // 1. Remove any spaces, dashes, or plus signs
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // 2. Handle Nigerian Format (080, 081, 070, 090, 091)
        // If it starts with '0', we swap it for '234'
        if (substr($phone, 0, 1) === '0') {
            return '234' . substr($phone, 1);
        }
        
        // 3. If it already starts with 234, keep it
        // 4. If it's international (but missing prefix), you might need more logic, 
        //    but for a Nigerian clinic, this covers 99% of cases.
        
        return $phone;
    }
}