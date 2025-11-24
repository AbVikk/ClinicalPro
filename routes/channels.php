<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use Illuminate\Support\Facades\Log; // We'll keep logging

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


// --- THIS IS THE CORRECT, SIMPLE RULE ---

// We are looking for the SIMPLE, SHORT channel name.
// e.g., "doctor-alerts.3"
// Our bootstrap.js file will "knock on the door" asking for "private-doctor-alerts.3"
// and Laravel is smart enough to match that to "doctor-alerts.{doctorId}"
Broadcast::channel('doctor-alerts.{doctorId}', function (User $user, $doctorId) {

    Log::info("[Broadcast Auth] Attempting to auth channel: doctor-alerts.{$doctorId}");
    Log::info("[Broadcast Auth] User ID is: " . $user->id);

    // Now we do our simple security check
    if ($user->id == $doctorId && $user->role == 'doctor') {
        Log::info("[Broadcast Auth] SUCCESS: User {$user->id} authorized for channel.");
        return true; // ...you are IN!
    }

    Log::warning("[Broadcast Auth] FAILED: User {$user->id} denied for channel.");
    return false; // ...you are NOT allowed.
});