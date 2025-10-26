# Countdown Timer Fix

## Issue
The countdown timer on the appointment details page (http://127.0.0.1:8000/doctor/appointments/3/details) was only showing "--:--:--" instead of the actual time countdown.

## Root Cause
The previous implementation had several issues:
1. It was calculating the end time based on the current time rather than storing it
2. It wasn't using localStorage for persistence across page reloads
3. The timer logic had issues with handling expired sessions

## Solution
Replaced the entire countdown timer implementation with an improved version that:

1. Uses localStorage to persist the session end time across page reloads
2. Properly calculates the absolute end time based on the appointment start time and duration
3. Handles expired sessions by resetting to a default duration
4. Properly clears the timer and localStorage when the session ends or is cancelled
5. Uses DOMContentLoaded to ensure the element is available before trying to update it

## Key Improvements
- Added localStorage persistence for the session end time
- Improved session end time calculation logic
- Better handling of expired sessions
- Proper cleanup of timers and localStorage entries
- More robust error handling

## Verification
The countdown timer should now display the correct time remaining and update every second. It will persist across page reloads and properly handle session expiration.