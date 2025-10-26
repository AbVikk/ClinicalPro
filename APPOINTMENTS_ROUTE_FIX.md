# Appointments Route Fix

## Issue
The doctor appointments page was throwing a "Route [doctor.patient.show] not defined" error when trying to display patient links in the appointments tab content.

## Root Cause
The appointments tab content view was using `route('doctor.patient.show', $appointment->patient->id)` but this route doesn't exist in the doctor routes.

## Solution
1. Replaced all instances of `route('doctor.patient.show', $appointment->patient->id)` with `route('doctor.patients.appointment-history', $appointment->patient->id)` in `resources/views/doctor/appointments-tab-content.blade.php`

2. Verified that the correct route exists:
   - GET|HEAD doctor/patients/{patient}/appointment-history doctor.patients.appointment-history

3. Cleared view cache to ensure changes take effect

## Verification
The appointments page should now load without route errors, and patient links should work correctly by showing the patient's appointment history.