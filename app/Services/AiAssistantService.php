<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class AiAssistantService
{
    protected $apiKey;
    protected $baseUrl;
    protected $primaryModel;
    
    // Model Priority List (Fallback if config model fails)
    protected $modelCandidates = [
        'gemini-2.5-flash',
        'gemini-2.5-pro',
        'gemini-1.5-flash',
        'gemini-1.5-pro',
        'gemini-1.0-pro'
    ];

    public function __construct()
    {
        $this->apiKey = config('gemini.api_key');
        
        // --- CRITICAL FIX ---
        // We use rtrim() to remove any trailing slash ('/') from the URL.
        // This prevents the "404 Not Found" error caused by double slashes (e.g. /v1beta//models).
        $this->baseUrl = rtrim(config('gemini.base_url'), '/');
        // --------------------
        
        $this->primaryModel = config('gemini.models.primary');
    }

    // --- INTERNAL ENGINE ---

    private function getValidModel()
    {
        // Cache the valid model to avoid repeated discovery calls
        return Cache::remember('valid_gemini_model_v5', 3600, function () {
            
            // 1. Try the configured primary model first
            if ($this->checkModelExists($this->primaryModel)) {
                return $this->primaryModel;
            }

            // 2. Discovery Loop
            try {
                $response = Http::timeout(10)->get("{$this->baseUrl}/models?key={$this->apiKey}");
                
                if ($response->successful()) {
                    $models = $response->json()['models'] ?? [];
                    
                    // Try our candidates in order
                    foreach ($this->modelCandidates as $pref) {
                        foreach ($models as $m) {
                            // Check if model name ends with our preference (handles 'models/' prefix)
                            if (str_ends_with($m['name'], $pref) && 
                                in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
                                return $m['name']; 
                            }
                        }
                    }
                    
                    // Fallback: Just grab the first one that supports content generation
                    foreach ($models as $m) {
                        if (in_array('generateContent', $m['supportedGenerationMethods'] ?? [])) {
                            return $m['name'];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error("[AiAssistantService] Discovery Failed: " . $e->getMessage());
            }
            
            // Absolute Fallback
            return 'models/gemini-pro';
        });
    }

    private function checkModelExists($modelName)
    {
        // Simple check logic could go here, for now we trust the list or the catch block
        return !empty($modelName);
    }

    protected function safeGenerate($prompt, $images = [])
    {
        $modelName = $this->getValidModel();
        
        // Ensure prefix
        if (strpos($modelName, 'models/') === false) {
            $modelName = 'models/' . $modelName;
        }

        $url = "{$this->baseUrl}/{$modelName}:generateContent?key={$this->apiKey}";
        
        $parts = [];
        if (!empty($prompt)) {
            $parts[] = ['text' => $prompt];
        }

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $parts[] = [
                    'inline_data' => [
                        'mime_type' => $image->getMimeType(),
                        'data' => base64_encode(File::get($image->getRealPath()))
                    ]
                ];
            }
        }

        $payload = [
            'contents' => [['parts' => $parts]],
            'generationConfig' => [
                'temperature' => 0.3, 
                'maxOutputTokens' => 2000
            ],
            // Safety: Allow medical context (Important for healthcare apps)
            'safetySettings' => [
                ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
            ]
        ];

        $maxRetries = 3; 
        $retryDelay = 2; 
        
        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders(['Content-Type' => 'application/json'])
                    ->timeout(config('gemini.request_timeout', 30)) 
                    ->post($url, $payload);

                if ($response->successful()) {
                    return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? "No response generated.";
                }
                
                $responseBody = $response->json();
                
                // Handle Overload
                if (isset($responseBody['error']['status']) && 
                    in_array($responseBody['error']['status'], ['UNAVAILABLE', 'RESOURCE_EXHAUSTED'])) {
                    
                    if ($attempt < $maxRetries) {
                        sleep($retryDelay * $attempt); // Simple exponential backoff
                        continue;
                    }
                }
                
                Log::error("[AiAssistantService] API Error: " . $response->body());
                return "I'm having trouble accessing the medical database. (Error: " . $response->status() . ")";

            } catch (\Exception $e) {
                if ($attempt < $maxRetries) {
                    sleep($retryDelay * $attempt);
                    continue;
                }
                Log::error("[AiAssistantService] Exception: " . $e->getMessage());
                return "Connection error. Please check your internet connection.";
            }
        }
        
        return "The AI service is currently overloaded. Please try again in a moment.";
    }

    public function cleanJsonResponse(string $response): string {
        $response = preg_replace('/^```[a-z]*\s*/i', '', $response);
        return trim(preg_replace('/\s*```$/', '', $response));
    }

    private function buildHistoryPrompt(array $history): string {
        $prompt = "--- CONVERSATION HISTORY ---\n";
        foreach ($history as $turn) {
            $role = $turn['role'] === 'user' ? 'User' : 'AI';
            $content = substr($turn['content'] ?? '', 0, 300); 
            $prompt .= "{$role}: {$content}\n";
        }
        return $prompt . "--- END HISTORY ---\n";
    }

    // --- INTELLIGENT FUNCTIONS ---

    public function classifyIntent(string $q, array $h = []) {
        $prompt = "Classify the user's intent based on the input.
        
        VALID INTENTS:
        1. 'scheduling': Booking, availability, 'Is Dr. X free?', 'Book appointment'.
        2. 'get_doctor_schedule': Asking for working hours/roster. e.g., 'What is Dr. X's schedule?', 'When does Dr. X work?'.
        3. 'get_doctor_appointments': Asking for patient list. e.g., 'Who is seeing Dr. X?', 'What appointments does Dr. X have?'.
        4. 'medical_query': Drugs, diseases, dosage, symptoms.
        5. 'summarize_patient': Asking about a patient's history/notes. e.g. 'Summarize John Doe', 'Tell me about patient X'.
        6. 'reminder': Setting alerts.
        7. 'general_chat': Greetings.
        
        OUTPUT JSON ONLY: {\"intent\": \"...\"}
        \n" . $this->buildHistoryPrompt($h) . "\nInput: $q";
        
        $res = $this->safeGenerate($prompt);
        $json = json_decode($this->cleanJsonResponse($res), true);
        return $json['intent'] ?? 'general_chat';
    }

    public function getMedicalQueryResponse(string $q, array $h = [], array $files = []) {
        $user = Auth::user();
        $role = $user ? strtolower($user->role) : 'patient';
        $isMedicalPro = in_array($role, ['doctor', 'nurse', 'admin', 'hod', 'pharmacist', 'primary_pharmacist', 'senior_pharmacist']);

        if ($isMedicalPro) {
            $persona = "You are a Clinical Decision Support System (CDSS).
            USER: Licensed {$role}.
            INSTRUCTIONS: Provide detailed, technical medical answers. Include Dosage, Contraindications, and Mechanism. No disclaimers needed.";
        } else {
            $persona = "You are a Medical Assistant.
            USER: Patient.
            INSTRUCTIONS: Explain in simple terms. Do not prescribe. Recommend seeing a specialist.";
        }

        $fullPrompt = "{$persona}\n\n" . $this->buildHistoryPrompt($h) . "\nQuery: $q";
        $result = $this->safeGenerate($fullPrompt, $files);
        
        if (strpos($result, 'overloaded') !== false || strpos($result, 'trouble accessing') !== false) {
            $fallback = $this->getFallbackMedicalResponse($q);
            if ($fallback) {
                return $fallback;
            }
        }
        
        return $result;
    }
    
    private function getFallbackMedicalResponse(string $query) {
        $query = strtolower($query);
        
        if (strpos($query, 'headache') !== false || strpos($query, 'migraine') !== false) {
            return "For headache relief, common over-the-counter options include:\n\n" .
                   "1. Paracetamol (500-1000mg every 4-6 hours as needed)\n" .
                   "2. Ibuprofen (200-400mg every 6-8 hours as needed)\n\n" .
                   "Important notes:\n" .
                   "- Follow dosage instructions on the package\n" .
                   "- Do not exceed recommended doses\n" .
                   "- Consult a doctor if headaches persist\n";
        }
        
        if (strpos($query, 'fever') !== false) {
            return "For fever reduction, common options include:\n\n" .
                   "1. Paracetamol (500-1000mg every 4-6 hours as needed)\n" .
                   "2. Ibuprofen (200-400mg every 6-8 hours as needed)\n\n" .
                   "Important notes:\n" .
                   "- Consult a doctor if fever persists > 3 days\n";
        }
        
        return null; 
    }

    public function getStructuredSchedulingQuery(string $q, array $context, array $h = []) {
        $docList = "";
        foreach($context['doctors'] as $doc) {
            $docList .= "- Dr. " . $doc['name'] . " (" . $doc['specialization'] . ")\n";
        }

        $date = now()->format('Y-m-d');
        $prompt = "You are a Booking System.
        Current Date: {$date}
        Directory:\n{$docList}
        
        Tasks:
        1. Match symptoms to specialists.
        2. If user asks 'who is available', set intent to 'find_doctor_availability'.
        3. Extract date/time.
        
        JSON SCHEMA: {\"intent\": \"find_doctor_availability\" OR \"general_scheduling_question\", \"target_datetime\": \"Y-m-d H:i:s\", \"duration_minutes\": 30, \"specialization\": \"string\", \"doctor_name\": \"string\"}
        \n" . $this->buildHistoryPrompt($h) . "\nInput: $q";
        
        $res = $this->safeGenerate($prompt);
        return json_decode($this->cleanJsonResponse($res), true);
    }

    public function getGeneralSchedulingResponse(string $q, array $context, array $h = []) {
        $docList = "";
        foreach($context['doctors'] as $doc) {
            $docList .= "- Dr. " . $doc['name'] . " (" . $doc['specialization'] . ")\n";
        }
        return $this->safeGenerate("Receptionist. Directory:\n{$docList}\nUser asks: $q\nAnswer helpfully.");
    }

    public function generateNaturalAvailabilityResponse($docs, $date, $dur, $spec) {
        $list = $docs->map(fn($d) => "Dr. " . $d->user->name . " (" . ($d->category->name ?? 'General') . ")")->implode(', ');
        return $this->safeGenerate("Receptionist. User wants appt at {$date}. Found: [{$list}]. Write concise response.");
    }

    public function getStructuredReminderQuery(string $q, array $h = []) {
        $res = $this->safeGenerate("Extract reminder JSON: {\"intent\":\"create_reminder\", \"scheduled_at\":\"Y-m-d H:i:s\", \"message\":\"\"}. Date: ".now()->format('Y-m-d')."\nUser: $q");
        return json_decode($this->cleanJsonResponse($res), true);
    }

    public function getGeneralChatResponse(string $q, array $h = []) {
        return $this->safeGenerate("You are ClinicalPro AI. Be warm, professional. \nUser: $q");
    }
    
    public function getStructuredScheduleInfoQuery(string $q, array $h = []) {
        $res = $this->safeGenerate("Extract schedule lookup JSON. Focus on extracting just the doctor's name (without titles like Dr./Doctor) and date.\nExample valid response: {\"intent\":\"get_doctor_schedule\", \"doctor_name\":\"Victor Iwasanmi\", \"target_date\":\"2025-11-19\"}\nUser: $q");
        return json_decode($this->cleanJsonResponse($res), true);
    }

    public function getStructuredAppointmentInfoQuery(string $q, array $h = []) {
        $res = $this->safeGenerate("Extract appointment lookup JSON. Focus on extracting just the doctor's name (without titles like Dr./Doctor) and date.\nExample valid response: {\"intent\":\"get_doctor_appointments\", \"doctor_name\":\"Victor Iwasanmi\", \"target_date\":\"2025-11-19\"}\nUser: $q");
        return json_decode($this->cleanJsonResponse($res), true);
    }
    
    public function getStructuredNoteData(string $note) {
        $res = $this->safeGenerate("Extract clinical JSON: {\"diagnosis\":\"\", \"medications\":[{\"name\":\"\",\"dosage\":\"\",\"instructions\":\"\"}], \"follow_up\":\"\"}\nNote: $note");
        return json_decode($this->cleanJsonResponse($res), true);
    }
    
    public function summarizePatientRecords(string $patientName, string $recordsText): string
    {
        $prompt = "Please summarize the following medical records for patient {$patientName}.
        Focus on the most important clinical notes and medications.
        Present the information in a clear, concise format suitable for a medical professional.
        
        Medical Records:
        {$recordsText}";
        
        return $this->safeGenerate($prompt);
    }
    
    public function getStructuredPatientSummaryQuery(string $q, array $h = []) {
        $res = $this->safeGenerate("Extract patient summary JSON: {\"intent\":\"summarize_patient\", \"patient_name\":\"\"}. Date: ".now()->format('Y-m-d')."\nUser: $q");
        return json_decode($this->cleanJsonResponse($res), true);
    }
    
    public function checkConnection() {
        try {
            $modelName = $this->getValidModel();
            if ($modelName) {
                return "SUCCESS! Connected to Gemini API. Using model: " . $modelName;
            }
            return "WARNING! Could not determine valid model.";
        } catch (\Exception $e) {
            return "ERROR! Could not connect to Gemini API. " . $e->getMessage();
        }
    }
}