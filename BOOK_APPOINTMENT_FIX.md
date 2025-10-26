# Book Appointment Duration Fix

## Issue
The book appointment page had issues with time duration selection:
1. The duration was always fixed at 30 minutes regardless of user selection
2. The price would change based on selection but the actual duration saved was always 30 minutes
3. Visual feedback for selected duration was not working properly

## Root Cause
1. Inconsistent use of CSS classes (`selected` vs `active`)
2. The hidden input field `service_duration` was not being properly updated
3. The price calculation logic was not correctly implemented
4. Event handlers were not properly updating the form data

## Solution
1. Fixed CSS class consistency - now using `active` class throughout
2. Implemented proper event handling for duration selection
3. Added a dedicated `updatePrice()` function that calculates price based on:
   - Base service price
   - Base service duration
   - Selected duration
4. Ensured the hidden `service_duration` input is properly updated
5. Improved form reset functionality

## Key Improvements
- Duration selection now properly updates the form data
- Price calculation is based on duration ratio (selected_duration/base_duration)
- Visual feedback shows which duration is currently selected
- Form reset properly clears all selections
- Better error handling for edge cases

## Verification
The book appointment page should now:
1. Allow users to select different time durations (30, 40, 60 minutes)
2. Properly update the hidden `service_duration` field with the selected value
3. Calculate and display the correct price based on the selected duration
4. Save the correct duration to the database when the appointment is booked
5. Provide visual feedback for the selected duration option