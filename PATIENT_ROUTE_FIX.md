# Patient Route Fix

## Issue
The doctor dashboard was throwing a "Route [doctor.patient.index] not defined" error when trying to access the "My Patients" quick action button.

## Root Cause
The route `doctor.patient.index` was not defined in the doctor routes file, even though the method existed in the DashboardController.

## Solution
1. Added the missing route in `routes/doctor.php`:
   ```php
   Route::get('/patients', [Doctor\DashboardController::class, 'indexPatient'])->name('patient.index');
   ```
   
   Due to the route group prefix in RouteServiceProvider, this becomes `doctor.patient.index`.

2. Verified that the route is properly registered:
   - GET|HEAD doctor/patients doctor.patient.index

3. Cleared all caches:
   - Route cache
   - View cache
   - Config cache

## Verification
The route should now be accessible and the doctor dashboard should load without errors.