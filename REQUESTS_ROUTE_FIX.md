# Requests Route Fix

## Issue
The doctor requests page was throwing a "Route [doctor.patient.show] not defined" error when trying to display patient links.

## Root Cause
The requests view was using `route('doctor.patient.show', $request->patient->id)` but this route doesn't exist in the doctor routes.

## Solution
1. Replaced all instances of `route('doctor.patient.show', $request->patient->id)` with `route('doctor.patients.appointment-history', $request->patient->id)` in `resources/views/doctor/requests.blade.php`

2. Verified that the correct route exists:
   - GET|HEAD doctor/patients/{patient}/appointment-history doctor.patients.appointment-history

3. Cleared all caches:
   - Route cache
   - View cache

## Verification
The requests page should now load without route errors, and patient links should work correctly by showing the patient's appointment history.