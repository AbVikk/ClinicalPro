<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AiAssistantService;
use App\Models\AiChatHistory;
use App\Models\Doctor;
use App\Models\Category;
use App\Models\Reminder;
use App\Models\User;
use App\Models\DoctorSchedule;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    protected $aiService;

    public function __construct(AiAssistantService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function getSmartScheduling(Request $request)
    {
        $user = $request->user() ?? Auth::user();
        if (!$user) return response()->json(['response' => 'Please log in.'], 401);

        $query = trim($request->input('query'));
        $files = $request->file('image_uploads') ?? [];

        if (empty($query) && empty($files)) return response()->json(['response' => '...'], 400);

        // Memory
        $chatNotebook = AiChatHistory::firstOrCreate(['user_id' => $user->id], ['history' => []]);
        $history = $chatNotebook->history ?? [];

        // 1. Identify Intent
        try {
            $intent = $this->aiService->classifyIntent($query, $history);
        } catch (\Exception $e) {
            $intent = 'general_chat';
        }

        // 2. Context
        $specializations = Category::pluck('name')->toArray();
        $doctors = Doctor::with('user', 'category')
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->get()
            ->map(function ($doc) {
                return [
                    'name' => $doc->user->name,
                    'specialization' => $doc->category->name ?? ($doc->specialization ?? 'General')
                ];
            })->toArray();

        $contextData = ['specializations' => $specializations, 'doctors' => $doctors];
        $jsonResponse = null;

        // 3. Execute
        try {
            switch ($intent) {
                case 'scheduling':
                    $structured = $this->aiService->getStructuredSchedulingQuery($query, $contextData, $history);
                    if ($structured && ($structured['intent'] === 'find_doctor_availability')) {
                        $targetDate = $structured['target_datetime'] ?? now()->format('Y-m-d H:i:s');
                        $duration = (int)($structured['duration_minutes'] ?? 30);
                        $spec = $structured['specialization'] ?? null;

                        $available = $this->findAvailableDoctors($targetDate, $duration, $spec);
                        $text = $this->aiService->generateNaturalAvailabilityResponse($available, Carbon::parse($targetDate), $duration, $spec);
                        $jsonResponse = response()->json(['response' => $text]);
                    } else {
                        $text = $this->aiService->getGeneralSchedulingResponse($query, $contextData, $history);
                        $jsonResponse = response()->json(['response' => $text]);
                    }
                    break;

                case 'medical_query':
                    // Allow all clinical staff
                    if (!in_array($user->role, ['doctor', 'nurse', 'admin', 'hod', 'pharmacist', 'primary_pharmacist', 'senior_pharmacist'])) {
                        $jsonResponse = response()->json(['response' => "I am optimized for medical professionals. As a patient, please consult a doctor directly for specific medical advice."]);
                    } else {
                        $text = $this->aiService->getMedicalQueryResponse($query, $history, $files);
                        $jsonResponse = response()->json(['response' => $text]);
                    }
                    break;

                case 'get_doctor_schedule':
                     // Only Staff can check schedules
                     if ($user->role === 'patient') {
                        $jsonResponse = response()->json(['response' => "Please contact the clinic directly to inquire about staff schedules."]);
                     } else {
                        $info = $this->aiService->getStructuredScheduleInfoQuery($query, $history);
                        $jsonResponse = $info ? $this->handleScheduleLookup($info) : response()->json(['response' => "Which doctor's schedule?"]);
                     }
                    break;

                case 'get_doctor_appointments':
                     if (!in_array($user->role, ['admin', 'doctor', 'nurse', 'hod'])) {
                        $jsonResponse = response()->json(['response' => "Unauthorized access to appointment lists."]);
                     } else {
                        $info = $this->aiService->getStructuredAppointmentInfoQuery($query, $history);
                        $jsonResponse = $info ? $this->handleAppointmentLookup($info) : response()->json(['response' => "Which doctor?"]);
                     }
                    break;
                    
                case 'summarize_patient':
                    if (!in_array($user->role, ['admin', 'doctor', 'nurse', 'hod'])) {
                        $jsonResponse = response()->json(['response' => "Unauthorized."]);
                    } else {
                        $info = $this->aiService->getStructuredPatientSummaryQuery($query, $history);
                        $jsonResponse = $this->handlePatientSummary($info);
                    }
                    break;

                case 'reminder':
                    $data = $this->aiService->getStructuredReminderQuery($query, $history);
                    $jsonResponse = $data ? $this->handleReminderCreation($data, $user) : response()->json(['response' => "Please specify a time for the reminder."]);
                    break;

                case 'general_chat':
                default:
                    $text = $this->aiService->getGeneralChatResponse($query, $history);
                    $jsonResponse = response()->json(['response' => $text]);
                    break;
            }
        } catch (\Exception $e) {
            Log::error("AI Error: " . $e->getMessage());
            $jsonResponse = response()->json(['response' => "I encountered an error. Please try again."]);
        }

        // 4. Save History
        $respData = $jsonResponse->getData();
        $aiText = $respData->response ?? '...';
        
        $userEntry = $query . (!empty($files) ? " [Images]" : "");
        $history[] = ['role' => 'user', 'content' => $userEntry];
        $history[] = ['role' => 'model', 'content' => $aiText];

        if (count($history) > 20) $history = array_slice($history, -20);

        $chatNotebook->history = $history;
        $chatNotebook->save();

        return $jsonResponse;
    }

    // --- HELPERS ---

    private function findAvailableDoctors($dateTimeStr, $duration, $specialization)
    {
        try { $start = Carbon::parse($dateTimeStr); } catch (\Exception $e) { return collect([]); }

        $query = Doctor::with(['user', 'category'])
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->where('status', 'verified');

        if ($specialization) {
            $query->where(function($q) use ($specialization) {
                $q->whereHas('category', fn($c) => $c->where('name', 'LIKE', "%$specialization%"))
                  ->orWhere('specialization', 'LIKE', "%$specialization%");
            });
        }

        // Use scopes from Doctor Model
        $query->whereIsAvailable($start);
        $query->whereHasNoConflict($start, $duration);

        return $query->get();
    }

    private function handleScheduleLookup($data)
    {
        $name = $data['doctor_name'];
        $date = Carbon::parse($data['target_date'] ?? now());
        
        // Clean the name by removing common prefixes
        $cleanName = preg_replace('/^(dr|doctor|dr\.)\s*/i', '', $name);
        
        // Search for doctors with more flexible matching
        $doctor = User::where('role', 'doctor')
            ->where(function($query) use ($cleanName, $name) {
                $query->where('name', 'LIKE', "%$cleanName%")
                      ->orWhere('name', 'LIKE', "%$name%")
                      ->orWhereRaw('LOWER(name) LIKE ?', [strtolower("%$cleanName%")])
                      ->orWhereRaw('LOWER(name) LIKE ?', [strtolower("%$name%")]);
            })
            ->first();
            
        if (!$doctor) return response()->json(['response' => "I couldn't find Dr. $name. Available doctors: " . User::where('role', 'doctor')->pluck('name')->join(', ') . "."]);

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', strtolower($date->format('l')))
            ->get();

        if ($schedules->isEmpty()) return response()->json(['response' => "Dr. {$doctor->name} is not scheduled for {$date->format('l, M d')}."]);

        $hours = $schedules->map(fn($s) => Carbon::parse($s->start_time)->format('g:i A') . "-" . Carbon::parse($s->end_time)->format('g:i A'))->join(', ');
        return response()->json(['response' => "Dr. {$doctor->name}'s schedule for {$date->format('l, M d')}: $hours."]);
    }

    private function handleAppointmentLookup($data)
    {
        $name = $data['doctor_name'];
        $date = Carbon::parse($data['target_date'] ?? now());

        // Clean the name by removing common prefixes
        $cleanName = preg_replace('/^(dr|doctor|dr\.)\s*/i', '', $name);
        
        // Search for doctors with more flexible matching
        $doctor = User::where('role', 'doctor')
            ->where(function($query) use ($cleanName, $name) {
                $query->where('name', 'LIKE', "%$cleanName%")
                      ->orWhere('name', 'LIKE', "%$name%")
                      ->orWhereRaw('LOWER(name) LIKE ?', [strtolower("%$cleanName%")])
                      ->orWhereRaw('LOWER(name) LIKE ?', [strtolower("%$name%")]);
            })
            ->first();
            
        if (!$doctor) return response()->json(['response' => "I couldn't find Dr. $name. Available doctors: " . User::where('role', 'doctor')->pluck('name')->join(', ') . "."]);

        $appts = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_time', $date->toDateString())
            ->whereIn('status', ['confirmed', 'in_progress', 'pending', 'approved'])
            ->with('patient')
            ->orderBy('appointment_time')
            ->get();

        if ($appts->isEmpty()) return response()->json(['response' => "Dr. {$doctor->name} has no appointments on {$date->format('l, M d')}."]);

        $list = "Appointments for Dr. {$doctor->name}:\n";
        foreach ($appts as $a) {
            $time = Carbon::parse($a->appointment_time)->format('g:i A');
            $pName = $a->patient->name ?? 'Unknown';
            $list .= "- $time: $pName ({$a->status})\n";
        }
        return response()->json(['response' => $list]);
    }
    
    private function handlePatientSummary($data)
    {
        $name = $data['patient_name'] ?? '';
        if (!$name) return response()->json(['response' => "Which patient?"]);

        $patient = User::where('role', 'patient')->where('name', 'LIKE', "%$name%")->first();
        if (!$patient) return response()->json(['response' => "Patient not found."]);

        // Gather records
        $notes = \App\Models\ClinicalNote::whereHas('appointment', fn($q) => $q->where('patient_id', $patient->id))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $meds = \App\Models\Medication::whereHas('appointment', fn($q) => $q->where('patient_id', $patient->id))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Build text blob for AI
        $text = "Clinical Notes:\n";
        foreach($notes as $n) $text .= "- " . ($n->note_text ?? 'No text') . "\n";
        
        $text .= "\nRecent Meds:\n";
        foreach($meds as $m) $text .= "- {$m->medication_name} ({$m->dosage})\n";

        if (empty($text)) return response()->json(['response' => "No medical records found for this patient."]);

        // Ask AI to summarize
        $summary = $this->aiService->summarizePatientRecords($patient->name, $text);
        return response()->json(['response' => $summary]);
    }

    private function handleReminderCreation($data, $user)
    {
        try {
            $date = Carbon::parse($data['scheduled_at']);
            if ($date->isPast()) return response()->json(['response' => "I can't set a reminder in the past."]);

            Reminder::create([
                'user_id' => $user->id,
                'creator_id' => $user->id,
                'scheduled_at' => $date,
                'message' => $data['message'],
                'status' => 'pending'
            ]);
            return response()->json(['response' => "✅ Reminder set for " . $date->format('M d, g:i A')]);
        } catch (\Exception $e) {
            return response()->json(['response' => "Invalid time format."]);
        }
    }

    public function getChatHistory(Request $request)
    {
        $history = AiChatHistory::where('user_id', $request->user()->id)->value('history');
        return response()->json(['history' => $history ?? []]);
    }

    public function clearChatHistory(Request $request)
    {
        AiChatHistory::where('user_id', $request->user()->id)->delete();
        return response()->json(['message' => 'History cleared']);
    }
    
    public function extractDetailsFromNote(Request $request)
    {
        $note = $request->input('note');
        $data = $this->aiService->getStructuredNoteData($note);
        return response()->json(['success' => (bool)$data, 'data' => $data]);
    }
}