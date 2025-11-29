<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Carbon\Carbon;

class QueueMonitorController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. Patients currently inside with a doctor (In Progress)
        $serving = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_time', $today)
            ->where('status', 'in_progress')
            ->orderBy('updated_at', 'desc') // Most recently started first
            ->get();

        // 2. Patients waiting in the lobby (Checked In or Vitals Taken)
        $waiting = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_time', $today)
            ->whereIn('status', ['checked_in', 'vitals_taken'])
            ->orderBy('appointment_time', 'asc') // Oldest appointment first
            ->get();

        return view('monitor.index', compact('serving', 'waiting'));
    }

    /**
     * AJAX endpoint for auto-refreshing content without reloading the page
     */
    public function content()
    {
        $today = Carbon::today();

        $serving = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_time', $today)
            ->where('status', 'in_progress')
            ->orderBy('updated_at', 'desc')
            ->get();

        $waiting = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_time', $today)
            ->whereIn('status', ['checked_in', 'vitals_taken'])
            ->orderBy('appointment_time', 'asc')
            ->get();

        // Return the partial HTML view for the lists
        return view('monitor.content', compact('serving', 'waiting'));
    }
}