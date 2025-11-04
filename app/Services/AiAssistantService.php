<?php

namespace App\Services;

use Gemini\Client as GeminiClient;
use Gemini\Factory as GeminiFactory;
use GuzzleHttp\Client as GuzzleClient;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AiAssistantService
{
    private $geminiClient;
    private $apiKey;
    private $model;

    public function __construct()
    {
        // --- YOUR WORKING CODE (TYPO FIXED) ---
        $this->apiKey = env('OPENAI_API_KEY');
        $this->model = 'gemini-flash-latest'; // Kept as requested
        
        $httpClient = new GuzzleClient([
            'timeout' => 60, // Increased timeout for file uploads
        ]);
        
        $this->geminiClient = (new GeminiFactory())
            ->withApiKey($this->apiKey)
            ->withHttpClient($httpClient)
            ->make();
        // --- END OF YOUR WORKING CODE ---
    }
    
    /**
     * Helper to clean JSON and strip markdown.
     */
    public function cleanJsonResponse(string $response): string
    {
        $response = preg_replace('/^```[a-z]*\s*/i', '', $response);
        $response = preg_replace('/\s*```$/', '', $response);
        return trim($response);
    }

    /**
     * Helper to build a simple string from the chat history.
     */
    private function buildHistoryPrompt(array $history): string
    {
        $promptHistory = "--- Start of Chat History ---\n";
        foreach ($history as $turn) {
            if ($turn['role'] === 'user') {
                $promptHistory .= "User: " . $turn['content'] . "\n";
            } else {
                $promptHistory .= "AI: " . $turn['content'] . "\n";
            }
        }
        $promptHistory .= "--- End of Chat History ---";
        return $promptHistory;
    }
    
    /**
     * Helper to classify the user's intent before routing.
     */
    public function classifyIntent(string $naturalLanguageQuery, array $history = [])
    {
        $systemPrompt = "You are an intent classifier for a clinic AI. Your ONLY job is to classify the user's *newest* query.
        
        Use the 'Chat History' for context, but only classify the *last* thing the user said.
        
        The value MUST be one of these six options:
        1. 'scheduling': for finding doctor availability, checking schedules, or booking.
        2. 'reminder': for setting alerts, creating a reminder, or scheduling a notification.
        3. 'medical_query': for medical questions, symptoms, drug information, treatment, or analyzing a lab result/image.
        4. 'general_chat': for 'hi', 'hello', 'thanks', 'how are you', or other chit-chat.
        5. 'get_doctor_schedule': for asking about a specific doctor's working hours or full schedule.
        6. 'get_doctor_appointments': for asking about a doctor's booked appointments or patient list for the day.

        Examples:
        - User: 'Is Dr. Smith free at 3?' -> {\"intent\": \"scheduling\"}
        - User: 'Remind me to call patient brown at 4pm' -> {\"intent\": \"reminder\"}
        - User: 'What are the side effects of amoxicillin?' -> {\"intent\": \"medical_query\"}
        - User: 'What does this lab result show?' -> {\"intent\": \"medical_query\"}
        - User: 'Hey there' -> {\"intent\": \"general_chat\"}
        - User: 'What time is Dr. Victor iwasanmi scheduled for, for tomorrow?' -> {\"intent\": \"get_doctor_schedule\"}
        - User: 'Who is Dr. Jane seeing today?' -> {\"intent\": \"get_doctor_appointments\"}
        
        **CONTEXT EXAMPLE:**
        User: 'Is a doctor free tomorrow?'
        AI: 'Yes, I found...'
        User: 'and for 40 min?'
        JSON: {\"intent\": \"scheduling\"}
        ";
        
        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nNew User Query: " . $naturalLanguageQuery;
            
            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); 
            $structuredQuery = json_decode($cleanedResponse, true);

            if (json_last_error() === JSON_ERROR_NONE && isset($structuredQuery['intent'])) {
                return $structuredQuery['intent'];
            }
            
            return 'general_chat'; 

        } catch (\Exception $e) {
            Log::error("AI Intent Classification failed: " . $e->getMessage());
            return 'general_chat'; 
        }
    }

    /**
     * Gets structured scheduling query.
     */
    public function getStructuredSchedulingQuery(string $naturalLanguageQuery, array $contextData, array $history = [])
    {
        $systemPrompt = "You are the ClinicalPro Smart Query Assistant. Your ONLY job is to translate a user's request into a clean, precise JSON object for a scheduling API.
        
        Available Specializations: " . implode(', ', $contextData['specializations']) . ". Current date is: " . now()->format('Y-m-d') . ".
        
        **NEW RULE:** Pay attention to the 'Chat History'. If the user's *new query* is a follow-up (e.g., 'from 2 pm please'), you MUST get the missing context (like the date or duration) from the history.
        
        Rules: 1. `intent` MUST be 'find_doctor_availability'. 2. `target_datetime` must be 'Y-m-d H:i:s', defaulting to '09:00:00'. 3. `duration_minutes` is an integer (15, 30, 40, 45, 60, 90), defaulting to 30.
        
        **CONTEXT EXAMPLE (Today is 2025-11-03):**
        User: 'Which doctor is available tomorrow?'
        AI: 'I found Dr. Smith...'
        User: 'from 2 pm please for a 40 min session'
        JSON: {\"intent\": \"find_doctor_availability\", \"target_datetime\": \"2025-11-04 14:00:00\", \"duration_minutes\": 40, \"specialization\": null}
        ";
        
        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser request: " . $naturalLanguageQuery;

            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); 
            $structuredQuery = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($structuredQuery['intent']) || $structuredQuery['intent'] !== 'find_doctor_availability') {
                return null; 
            }
            return $structuredQuery;

        } catch (\Exception $e) {
            Log::error("AI Scheduling API call failed: " . $e->getMessage());
            return null; 
        }
    }

    /**
     * Gets structured reminder query.
     */
    public function getStructuredReminderQuery(string $naturalLanguageQuery, array $history = [])
    {
        $currentDate = Carbon::now()->format('Y-m-d');
        $systemPrompt = "You are the ClinicalPro Reminder Assistant. Your ONLY job is to translate a user's request into a clean, precise JSON object.
        
        The current date is: {$currentDate}.
        
        **NEW RULE:** Pay attention to the 'Chat History' for context if the new query is a follow-up.
        
        Rules: 1. `intent` MUST be 'create_reminder'. 2. `scheduled_at` must be 'Y-m-d H:i:s'. 3. `message` must be a concise reminder text.";
        
        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser request: " . $naturalLanguageQuery;
            
            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); 
            $structuredQuery = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($structuredQuery['intent']) || $structuredQuery['intent'] !== 'create_reminder') {
                return null;
            }
            return $structuredQuery;

        } catch (\Exception $e) {
            Log::error("AI Reminder API call failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Handles medical questions for doctors.
     */
    public function getMedicalQueryResponse(string $naturalLanguageQuery, array $history = [], $file = null)
    {
        $systemPrompt = "You are a professional, helpful medical AI assistant (ClinicalPro AI).
        Your user is a verified doctor or nurse.
        Your job is to provide concise, accurate, and safe medical information for clinical support.
        
        **NEW RULE:** Pay attention to the 'Chat History' for context.
        **NEW RULE:** If an image of a lab result or medical report is provided, analyze it and answer the user's question about it.
        
        RULES:
        - You are a medical expert co-pilot.
        - When the user (a licensed doctor) provides a diagnosis, you MUST provide detailed treatment options, including common drug names, dosages, and protocols.
        - The user is a professional, so provide direct, clinical-level answers.
        - Answer the user's question directly.
        ";
        
        try {
            $historyString = $this->buildHistoryPrompt($history);
            $fullTextPrompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser question: " . $naturalLanguageQuery;

            if ($file) {
                // 1. If there is a file, we build the $content array
                $content = [];
                $content[] = ['text' => $fullTextPrompt];
                $content[] = [
                    'inline_data' => [
                        'mime_type' => $file->getMimeType(),
                        'data' => base64_encode(file_get_contents($file->getRealPath()))
                    ]
                ];
                
                $modelToUse = 'gemini-pro-vision';
                
                $response = $this->geminiClient->generativeModel($modelToUse)->generateContent($content);

            } else {
                // 1. If there is NO file, we send a simple STRING
                //    to the working 'gemini-flash-latest' model.
                $response = $this->geminiClient->generativeModel($this->model)->generateContent($fullTextPrompt);
            }
            
            $responseText = $response->text();
            $responseText = str_replace(['**', '*'], '', $responseText);
            
            return $responseText;

        } catch (\Exception $e) {
            Log::error("AI Medical Query failed: " . $e->getMessage());
            return "I'm sorry, I am unable to look up that medical information at this time.";
        }
    }
    
    /**
     * Extracts doctor and date for reading a schedule.
     */
    public function getStructuredScheduleInfoQuery(string $naturalLanguageQuery, array $history = [])
    {
        $today = Carbon::now();
        $tomorrow = Carbon::now()->addDay();
        $friday = Carbon::now()->next(Carbon::FRIDAY);

        $systemPrompt = "You are a query parser. Your ONLY job is to extract a doctor's name and a target date from a user's query. Return only a clean JSON object.
        
        The current date is: {$today->format('Y-m-d')}.
        
        **NEW RULE:** Pay attention to the 'Chat History' for context.
        
        Rules:
        1. `intent` MUST be 'get_doctor_schedule'.
        2. `doctor_name` must be the proper name extracted (e.g., 'Victor Iwasanmi', 'Jane', 'Bimsara').
        3. `target_date` must be 'Y-m-d'.
        
        EXAMPLES:
        User: 'What time is Dr. Victor iwasanmi scheduled for, for tomorrow?'
        JSON: {\"intent\": \"get_doctor_schedule\", \"doctor_name\": \"Victor iwasanmi\", \"target_date\": \"{$tomorrow->format('Y-m-d')}\"} 
        User: 'What is Dr. Jane's schedule on Friday?'
        JSON: {\"intent\": \"get_doctor_schedule\", \"doctor_name\": \"Jane\", \"target_date\": \"{$friday->format('Y-m-d')}\"}
        User: 'Show me Dr. Bimsara's hours today.'
        JSON: {\"intent\": \"get_doctor_schedule\", \"doctor_name\": \"Bimsara\", \"target_date\": \"{$today->format('Y-m-d')}\"}
        User: 'Do you know Dr. Okon's schedule?'
        JSON: {\"intent\": \"get_doctor_schedule\", \"doctor_name\": \"Okon\", \"target_date\": \"{$today->format('Y-m-d')}\"}
        ";
        
        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser query: " . $naturalLanguageQuery;

            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); 
            
            $structuredQuery = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($structuredQuery['intent']) || $structuredQuery['intent'] !== 'get_doctor_schedule') {
                return null;
            }
            return $structuredQuery;

        } catch (\Exception $e) {
            Log::error("AI Schedule Info API call failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Extracts doctor and date for reading a doctor's booked appointments.
     */
    public function getStructuredAppointmentInfoQuery(string $naturalLanguageQuery, array $history = [])
    {
        $today = Carbon::now();
        $tomorrow = Carbon::now()->addDay();

        $systemPrompt = "You are a query parser. Your ONLY job is to extract a doctor's name and a target date from a user's query. Return only a clean JSON object.
        
        The current date is: {$today->format('Y-m-d')}.
        
        **NEW RULE:** Pay attention to the 'Chat History' for context.
        
        Rules:
        1. `intent` MUST be 'get_doctor_appointments'.
        2. `doctor_name` must be the proper name extracted.
        3. `target_date` must be 'Y-m-d'.
        
        EXAMPLES:
        User: 'What appointments does Dr. Victor iwasanmi have tomorrow?'
        JSON: {\"intent\": \"get_doctor_appointments\", \"doctor_name\": \"Victor iwasanmi\", \"target_date\": \"{$tomorrow->format('Y-m-d')}\"} 
        User: 'Who is Dr. Jane seeing today?'
        JSON: {\"intent\": \"get_doctor_appointments\", \"doctor_name\": \"Jane\", \"target_date\": \"{$today->format('Y-m-d')}\"}
        User: 'Show me Dr. Bimsara's patient list for tomorrow.'
        JSON: {\"intent\": \"get_doctor_appointments\", \"doctor_name\": \"Bimsara\", \"target_date\": \"{$tomorrow->format('Y-m-d')}\"}
        ";
        
        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser query: " . $naturalLanguageQuery;

            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); 
            $structuredQuery = json_decode($cleanedResponse, true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($structuredQuery['intent']) || $structuredQuery['intent'] !== 'get_doctor_appointments') {
                return null;
            }
            return $structuredQuery;

        } catch (\Exception $e) {
            Log::error("AI Appointment Info API call failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Handles general chat.
     */
    public function getGeneralChatResponse(string $query, array $history = [])
    {
        $systemPrompt = "You are ClinicalPro AI, a friendly, warm, and professional conversational assistant.
        The user is saying hi, thanks, or making simple small talk.
        
        RULES:
        - Be warm, welcoming, and concise.
        - **NEW RULE:** If the user just says 'thanks', 'okay', 'got it', 'alright', or 'perfect', just give a *very short, simple* acknowledgment (like 'You're welcome!', 'Great!', or 'Sounds good!'). Do NOT offer help again.
        - For *greetings* ('hi', 'hey'), greet them back and gently remind them of your skills.
        
        EXAMPLES:
        - User: 'hi'
          Response: 'Hello there! I'm here if you need to find a doctor, set a reminder, or look up medical info.'
        - User: 'thanks a lot'
          Response: 'You're very welcome! Let me know if you need anything else.'
        - User: 'Okay'
          Response: 'Great!'
        - User: 'perfect'
          Response: 'Sounds good!'
        - User: 'are you a robot?'
          Response: 'I'm an AI assistant for ClinicalPro, here to help the medical team!'
        - User: 'good morning'
          Response: 'Good morning! I'm here to help with scheduling or reminders today.'
        ";

        try {
            $historyString = $this->buildHistoryPrompt($history); 
            $prompt = $systemPrompt . "\n\n" . $historyString . "\n\nUser: " . $query;
            
            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); 
            
            $responseText = $response->text();
            $responseText = str_replace(['**', '*'], '', $responseText); 
            
            return $responseText;

        } catch (\Exception $e) {
            Log::error("AI General Chat failed: " . $e->getMessage());
            return "I'm sorry, I'm having trouble connecting to my brain right now!";
        }
    }

    /**
     * This is our "Specialist" for reading clinical notes.
     */
    public function getStructuredNoteData(string $noteText)
    {
        $systemPrompt = "You are a clinical data extraction tool. Your ONLY job is to read the following doctor's note and extract key information.
        
        You MUST return ONLY a single, clean JSON object with the following keys:
        1. `diagnosis`: (string) The primary diagnosis.
        2. `medications`: (array) A list of medication objects. Each object must have `name`, `dosage`, and `instructions`.
        3. `follow_up`: (string) The follow-up plan or date.
        
        RULES:
        - If you cannot find information for a key, return an empty string \"\" or an empty array [].
        - Do not add any extra text, conversation, or markdown. ONLY the JSON object.

        --- EXAMPLE NOTE ---
        Patient presents with a sore throat and fever. Diagnosis is acute pharyngitis. 
        I'm prescribing Amoxicillin 500mg, 3 times a day for 7 days. Also, Tylenol 650mg every 6 hours as needed for fever.
        Patient should follow up in one week if symptoms do not improve.
        
        --- EXAMPLE JSON OUTPUT ---
        {
          \"diagnosis\": \"Acute Pharyngitis\",
          \"medications\": [
            {
              \"name\": \"Amoxicillin\",
              \"dosage\": \"500mg\",
              \"instructions\": \"3 times a day for 7 days\"
            },
            {
              \"name\": \"Tylenol\",
              \"dosage\": \"650mg\",
              \"instructions\": \"every 6 hours as needed for fever\"
            }
          ],
          \"follow_up\": \"Follow up in one week if symptoms do not improve.\"
        }
        ";
        
        try {
            $prompt = $systemPrompt . "\n\n--- DOCTOR'S NOTE TO ANALYZE ---\n" . $noteText;
            
            $response = $this->geminiClient->generativeModel($this->model)->generateContent($prompt); // TYPO FIX
            $aiResponseText = $response->text();
            
            $cleanedResponse = $this->cleanJsonResponse($aiResponseText); // TYPO FIX
            $structuredData = json_decode($cleanedResponse, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $structuredData;
            }
            
            Log::error("AI Note Extraction failed to parse JSON: " . $cleanedResponse);
            return null;

        } catch (\Exception $e) {
            Log::error("AI Note Extraction API call failed: " . $e->getMessage());
            return null;
        }
    }
}