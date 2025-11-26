<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// 1. User Private Channel
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 2. Doctor Alerts Channel (Secured + Logged)
Broadcast::channel('doctor-alerts.{doctorId}', function (User $user, $doctorId) {
    
    Log::info("[Broadcast Auth] Attempting to auth channel: doctor-alerts.{$doctorId}");
    Log::info("[Broadcast Auth] Authenticated User ID: " . $user->id . " | Role: " . $user->role);

    // Security Check:
    // 1. User must be a Doctor
    // 2. User ID must match the channel ID
    if ($user->role === 'doctor' && (int) $user->id === (int) $doctorId) {
        Log::info("[Broadcast Auth] SUCCESS: Doctor {$user->id} authorized.");
        return true; 
    }

    Log::warning("[Broadcast Auth] FAILED: User {$user->id} denied access to doctor-alerts.{$doctorId}");
    return false; 
});

// 3. Future P2P Chat Channel (Placeholder)
// Broadcast::channel('chat.{roomId}', function ($user, $roomId) {
//     return $user->canJoinRoom($roomId);
// });