
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Api\AiController;

// Admin routes (middleware applied in RouteServiceProvider)
Route::get('/test', function () {
    return response()->json(['message' => 'Admin test route working']);
});

/*
|--------------------------------------------------------------------------
| AI Chat API Routes
|--------------------------------------------------------------------------
*/
// These routes are automatically prefixed with 'admin.' by the RouteServiceProvider
Route::prefix('ai')->name('api.ai.')->group(function () {
    Route::post('/scheduling', [AiController::class, 'getSmartScheduling'])
        ->name('scheduling');

    Route::post('/extract-note-details', [AiController::class, 'extractDetailsFromNote'])
        ->name('extract-notes');

    Route::get('/chat-history', [AiController::class, 'getChatHistory'])
        ->name('chat-history');

    Route::delete('/chat-history/clear', [AiController::class, 'clearChatHistory'])
        ->name('chat-history.clear');
});

Route::get('/index', [Admin\DashboardController::class, 'index'])->name('index');
Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

// Roles and Permissions route - moved to the top to avoid conflicts
Route::get('/clinic-staff/roles-permissions', [Admin\ClinicStaffController::class, 'rolesPermissions'])->name('clinic-staff.roles-permissions');

// Attendance routes
Route::get('/clinic-staff/attendance', [Admin\ClinicStaffController::class, 'attendance'])->name('clinic-staff.attendance');
Route::post('/clinic-staff/attendance/record', [Admin\ClinicStaffController::class, 'recordAttendance'])->name('clinic-staff.attendance.record');
Route::get('/clinic-staff/attendance/calendar-data', [Admin\ClinicStaffController::class, 'getCalendarDataAjax'])->name('clinic-staff.attendance.calendar-data');

// Clinic Staff routes
Route::get('/clinic-staff', [Admin\ClinicStaffController::class, 'index'])->name('clinic-staff.index');
Route::get('/clinic-staff/add', [Admin\ClinicStaffController::class, 'create'])->name('clinic-staff.add');
Route::post('/clinic-staff', [Admin\ClinicStaffController::class, 'store'])->name('clinic-staff.store');
Route::get('/clinic-staff/{id}', [Admin\ClinicStaffController::class, 'show'])->name('clinic-staff.show');
Route::get('/clinic-staff/{id}/edit', [Admin\ClinicStaffController::class, 'edit'])->name('clinic-staff.edit');
Route::put('/clinic-staff/{id}', [Admin\ClinicStaffController::class, 'update'])->name('clinic-staff.update');
Route::delete('/clinic-staff/{id}', [Admin\ClinicStaffController::class, 'destroy'])->name('clinic-staff.destroy');

// Invitation routes
Route::get('/invitations', [Admin\InvitationController::class, 'index'])->name('invitations.index');
Route::get('/invitations/create', [Admin\InvitationController::class, 'create'])->name('invitations.create');
Route::post('/invitations', [Admin\InvitationController::class, 'store'])->name('invitations.store');
Route::delete('/invitations/{invitation}', [Admin\InvitationController::class, 'destroy'])->name('invitations.destroy');

// Doctor management routes
Route::get('/doctor', [Admin\DoctorController::class, 'index'])->name('doctor.index');

Route::get('/doctor/add', [Admin\DoctorController::class, 'create'])->name('doctor.add');

Route::post('/doctor', [Admin\DoctorController::class, 'store'])->name('doctor.store');

Route::get('/doctor/dashboard', [Admin\DoctorController::class, 'dashboard'])->name('doctor.dashboard');

Route::get('/doctor/schedule', [Admin\DoctorController::class, 'schedule'])->name('doctor.schedule');
Route::post('/doctor/schedule', [Admin\DoctorController::class, 'storeSchedule'])->name('doctor.schedule.store');
Route::post('/doctor/schedule/update', [Admin\DoctorController::class, 'updateSchedule'])->name('doctor.schedule.update');

// HOD routes (moved before parameterized routes to avoid conflicts)
Route::get('/doctor/hods', [Admin\DoctorController::class, 'listHODs'])->name('doctor.hods');
Route::put('/doctor/assign-hod/{user}', [Admin\DoctorController::class, 'assignHOD'])->name('doctor.assign-hod');
Route::put('/doctor/assign-doctor-role/{user}', [Admin\DoctorController::class, 'assignDoctorRole'])->name('doctor.assign-doctor-role');

// Parameterized routes (must come after HOD routes)
Route::get('/doctor/{doctor}', [Admin\DoctorController::class, 'show'])->name('doctor.profile');

Route::get('/doctor/{doctor}/edit', [Admin\DoctorController::class, 'edit'])->name('doctor.edit');

Route::put('/doctor/{doctor}', [Admin\DoctorController::class, 'update'])->name('doctor.update');

Route::delete('/doctor/{doctor}', [Admin\DoctorController::class, 'destroy'])->name('doctor.destroy');

// Doctor specialization routes
Route::get('/doctor/specialization/specializations', [Admin\DoctorController::class, 'specializations'])->name('doctor.specialization.index');

Route::get('/doctor/specialization/add_categories', function () {
    return view('admin.doctor.specialization.add_categories');
})->name('doctor.specialization.add_categories');

Route::get('/doctor/specialization/add_department', function () {
    return view('admin.doctor.specialization.add_department');
})->name('doctor.specialization.add_department');

// Department routes
Route::get('/doctor/specialization/departments', [Admin\DepartmentController::class, 'index'])->name('doctor.specialization.departments');
Route::get('/doctor/specialization/departments/add', [Admin\DepartmentController::class, 'create'])->name('doctor.specialization.add_department_form');
Route::post('/doctor/specialization/departments', [Admin\DepartmentController::class, 'store'])->name('departments.store');
Route::get('/doctor/specialization/departments/{department}', [Admin\DepartmentController::class, 'show'])->name('doctor.specialization.department_show');
Route::get('/doctor/specialization/departments/{department}/edit', [Admin\DepartmentController::class, 'edit'])->name('doctor.specialization.edit_department');
Route::put('/doctor/specialization/departments/{department}', [Admin\DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/doctor/specialization/departments/{department}', [Admin\DepartmentController::class, 'destroy'])->name('departments.destroy');

// Category routes
Route::get('/doctor/specialization/categories', [Admin\CategoryController::class, 'index'])->name('doctor.specialization.categories');
Route::get('/doctor/specialization/categories/add', [Admin\CategoryController::class, 'create'])->name('doctor.specialization.add_category_form');
Route::post('/doctor/specialization/categories', [Admin\CategoryController::class, 'store'])->name('categories.store');
Route::get('/doctor/specialization/categories/{category}', [Admin\CategoryController::class, 'show'])->name('doctor.specialization.category_show');
Route::get('/doctor/specialization/categories/{category}/edit', [Admin\CategoryController::class, 'edit'])->name('doctor.specialization.edit_category');
Route::put('/doctor/specialization/categories/{category}', [Admin\CategoryController::class, 'update'])->name('categories.update');
Route::delete('/doctor/specialization/categories/{category}', [Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

// Book Appointment routes
Route::get('/book-appointment', [Admin\BookAppointmentController::class, 'index'])->name('book-appointment');
Route::post('/book-appointment/patient-info', [Admin\BookAppointmentController::class, 'getPatientInfo'])->name('book-appointment.patient-info');
Route::post('/book-appointment/available-doctors', [Admin\BookAppointmentController::class, 'getAvailableDoctors'])->name('book-appointment.available-doctors');
Route::post('/book-appointment/available-locations', [Admin\BookAppointmentController::class, 'getAvailableLocations'])->name('book-appointment.available-locations');
Route::post('/book-appointment/search-patients', [Admin\BookAppointmentController::class, 'searchPatients'])->name('book-appointment.search-patients');
Route::post('/book-appointment', [Admin\BookAppointmentController::class, 'store'])->name('book-appointment.store');
Route::post('/book-appointment/walk-in-patient', [Admin\BookAppointmentController::class, 'storeWalkInPatient'])->name('book-appointment.walk-in-patient');
Route::get('/book-appointment/payment', [Admin\BookAppointmentController::class, 'showAppointmentPayment'])->name('book-appointment.payment');
Route::post('/book-appointment/service-time-pricing', [Admin\BookAppointmentController::class, 'getServiceTimePricing'])->name('book-appointment.service-time-pricing');

// Doctor Availability routes
Route::get('/doctors/availability', [Admin\BookAppointmentController::class, 'showAvailabilityForm'])->name('doctors.availability');
Route::post('/doctors/availability', [Admin\BookAppointmentController::class, 'updateAvailability'])->name('doctors.availability.update');

// Appointments routes
Route::get('/appointments', [Admin\AppointmentController::class, 'index'])->name('appointments.index');
Route::get('/appointment/{id}', [Admin\AppointmentController::class, 'show'])->name('appointment.show');
Route::put('/appointments/{id}/assign-doctor', [Admin\AppointmentController::class, 'assignDoctor'])->name('appointments.assign-doctor');
Route::put('/appointments/{id}', [Admin\AppointmentController::class, 'update'])->name('appointments.update');
Route::delete('/appointments/{id}', [Admin\AppointmentController::class, 'destroy'])->name('appointments.destroy');

// Patients routes
Route::get('/patients', [Admin\PatientController::class, 'index'])->name('patients.index');
Route::get('/patient/{id}', [Admin\PatientController::class, 'show'])->name('patient.show');
Route::delete('/patient/{id}', [Admin\PatientController::class, 'destroy'])->name('patient.destroy');

// Pharmacists routes
Route::get('/pharmacists', [Admin\PharmacistController::class, 'index'])->name('pharmacists.index');
Route::get('/pharmacists/{pharmacist}', [Admin\PharmacistController::class, 'show'])->name('pharmacists.show');
Route::put('/pharmacists/{pharmacist}', [Admin\PharmacistController::class, 'update'])->name('pharmacists.update');
Route::delete('/pharmacists/{pharmacist}', [Admin\PharmacistController::class, 'destroy'])->name('pharmacists.destroy');

// Pharmacy routes
Route::get('/pharmacy', function () {
    return view('admin.pharmacy.index');
})->name('pharmacy.dashboard');

// Drug Categories
Route::resource('/pharmacy/categories', Admin\DrugCategoryController::class)->names([
    'index' => 'pharmacy.categories.index',
    'create' => 'pharmacy.categories.create',
    'store' => 'pharmacy.categories.store',
    'edit' => 'pharmacy.categories.edit',
    'update' => 'pharmacy.categories.update',
    'destroy' => 'pharmacy.categories.destroy',
]);

// Drug MG Values
Route::resource('/pharmacy/mg', Admin\DrugMgController::class)->names([
    'index' => 'pharmacy.mg.index',
    'create' => 'pharmacy.mg.create',
    'store' => 'pharmacy.mg.store',
    'edit' => 'pharmacy.mg.edit',
    'update' => 'pharmacy.mg.update',
    'destroy' => 'pharmacy.mg.destroy',
]);

// Pharmacy routes - Primary Pharmacist
Route::get('/pharmacy/drugs/create-form', [Admin\PrimaryPharmacistController::class, 'showCreateDrugForm'])->name('pharmacy.drugs.create.form');
Route::get('/pharmacy/drugs/{id}', [Admin\PrimaryPharmacistController::class, 'viewDrug'])->name('pharmacy.drugs.view');
Route::get('/pharmacy/drugs/{id}/edit', [Admin\PrimaryPharmacistController::class, 'editDrug'])->name('pharmacy.drugs.edit');
Route::post('/pharmacy/drugs/create', [Admin\PrimaryPharmacistController::class, 'createDrug'])->name('pharmacy.drugs.create');
Route::put('/pharmacy/drugs/{id}', [Admin\PrimaryPharmacistController::class, 'updateDrug'])->name('pharmacy.drugs.update');
Route::delete('/pharmacy/drugs/{id}', [Admin\PrimaryPharmacistController::class, 'deleteDrug'])->name('pharmacy.drugs.delete');
Route::post('/pharmacy/stock/receive', [Admin\PrimaryPharmacistController::class, 'receiveStock'])->name('pharmacy.stock.receive');
Route::post('/pharmacy/stock/update', [Admin\PrimaryPharmacistController::class, 'updateStock'])->name('pharmacy.stock.update');
Route::get('/pharmacy/drugs/{id}/history', [Admin\PrimaryPharmacistController::class, 'getDrugHistory'])->name('pharmacy.drugs.history');
Route::post('/pharmacy/transfers/approve/{id}', [Admin\PrimaryPharmacistController::class, 'approveTransfer'])->name('pharmacy.transfers.approve');

// Prescriptions routes
Route::get('/prescriptions', [Admin\PrescriptionController::class, 'index'])->name('prescriptions.index');
Route::get('/prescriptions/create', [Admin\PrescriptionController::class, 'create'])->name('prescriptions.create');
Route::post('/prescriptions', [Admin\PrescriptionController::class, 'store'])->name('prescriptions.store');

// Prescription Templates
Route::get('/prescriptions/templates', [Admin\PrescriptionController::class, 'templates'])->name('prescriptions.templates');
Route::post('/prescriptions/templates', [Admin\PrescriptionController::class, 'storeTemplate'])->name('prescriptions.templates.store');
Route::get('/prescriptions/template/{id}/details', [Admin\PrescriptionController::class, 'getTemplateDetails'])->name('prescriptions.template.details');
Route::post('/prescriptions/template/{id}/use', [Admin\PrescriptionController::class, 'useTemplate'])->name('prescriptions.template.use');
Route::get('/prescriptions/template/{id}/use-form', [Admin\PrescriptionController::class, 'useTemplateForm'])->name('prescriptions.template.use-form');
Route::get('/prescriptions/template/{id}/view', [Admin\PrescriptionController::class, 'viewTemplate'])->name('prescriptions.template.view');
Route::get('/prescriptions/template/{id}/edit', [Admin\PrescriptionController::class, 'editTemplate'])->name('prescriptions.template.edit');
Route::put('/prescriptions/template/{id}/update', [Admin\PrescriptionController::class, 'updateTemplate'])->name('prescriptions.template.update');

// Individual prescription routes (must be defined after templates)
Route::get('/prescriptions/{id}', [Admin\PrescriptionController::class, 'show'])->name('prescriptions.show');
Route::get('/prescriptions/{id}/edit', [Admin\PrescriptionController::class, 'edit'])->name('prescriptions.edit');
Route::put('/prescriptions/{id}', [Admin\PrescriptionController::class, 'update'])->name('prescriptions.update');
Route::get('/prescriptions/{id}/renew', [Admin\PrescriptionController::class, 'renew'])->name('prescriptions.renew');
Route::post('/prescriptions/{id}/renew', [Admin\PrescriptionController::class, 'storeRenewal'])->name('prescriptions.store-renewal');
Route::delete('/prescriptions/{id}/cancel', [Admin\PrescriptionController::class, 'cancel'])->name('prescriptions.cancel');
Route::get('/prescriptions/{id}/print', [Admin\PrescriptionController::class, 'print'])->name('prescriptions.print');

// Simple test route to check if the route is working
Route::get('/prescriptions/templates-simple', function () {
    return response()->json(['message' => 'Templates simple route working']);
});

Route::get('/prescriptions/patient-details', [Admin\PrescriptionController::class, 'getPatientDetails'])->name('prescriptions.patient-details');
Route::get('/prescriptions/search-patients', [Admin\PrescriptionController::class, 'searchPatients'])->name('prescriptions.search-patients');
    
// Test route without auth middleware
Route::get('/test-templates', function () {
    \Illuminate\Support\Facades\Log::info('Accessing test templates route');
    return response()->json(['message' => 'Test route working']);
});

// Pharmacy routes - Senior Pharmacist
Route::post('/clinic/request-stock', [Admin\SeniorPharmacistController::class, 'requestStock'])->name('clinic.request-stock');
Route::post('/clinic/transfer/receive/{id}', [Admin\SeniorPharmacistController::class, 'receiveStock'])->name('clinic.transfer.receive');
Route::get('/clinic/alerts', [Admin\SeniorPharmacistController::class, 'getLowStockAlerts'])->name('clinic.alerts');

// Pharmacy routes - Clinic Pharmacist
Route::post('/clinic/sell', [Admin\ClinicPharmacistController::class, 'sell'])->name('clinic.sell');

// Matron routes
Route::get('/matron', [Admin\MatronController::class, 'index'])->name('matrons.index');
Route::get('/matron/create', [Admin\MatronController::class, 'create'])->name('matrons.create');
Route::post('/matron', [Admin\MatronController::class, 'store'])->name('matrons.store');
Route::get('/matron/{matron}/edit', [Admin\MatronController::class, 'edit'])->name('matrons.edit');
Route::put('/matron/{matron}', [Admin\MatronController::class, 'update'])->name('matrons.update');
Route::delete('/matron/{matron}', [Admin\MatronController::class, 'destroy'])->name('matrons.destroy');

// Payments routes
Route::get('/payments', [Admin\PaymentController::class, 'index'])->name('payments.index');
Route::get('/payments/create', [Admin\PaymentController::class, 'create'])->name('payments.create');
Route::post('/payments', [Admin\PaymentController::class, 'store'])->name('payments.store');
Route::get('/payments/{payment}', [Admin\PaymentController::class, 'show'])->name('payments.show');
Route::get('/payments/{payment}/edit', [Admin\PaymentController::class, 'edit'])->name('payments.edit');
Route::put('/payments/{payment}', [Admin\PaymentController::class, 'update'])->name('payments.update');
Route::delete('/payments/{payment}', [Admin\PaymentController::class, 'destroy'])->name('payments.destroy');
Route::get('/payments/{payment}/invoice', [Admin\PaymentController::class, 'invoice'])->name('payments.invoice');
// Add this route for the invoice page in sidemenu
Route::get('/invoice', [Admin\PaymentController::class, 'invoiceList'])->name('payments.invoice.list');

// Paystack routes
Route::post('/payments/paystack/initialize', [Admin\PaymentController::class, 'initializePaystack'])->name('payments.paystack.initialize');
Route::get('/payments/paystack/callback', [Admin\PaymentController::class, 'handlePaystackCallback'])->name('payments.paystack.callback');

// Custom payment status pages
Route::get('/payments/success', function () {
    return view('admin.payments.success');
})->name('payments.success');
Route::get('/payments/failed', function () {
    return view('admin.payments.failed');
})->name('payments.failed');
Route::get('/payments/pending', [Admin\PaymentController::class, 'showPendingPayment'])->name('payments.pending');

// Admin Wallet Top-Up routes
Route::get('/wallet/topup', [Admin\PaymentController::class, 'showTopUpForm'])->name('payment.topup');
Route::post('/wallet/topup/initialize', [Admin\PaymentController::class, 'initializeTopUp'])->name('payment.initialize-topup');

// Service Management routes
Route::get('/services', [Admin\ServiceController::class, 'index'])->name('services.index');
Route::get('/services/all', [Admin\ServiceController::class, 'showAll'])->name('services.all');
Route::get('/services/create', [Admin\ServiceController::class, 'create'])->name('services.create');
Route::post('/services', [Admin\ServiceController::class, 'store'])->name('services.store');
Route::get('/services/{service}/edit', [Admin\ServiceController::class, 'edit'])->name('services.edit');
Route::put('/services/{service}', [Admin\ServiceController::class, 'update'])->name('services.update');
Route::delete('/services/{service}', [Admin\ServiceController::class, 'destroy'])->name('services.destroy');
Route::get('/services/api', [Admin\ServiceController::class, 'apiIndex'])->name('services.api');
Route::get('/services/test', [Admin\ServiceController::class, 'test'])->name('services.test');

// Appointment Payment routes
Route::match(['get', 'post'], '/appointment/payment/initialize', [Admin\PaymentController::class, 'initializeAppointmentPayment'])->name('appointment.payment.initialize');