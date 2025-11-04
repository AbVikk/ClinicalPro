<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiAssistantService;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\Reminder; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache; 
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB; 
use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\Appointment;

class AiController extends Controller
{
    protected $aiService;

    public function __construct(AiAssistantService $aiService)
    {
        $this->aiService = $aiService; // TYPO FIX
    }

    /**
     * Handles the natural language request (main entry point).
     *
     * === THIS IS THE UPDATED VERSION WITH FILE UPLOAD LOGIC ===
     */
    public function getSmartScheduling(Request $request)
    {
        // --- 1. INITIAL CHECKS ---
        $user = $request->user() ?? Auth::user(); 
        if (!$user) {
            return response()->json(['error' => 'Authentication required.'], 401);
        }
        
        $query = trim($request->input('query'));

        // --- NEW: Check for the uploaded file ---
        $file = null;
        if ($request->hasFile('image_upload')) {
            $file = $request->file('image_upload');
        }
        
        if (empty($query) && $file === null) {
            return response()->json(['error' => 'No query or file provided.'], 400);
        }
        // --- END OF NEW FILE LOGIC ---
        
        // --- 2. GET THE NOTEBOOK (SESSION HISTORY) ---
        $sessionKey = 'ai_chat_history_' . $user->id; 
        $history = $request->session()->get($sessionKey, []);
        
        
        // --- 3. HIRE THE MANAGER (Pass the history to it) ---
        $intent = $this->aiService->classifyIntent($query, $history); // TYPO FIX

        
        // --- 4. ROUTE TO THE CORRECT "DESK" ---
        $jsonResponse = null; 
        
        switch ($intent) {
            
            // --- JOB 1: SCHEDULING DESK ---
            case 'scheduling':
                $specializations = \App\Models\Category::pluck('name')->toArray();
                $contextData = [ 'specializations' => $specializations, 'current_date' => now()->format('Y-m-d') ];
                $structuredQuery = $this->aiService->getStructuredSchedulingQuery($query, $contextData, $history); // TYPO FIX 

                if (!$structuredQuery || $structuredQuery['intent'] !== 'find_doctor_availability') {
                    $jsonResponse = response()->json(['response' => "I understood you want to check a schedule, but I couldn't figure out the exact date and time. Please try again."], 200);
                    break;
                }

                $specialization = $structuredQuery['specialization'] ?? null;
                $duration = (int)($structuredQuery['duration_minutes'] ?? 30);
                
                try {
                    $targetDateTime = Carbon::parse($structuredQuery['target_datetime']); 
                } catch (\Exception $e) {
                    $jsonResponse = response()->json(['response' => 'The AI generated an invalid date/time. Please try again or be more specific.'], 200);
                    break;
                }
                
                $doctorsQuery = Doctor::query();
                
                if ($specialization) {
                    $category = Category::where('name', $specialization)->first();
                    if ($category) {
                        $doctorsQuery->where('category_id', $category->id);
                    }
                }
                
                $availableDoctors = $doctorsQuery
                    ->whereIsAvailable($targetDateTime) 
                    ->whereHasNoConflict($targetDateTime, $duration) 
                    ->with('user', 'category')
                    ->get();
                
                if ($availableDoctors->isEmpty()) {
                    $responseText = "I'm sorry, I couldn't find any doctors matching those criteria ({$specialization} for {$duration} minutes) at {$targetDateTime->format('g:i A')} on {$targetDateTime->format('l, M jS')}.";
                    if ($targetDateTime->isToday() && $targetDateTime->lessThan(now())) {
                        $responseText .= " (Note: The requested time is in the past. Try a future time.)";
                    }
                } else {
                    $list = $availableDoctors->map(function($doctor) {
                        $specialtyName = $doctor->category->name ?? 'General Practice';
                        return "Dr. " . ($doctor->user->name ?? 'Unknown') . " (Specialization: {$specialtyName})";
                    })->implode("\n");

                    $responseText = "✅ I found {$availableDoctors->count()} available doctor(s) for a {$duration}-minute slot on {$targetDateTime->format('l, M jS')} at {$targetDateTime->format('g:i A')}:\n{$list}";
                }
                
                $jsonResponse = response()->json(['response' => $responseText, 'doctors' => $availableDoctors], 200);
                break;

                
            // --- JOB 2: REMINDER DESK ---
            case 'reminder':
                $reminderQuery = $this->aiService->getStructuredReminderQuery($query, $history); // TYPO FIX
                
                if (!$reminderQuery || $reminderQuery['intent'] !== 'create_reminder') {
                     $jsonResponse = response()->json(['response' => "I understood you want to set a reminder, but I couldn't figure out the time or message. Please try again."], 200);
                     break;
                }
                
                $jsonResponse = $this->handleReminderCreation($reminderQuery, $user); // TYPO FIX
                break;

                
            // --- JOB 3: LIBRARIAN DESK (THIS IS THE MODIFIED CASE) ---
            case 'medical_query':
                if (!in_array($user->role, ['doctor', 'admin', 'nurse'])) {
                     $jsonResponse = response()->json(['response' => "I'm sorry, the medical search feature is only available for clinic staff."], 403);
                     break;
                }
                
                // --- MODIFICATION: Pass the $file to the function ---
                $medicalResponse = $this->aiService->getMedicalQueryResponse($query, $history, $file); // TYPO FIX
                // --- END OF MODIFICATION ---
                
                $jsonResponse = response()->json(['response' => $medicalResponse, 'doctors' => []], 200);
                break;

                
            // --- JOB 4: SCHEDULE REPORTER ---
            case 'get_doctor_schedule':
                $infoQuery = $this->aiService->getStructuredScheduleInfoQuery($query, $history); // TYPO FIX
                
                if (!$infoQuery || !isset($infoQuery['doctor_name']) || !isset($infoQuery['target_date'])) {
                    $jsonResponse = response()->json(['response' => "I understood you wanted a doctor's schedule, but I couldn't tell who or for what day. Please be more specific."], 200);
                    break;
                }

                $doctorName = $infoQuery['doctor_name'];
                $targetDate = Carbon::parse($infoQuery['target_date']);
                $dayOfWeek = strtolower($targetDate->format('l'));

                $doctorUser = User::where('name', 'LIKE', '%' . $doctorName . '%')
                                  ->where('role', 'doctor')
                                  ->first();

                if (!$doctorUser) {
                    $jsonResponse = response()->json(['response' => "I'm sorry, I couldn't find a doctor in our system by the name of '{$doctorName}'."], 200);
                    break;
                }
                
                $schedules = DoctorSchedule::where('doctor_id', $doctorUser->id)
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_date', '<=', $targetDate->format('Y-m-d'))
                    ->where('end_date', '>=', $targetDate->format('Y-m-d'))
                    ->with('clinic') 
                    ->get();

                if ($schedules->isEmpty()) {
                    $jsonResponse = response()->json(['response' => "It looks like Dr. {$doctorUser->name} does not have any scheduled hours on {$targetDate->format('l, M jS')}. They may be on leave or not scheduled for that day."], 200);
                    break;
                }

                $responseText = "Here is the schedule for Dr. {$doctorUser->name} on {$targetDate->format('l, M jS')}:\n";
                foreach ($schedules as $schedule) {
                    $startTime = Carbon::parse($schedule->start_time)->format('g:i A');
                    $endTime = Carbon::parse($schedule->end_time)->format('g:i A');
                    $location = 'Virtual Session'; 
                    if ($schedule->location !== 'virtual' && $schedule->clinic) {
                        $location = $schedule->clinic->name;
                    } else if ($schedule->location !== 'virtual') {
                        $location = 'Physical Clinic'; 
                    }
                    
                    $responseText .= "• {$startTime} to {$endTime} ({$location})\n";
                }

                $jsonResponse = response()->json(['response' => $responseText, 'doctors' => []], 200);
                break;

                
            // --- JOB 5: APPOINTMENT REPORTER ---
            case 'get_doctor_appointments':
                $infoQuery = $this->aiService->getStructuredAppointmentInfoQuery($query, $history); // TYPO FIX
                
                if (!$infoQuery || !isset($infoQuery['doctor_name']) || !isset($infoQuery['target_date'])) {
                    $jsonResponse = response()->json(['response' => "I understood you wanted a doctor's appointment list, but I couldn't tell who or for what day. Please be more specific."], 200);
                    break;
                }

                $doctorName = $infoQuery['doctor_name'];
                $targetDate = Carbon::parse($infoQuery['target_date']);

                $doctorUser = User::where('name', 'LIKE', '%' . $doctorName . '%')
                                  ->where('role', 'doctor')
                                  ->first();

                if (!$doctorUser) {
                    $jsonResponse = response()->json(['response' => "I'm sorry, I couldn't find a doctor in our system by the name of '{$doctorName}'."], 200);
                    break;
                }
                
                $appointments = Appointment::where('doctor_id', $doctorUser->id)
                    ->whereDate('appointment_time', $targetDate->format('Y-m-d'))
                    ->whereIn('status', ['confirmed', 'in_progress', 'pending']) 
                    ->with('patient') 
                    ->orderBy('appointment_time', 'asc')
                    ->get();

                if ($appointments->isEmpty()) {
                    $jsonResponse = response()->json(['response' => "Dr. {$doctorUser->name} has no booked appointments for {$targetDate->format('l, M jS')}."], 200);
                    break;
                }

                $responseText = "Here are the booked appointments for Dr. {$doctorUser->name} on {$targetDate->format('l, M jS')}:\n";
                foreach ($appointments as $appointment) {
                    $startTime = Carbon::parse($appointment->appointment_time)->format('g:i A');
                    $patientName = $appointment->patient->name ?? 'Unknown Patient';
                    
                    $responseText .= "• {$startTime} with {$patientName} (Status: {$appointment->status})\n";
                }

                $jsonResponse = response()->json(['response' => $responseText, 'doctors' => []], 200);
                break;


            // --- JOB 6: GREETER DESK (The fallback) ---
            case 'general_chat':
            default:
                if ($file !== null) {
                    $generalResponse = "I can see you've attached an image, but I can only analyze images when you ask a specific medical question about it.";
                } else {
                    $generalResponse = $this->aiService->getGeneralChatResponse($query, $history); // TYPO FIX
                }
                
                $jsonResponse = response()->json(['response' => $generalResponse, 'doctors' => []], 200);
                break;
        }
        
        
        // --- 5. WRITE TO THE NOTEBOOK (SESSION) ---
        $responseData = $jsonResponse->getData(true);
        $aiResponseText = $responseData['response'] ?? 'Sorry, an error occurred.';

        $userHistoryText = $query;
        if ($file) {
            $userHistoryText .= " [User attached an image: " . $file->getClientOriginalName() . "]";
        }
        $history[] = ['role' => 'user', 'content' => $userHistoryText];
        
        $history[] = ['role' => 'model', 'content' => $aiResponseText];
        
        if (count($history) > 6) {
            $history = array_slice($history, -6);
        }
        
        $request->session()->put($sessionKey, $history);

        
        // --- 6. GIVE THE USER THE ANSWER ---
        return $jsonResponse;
    }

    /**
     * This is the new API endpoint for the "Smart Notes" tool.
     */
    public function extractDetailsFromNote(Request $request)
    {
        $request->validate([
            'note' => 'required|string|min:10',
        ]);
        
        $noteText = $request->input('note');
        
        $structuredData = $this->aiService->getStructuredNoteData($noteText); // TYPO FIX
        
        if ($structuredData) {
            return response()->json([
                'success' => true,
                'data' => $structuredData
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'The AI was unable to read this note. Please check the note for clarity.'
        ], 500);
    }

    /**
     * Handles the logic for saving a new AI-parsed reminder.
     */
    protected function handleReminderCreation(array $structuredData, $user)
    {
        try {
            $scheduledAt = Carbon::parse($structuredData['scheduled_at']);
            
            if ($scheduledAt->isPast()) {
                return response()->json(['response' => "❌ I can't set a reminder for a time that has already passed. Please specify a future date and time."], 200);
            }

            Reminder::create([
                'creator_id' => $user->id,
                'user_id' => $user->id, 
                'scheduled_at' => $scheduledAt,
                'message' => $structuredData['message'],
                'status' => 'pending',
            ]);

            $responseText = "✅ Reminder successfully set! I will notify you on {$scheduledAt->format('l, M jS')} at {$scheduledAt->format('g:i A')} with the message: \"{$structuredData['message']}\"";

            Cache::forget("admin_stats_pending_reminders");
            
            return response()->json(['response' => $responseText], 200);

        } catch (\Exception $e) {
            Log::error("Reminder saving failed: " . $e->getMessage());
            return response()->json(['response' => "❌ Sorry, I had trouble saving that reminder. Please check the date format."], 200);
        }
    }
}