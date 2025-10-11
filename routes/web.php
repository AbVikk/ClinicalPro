<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\PrescriptionTemplate;
use App\Models\Drug;
use App\Models\User;

Route::get('/test-login', function () {
    $user = App\Models\User::where('role', 'admin')->first();
    if ($user) {
        Auth::login($user);
        return response()->json(['message' => 'Logged in as admin user ID: ' . $user->id]);
    } else {
        return response()->json(['message' => 'No admin user found']);
    }
});

Route::get('/test-templates-direct', function () {
    $user = App\Models\User::where('role', 'admin')->first();
    if ($user) {
        Auth::login($user);
        $controller = new App\Http\Controllers\Admin\PrescriptionController();
        return app()->call([$controller, 'templates']);
    } else {
        return response()->json(['message' => 'No admin user found']);
    }
});

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-public-route', function () {
    return response()->json(['message' => 'Public route working']);
});

// Paystack webhook route (public, no authentication)
Route::post('/paystack/webhook', [App\Http\Controllers\PaystackWebhookController::class, 'handleWebhook']);

// Test route to check if routing is working
Route::get('/test-route', function () {
    return response()->json(['message' => 'Test route working']);
});

// Test route to check if the rolesPermissions method is working
Route::get('/test-roles-permissions', [App\Http\Controllers\Admin\ClinicStaffController::class, 'rolesPermissions']);

// Public invitation routes
Route::get('/register/invite/{token}', [App\Http\Controllers\Admin\InvitationController::class, 'showRegistrationForm'])
    ->middleware('guest', 'signed')
    ->name('invitations.register');
Route::post('/register/invite/{token}', [App\Http\Controllers\Admin\InvitationController::class, 'register'])
    ->middleware('guest')
    ->name('invitations.process');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.sign-in');
    })->name('login');
    
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    
    // New multi-step registration routes
    Route::get('/register', [App\Http\Controllers\Auth\RegistrationController::class, 'showInitialForm'])->name('register.initial');
    Route::post('/register/initial', [App\Http\Controllers\Auth\RegistrationController::class, 'processInitialForm'])->name('register.process.initial');
    Route::get('/register/otp', [App\Http\Controllers\Auth\RegistrationController::class, 'showOtpForm'])->name('register.otp');
    Route::post('/register/otp', [App\Http\Controllers\Auth\RegistrationController::class, 'verifyOtp'])->name('register.verify.otp');
    Route::get('/register/continue', [App\Http\Controllers\Auth\RegistrationController::class, 'showContinueForm'])->name('register.continue');
    Route::post('/register/continue', [App\Http\Controllers\Auth\RegistrationController::class, 'processContinueForm'])->name('register.process.continue');
    Route::get('/register/photo/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showPhotoForm'])->name('register.photo');
    Route::post('/register/photo', [App\Http\Controllers\Auth\RegistrationController::class, 'processPhotoForm'])->name('register.process.photo');
    Route::get('/register/proof/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showProofForm'])->name('register.proof');
    Route::post('/register/proof', [App\Http\Controllers\Auth\RegistrationController::class, 'processProofUpload'])->name('register.process.proof');
    Route::get('/register/license/{user_id}', [App\Http\Controllers\Auth\RegistrationController::class, 'showLicenseForm'])->name('register.license');
    Route::post('/register/license', [App\Http\Controllers\Auth\RegistrationController::class, 'processLicenseForm'])->name('register.process.license');
});

// Logout routes (accessible to everyone)
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('/logout-page', function () {
    return view('auth.logout');
})->name('logout.page');

// Password reset routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::post('/forgot-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/forgot-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'showOtpForm'])->name('password.show-otp');
    Route::post('/forgot-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'verifyOtp'])->name('password.verify-otp');
    Route::post('/resend-otp', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'resendOtp'])->name('password.resend-otp');
    
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\CustomPasswordResetController::class, 'resetPassword'])->name('password.update');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/test', function () {
        return response()->json(['message' => 'Admin test route working']);
    });
    
    Route::get('/admin/index', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.index');
    Route::get('/admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Roles and Permissions route - moved to the top to avoid conflicts
    Route::get('/admin/clinic-staff/roles-permissions', [App\Http\Controllers\Admin\ClinicStaffController::class, 'rolesPermissions'])->name('admin.clinic-staff.roles-permissions');
    
    // Attendance routes
    Route::get('/admin/clinic-staff/attendance', [App\Http\Controllers\Admin\ClinicStaffController::class, 'attendance'])->name('admin.clinic-staff.attendance');
    Route::post('/admin/clinic-staff/attendance/record', [App\Http\Controllers\Admin\ClinicStaffController::class, 'recordAttendance'])->name('admin.clinic-staff.attendance.record');
    Route::get('/admin/clinic-staff/attendance/calendar-data', [App\Http\Controllers\Admin\ClinicStaffController::class, 'getCalendarDataAjax'])->name('admin.clinic-staff.attendance.calendar-data');
    
    // Clinic Staff routes
    Route::get('/admin/clinic-staff', [App\Http\Controllers\Admin\ClinicStaffController::class, 'index'])->name('admin.clinic-staff.index');
    Route::get('/admin/clinic-staff/add', [App\Http\Controllers\Admin\ClinicStaffController::class, 'create'])->name('admin.clinic-staff.add');
    Route::post('/admin/clinic-staff', [App\Http\Controllers\Admin\ClinicStaffController::class, 'store'])->name('admin.clinic-staff.store');
    Route::get('/admin/clinic-staff/{id}', [App\Http\Controllers\Admin\ClinicStaffController::class, 'show'])->name('admin.clinic-staff.show');
    Route::get('/admin/clinic-staff/{id}/edit', [App\Http\Controllers\Admin\ClinicStaffController::class, 'edit'])->name('admin.clinic-staff.edit');
    Route::put('/admin/clinic-staff/{id}', [App\Http\Controllers\Admin\ClinicStaffController::class, 'update'])->name('admin.clinic-staff.update');
    Route::delete('/admin/clinic-staff/{id}', [App\Http\Controllers\Admin\ClinicStaffController::class, 'destroy'])->name('admin.clinic-staff.destroy');
    
    // Invitation routes
    Route::get('/admin/invitations', [App\Http\Controllers\Admin\InvitationController::class, 'index'])->name('admin.invitations.index');
    Route::get('/admin/invitations/create', [App\Http\Controllers\Admin\InvitationController::class, 'create'])->name('admin.invitations.create');
    Route::post('/admin/invitations', [App\Http\Controllers\Admin\InvitationController::class, 'store'])->name('admin.invitations.store');
    Route::delete('/admin/invitations/{invitation}', [App\Http\Controllers\Admin\InvitationController::class, 'destroy'])->name('admin.invitations.destroy');
    
    // Doctor management routes
    Route::get('/admin/doctor', [App\Http\Controllers\Admin\DoctorController::class, 'index'])->name('admin.doctor.index');
    
    Route::get('/admin/doctor/add', [App\Http\Controllers\Admin\DoctorController::class, 'create'])->name('admin.doctor.add');
    
    Route::post('/admin/doctor', [App\Http\Controllers\Admin\DoctorController::class, 'store'])->name('admin.doctor.store');
    
    Route::get('/admin/doctor/dashboard', [App\Http\Controllers\Admin\DoctorController::class, 'dashboard'])->name('admin.doctor.dashboard');
    
    Route::get('/admin/doctor/schedule', [App\Http\Controllers\Admin\DoctorController::class, 'schedule'])->name('admin.doctor.schedule');
    Route::post('/admin/doctor/schedule', [App\Http\Controllers\Admin\DoctorController::class, 'storeSchedule'])->name('admin.doctor.schedule.store');
    Route::post('/admin/doctor/schedule/update', [App\Http\Controllers\Admin\DoctorController::class, 'updateSchedule'])->name('admin.doctor.schedule.update');
    
    Route::get('/admin/doctor/{doctor}', [App\Http\Controllers\Admin\DoctorController::class, 'show'])->name('admin.doctor.profile');
    
    Route::get('/admin/doctor/{doctor}/edit', [App\Http\Controllers\Admin\DoctorController::class, 'edit'])->name('admin.doctor.edit');
    
    Route::put('/admin/doctor/{doctor}', [App\Http\Controllers\Admin\DoctorController::class, 'update'])->name('admin.doctor.update');
    
    Route::delete('/admin/doctor/{doctor}', [App\Http\Controllers\Admin\DoctorController::class, 'destroy'])->name('admin.doctor.destroy');
    
    // Doctor specialization routes
    Route::get('/admin/doctor/specialization/specializations', [App\Http\Controllers\Admin\DoctorController::class, 'specializations'])->name('admin.doctor.specialization.index');
    
    Route::get('/admin/doctor/specialization/add_categories', function () {
        return view('admin.doctor.specialization.add_categories');
    })->name('admin.doctor.specialization.add_categories');
    
    Route::get('/admin/doctor/specialization/add_department', function () {
        return view('admin.doctor.specialization.add_department');
    })->name('admin.doctor.specialization.add_department');
    
    // Department routes
    Route::get('/admin/doctor/specialization/departments', [App\Http\Controllers\Admin\DepartmentController::class, 'index'])->name('admin.doctor.specialization.departments');
    Route::get('/admin/doctor/specialization/departments/add', [App\Http\Controllers\Admin\DepartmentController::class, 'create'])->name('admin.doctor.specialization.add_department_form');
    Route::post('/admin/doctor/specialization/departments', [App\Http\Controllers\Admin\DepartmentController::class, 'store'])->name('admin.departments.store');
    Route::get('/admin/doctor/specialization/departments/{department}', [App\Http\Controllers\Admin\DepartmentController::class, 'show'])->name('admin.doctor.specialization.department_show');
    Route::get('/admin/doctor/specialization/departments/{department}/edit', [App\Http\Controllers\Admin\DepartmentController::class, 'edit'])->name('admin.doctor.specialization.edit_department');
    Route::put('/admin/doctor/specialization/departments/{department}', [App\Http\Controllers\Admin\DepartmentController::class, 'update'])->name('admin.departments.update');
    Route::delete('/admin/doctor/specialization/departments/{department}', [App\Http\Controllers\Admin\DepartmentController::class, 'destroy'])->name('admin.departments.destroy');
    
    // Category routes
    Route::get('/admin/doctor/specialization/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.doctor.specialization.categories');
    Route::get('/admin/doctor/specialization/categories/add', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.doctor.specialization.add_category_form');
    Route::post('/admin/doctor/specialization/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/doctor/specialization/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'show'])->name('admin.doctor.specialization.category_show');
    Route::get('/admin/doctor/specialization/categories/{category}/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.doctor.specialization.edit_category');
    Route::put('/admin/doctor/specialization/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/doctor/specialization/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // Book Appointment routes
    Route::get('/admin/book-appointment', [App\Http\Controllers\Admin\BookAppointmentController::class, 'index'])->name('admin.book-appointment');
    Route::post('/admin/book-appointment/patient-info', [App\Http\Controllers\Admin\BookAppointmentController::class, 'getPatientInfo'])->name('admin.book-appointment.patient-info');
    Route::post('/admin/book-appointment/available-doctors', [App\Http\Controllers\Admin\BookAppointmentController::class, 'getAvailableDoctors'])->name('admin.book-appointment.available-doctors');
    Route::post('/admin/book-appointment', [App\Http\Controllers\Admin\BookAppointmentController::class, 'store'])->name('admin.book-appointment.store');
    Route::post('/admin/book-appointment/walk-in-patient', [App\Http\Controllers\Admin\BookAppointmentController::class, 'storeWalkInPatient'])->name('admin.book-appointment.walk-in-patient');
    
    // Doctor Availability routes
    Route::get('/admin/doctors/availability', [App\Http\Controllers\Admin\BookAppointmentController::class, 'showAvailabilityForm'])->name('admin.doctors.availability');
    Route::post('/admin/doctors/availability', [App\Http\Controllers\Admin\BookAppointmentController::class, 'updateAvailability'])->name('admin.doctors.availability.update');
    
    // Appointments routes
    Route::get('/admin/appointments', [App\Http\Controllers\Admin\AppointmentController::class, 'index'])->name('appointments.index');
    Route::get('/admin/appointment/{id}', [App\Http\Controllers\Admin\AppointmentController::class, 'show'])->name('appointment.show');
    Route::put('/admin/appointments/{id}/assign-doctor', [App\Http\Controllers\Admin\AppointmentController::class, 'assignDoctor'])->name('appointments.assign-doctor');
    Route::put('/admin/appointments/{id}', [App\Http\Controllers\Admin\AppointmentController::class, 'update'])->name('appointments.update');
    Route::delete('/admin/appointments/{id}', [App\Http\Controllers\Admin\AppointmentController::class, 'destroy'])->name('appointments.destroy');
    
    // Patients routes
    Route::get('/admin/patients', [App\Http\Controllers\Admin\PatientController::class, 'index'])->name('patients.index');
    Route::get('/admin/patient/{id}', [App\Http\Controllers\Admin\PatientController::class, 'show'])->name('patient.show');
    Route::delete('/admin/patient/{id}', [App\Http\Controllers\Admin\PatientController::class, 'destroy'])->name('patient.destroy');
    
    // Pharmacists routes
    Route::get('/admin/pharmacists', [App\Http\Controllers\Admin\PharmacistController::class, 'index'])->name('admin.pharmacists.index');
    Route::get('/admin/pharmacists/{pharmacist}', [App\Http\Controllers\Admin\PharmacistController::class, 'show'])->name('admin.pharmacists.show');
    Route::put('/admin/pharmacists/{pharmacist}', [App\Http\Controllers\Admin\PharmacistController::class, 'update'])->name('admin.pharmacists.update');
    Route::delete('/admin/pharmacists/{pharmacist}', [App\Http\Controllers\Admin\PharmacistController::class, 'destroy'])->name('admin.pharmacists.destroy');
    
    // Pharmacy routes
    Route::get('/admin/pharmacy', function () {
        return view('admin.pharmacy.index');
    })->name('admin.pharmacy.dashboard');
    
    // Drug Categories
    Route::resource('/admin/pharmacy/categories', App\Http\Controllers\Admin\DrugCategoryController::class)->names([
        'index' => 'admin.pharmacy.categories.index',
        'create' => 'admin.pharmacy.categories.create',
        'store' => 'admin.pharmacy.categories.store',
        'edit' => 'admin.pharmacy.categories.edit',
        'update' => 'admin.pharmacy.categories.update',
        'destroy' => 'admin.pharmacy.categories.destroy',
    ]);
    
    // Drug MG Values
    Route::resource('/admin/pharmacy/mg', App\Http\Controllers\Admin\DrugMgController::class)->names([
        'index' => 'admin.pharmacy.mg.index',
        'create' => 'admin.pharmacy.mg.create',
        'store' => 'admin.pharmacy.mg.store',
        'edit' => 'admin.pharmacy.mg.edit',
        'update' => 'admin.pharmacy.mg.update',
        'destroy' => 'admin.pharmacy.mg.destroy',
    ]);
    
    // Pharmacy routes - Primary Pharmacist
    Route::get('/admin/pharmacy/drugs/create-form', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'showCreateDrugForm'])->name('admin.pharmacy.drugs.create.form');
    Route::get('/admin/pharmacy/drugs/{id}', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'viewDrug'])->name('admin.pharmacy.drugs.view');
    Route::get('/admin/pharmacy/drugs/{id}/edit', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'editDrug'])->name('admin.pharmacy.drugs.edit');
    Route::post('/admin/pharmacy/drugs/create', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'createDrug'])->name('admin.pharmacy.drugs.create');
    Route::put('/admin/pharmacy/drugs/{id}', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'updateDrug'])->name('admin.pharmacy.drugs.update');
    Route::delete('/admin/pharmacy/drugs/{id}', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'deleteDrug'])->name('admin.pharmacy.drugs.delete');
    Route::post('/admin/pharmacy/stock/receive', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'receiveStock'])->name('admin.pharmacy.stock.receive');
    Route::post('/admin/pharmacy/stock/update', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'updateStock'])->name('admin.pharmacy.stock.update');
    Route::get('/admin/pharmacy/drugs/{id}/history', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'getDrugHistory'])->name('admin.pharmacy.drugs.history');
    Route::post('/admin/pharmacy/transfers/approve/{id}', [App\Http\Controllers\Admin\PrimaryPharmacistController::class, 'approveTransfer'])->name('admin.pharmacy.transfers.approve');
    
    // Prescriptions routes
    Route::get('/admin/prescriptions', [App\Http\Controllers\Admin\PrescriptionController::class, 'index'])->name('admin.prescriptions.index');
    Route::get('/admin/prescriptions/create', [App\Http\Controllers\Admin\PrescriptionController::class, 'create'])->name('admin.prescriptions.create');
    Route::post('/admin/prescriptions', [App\Http\Controllers\Admin\PrescriptionController::class, 'store'])->name('admin.prescriptions.store');
    
    // Prescription Templates
    Route::get('/admin/prescriptions/templates', [App\Http\Controllers\Admin\PrescriptionController::class, 'templates'])->name('admin.prescriptions.templates');
    Route::post('/admin/prescriptions/templates', [App\Http\Controllers\Admin\PrescriptionController::class, 'storeTemplate'])->name('admin.prescriptions.templates.store');
    Route::get('/admin/prescriptions/template/{id}/details', [App\Http\Controllers\Admin\PrescriptionController::class, 'getTemplateDetails'])->name('admin.prescriptions.template.details');
    Route::post('/admin/prescriptions/template/{id}/use', [App\Http\Controllers\Admin\PrescriptionController::class, 'useTemplate'])->name('admin.prescriptions.template.use');
    Route::get('/admin/prescriptions/template/{id}/use-form', [App\Http\Controllers\Admin\PrescriptionController::class, 'useTemplateForm'])->name('admin.prescriptions.template.use-form');
    Route::get('/admin/prescriptions/template/{id}/view', [App\Http\Controllers\Admin\PrescriptionController::class, 'viewTemplate'])->name('admin.prescriptions.template.view');
    Route::get('/admin/prescriptions/template/{id}/edit', [App\Http\Controllers\Admin\PrescriptionController::class, 'editTemplate'])->name('admin.prescriptions.template.edit');
    Route::put('/admin/prescriptions/template/{id}/update', [App\Http\Controllers\Admin\PrescriptionController::class, 'updateTemplate'])->name('admin.prescriptions.template.update');
    
    // Individual prescription routes (must be defined after templates)
    Route::get('/admin/prescriptions/{id}', [App\Http\Controllers\Admin\PrescriptionController::class, 'show'])->name('admin.prescriptions.show');
    Route::get('/admin/prescriptions/{id}/edit', [App\Http\Controllers\Admin\PrescriptionController::class, 'edit'])->name('admin.prescriptions.edit');
    Route::put('/admin/prescriptions/{id}', [App\Http\Controllers\Admin\PrescriptionController::class, 'update'])->name('admin.prescriptions.update');
    Route::get('/admin/prescriptions/{id}/renew', [App\Http\Controllers\Admin\PrescriptionController::class, 'renew'])->name('admin.prescriptions.renew');
    Route::post('/admin/prescriptions/{id}/renew', [App\Http\Controllers\Admin\PrescriptionController::class, 'storeRenewal'])->name('admin.prescriptions.store-renewal');
    Route::delete('/admin/prescriptions/{id}/cancel', [App\Http\Controllers\Admin\PrescriptionController::class, 'cancel'])->name('admin.prescriptions.cancel');
    Route::get('/admin/prescriptions/{id}/print', [App\Http\Controllers\Admin\PrescriptionController::class, 'print'])->name('admin.prescriptions.print');
    
    // Simple test route to check if the route is working
    Route::get('/admin/prescriptions/templates-simple', function () {
        return response()->json(['message' => 'Templates simple route working']);
    });
    
    Route::get('/admin/prescriptions/patient-details', [App\Http\Controllers\Admin\PrescriptionController::class, 'getPatientDetails'])->name('admin.prescriptions.patient-details');
    Route::get('/admin/prescriptions/search-patients', [App\Http\Controllers\Admin\PrescriptionController::class, 'searchPatients'])->name('admin.prescriptions.search-patients');
        
    // Test route without auth middleware
    Route::get('/test-templates', function () {
        \Illuminate\Support\Facades\Log::info('Accessing test templates route');
        return response()->json(['message' => 'Test route working']);
    });
    
    // Pharmacy routes - Senior Pharmacist
    Route::post('/admin/clinic/request-stock', [App\Http\Controllers\Admin\SeniorPharmacistController::class, 'requestStock'])->name('admin.clinic.request-stock');
    Route::post('/admin/clinic/transfer/receive/{id}', [App\Http\Controllers\Admin\SeniorPharmacistController::class, 'receiveStock'])->name('admin.clinic.transfer.receive');
    Route::get('/admin/clinic/alerts', [App\Http\Controllers\Admin\SeniorPharmacistController::class, 'getLowStockAlerts'])->name('admin.clinic.alerts');
    
    // Pharmacy routes - Clinic Pharmacist
    Route::post('/admin/clinic/sell', [App\Http\Controllers\Admin\ClinicPharmacistController::class, 'sell'])->name('admin.clinic.sell');
    
    // Matron routes
    Route::get('/admin/matron', [App\Http\Controllers\Admin\MatronController::class, 'index'])->name('admin.matrons.index');
    Route::get('/admin/matron/create', [App\Http\Controllers\Admin\MatronController::class, 'create'])->name('admin.matrons.create');
    Route::post('/admin/matron', [App\Http\Controllers\Admin\MatronController::class, 'store'])->name('admin.matrons.store');
    Route::get('/admin/matron/{matron}/edit', [App\Http\Controllers\Admin\MatronController::class, 'edit'])->name('admin.matrons.edit');
    Route::put('/admin/matron/{matron}', [App\Http\Controllers\Admin\MatronController::class, 'update'])->name('admin.matrons.update');
    Route::delete('/admin/matron/{matron}', [App\Http\Controllers\Admin\MatronController::class, 'destroy'])->name('admin.matrons.destroy');
    
    // Payments routes
    Route::get('/admin/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/admin/payments/create', [App\Http\Controllers\Admin\PaymentController::class, 'create'])->name('admin.payments.create');
    Route::post('/admin/payments', [App\Http\Controllers\Admin\PaymentController::class, 'store'])->name('admin.payments.store');
    Route::get('/admin/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('admin.payments.show');
    Route::get('/admin/payments/{payment}/edit', [App\Http\Controllers\Admin\PaymentController::class, 'edit'])->name('admin.payments.edit');
    Route::put('/admin/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'update'])->name('admin.payments.update');
    Route::delete('/admin/payments/{payment}', [App\Http\Controllers\Admin\PaymentController::class, 'destroy'])->name('admin.payments.destroy');
    Route::get('/admin/payments/{payment}/invoice', [App\Http\Controllers\Admin\PaymentController::class, 'invoice'])->name('admin.payments.invoice');
    // Add this route for the invoice page in sidemenu
    Route::get('/admin/invoice', function () {
        return view('admin.invoice');
    })->name('admin.payments.invoice.list');
    
    // Paystack routes
    Route::post('/admin/payments/paystack/initialize', [App\Http\Controllers\Admin\PaymentController::class, 'initializePaystack'])->name('admin.payments.paystack.initialize');
    Route::get('/admin/payments/paystack/callback', [App\Http\Controllers\Admin\PaymentController::class, 'handlePaystackCallback'])->name('admin.payments.paystack.callback');
    
    // Admin Wallet Top-Up routes
    Route::get('/admin/wallet/topup', [App\Http\Controllers\Admin\PaymentController::class, 'showTopUpForm'])->name('admin.payment.topup');
    Route::post('/admin/wallet/topup/initialize', [App\Http\Controllers\Admin\PaymentController::class, 'initializeTopUp'])->name('admin.payment.initialize-topup');
    Route::get('/admin/wallet/topup/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verifyPayment'])->name('admin.payment.verify');
    Route::get('/admin/wallet/test-webhook', function () {
        return view('admin.wallet.test_webhook');
    })->name('admin.wallet.test-webhook');
});

// HOD routes
Route::middleware(['auth', 'role:hod'])->group(function () {
    Route::get('/hod/dashboard', [App\Http\Controllers\HOD\DashboardController::class, 'index'])->name('hod.dashboard');
    
    // Doctor management routes
    Route::get('/hod/doctors', [App\Http\Controllers\HOD\DoctorManagementController::class, 'index'])->name('hod.doctors.index');
    Route::post('/hod/doctors/assign', [App\Http\Controllers\HOD\DoctorManagementController::class, 'assignDoctor'])->name('hod.doctors.assign');
    Route::post('/hod/doctors/remove', [App\Http\Controllers\HOD\DoctorManagementController::class, 'removeDoctor'])->name('hod.doctors.remove');
});

// Matron routes
Route::middleware(['auth', 'role:matron'])->group(function () {
    Route::get('/matron/dashboard', [App\Http\Controllers\Matron\StaffManagementController::class, 'index'])->name('matron.dashboard');
    
    // Staff management routes
    Route::get('/matron/staff', [App\Http\Controllers\Matron\StaffManagementController::class, 'index'])->name('matron.staff.index');
    Route::post('/matron/staff/assign', [App\Http\Controllers\Matron\StaffManagementController::class, 'assignStaff'])->name('matron.staff.assign');
    Route::post('/matron/staff/remove', [App\Http\Controllers\Matron\StaffManagementController::class, 'removeStaff'])->name('matron.staff.remove');
});

// Doctor routes
Route::middleware(['auth', 'role:doctor'])->group(function () {
    Route::get('/doctor/dashboard', [App\Http\Controllers\Doctor\DashboardController::class, 'index'])->name('doctor.dashboard');
    
    // Prescription routes
    Route::post('/doctor/consultations/prescribe', [App\Http\Controllers\Doctor\PrescriptionController::class, 'prescribe'])->name('doctor.consultations.prescribe');
    Route::get('/doctor/prescriptions/{id}', [App\Http\Controllers\Doctor\PrescriptionController::class, 'checkFulfillment'])->name('doctor.prescriptions.check');
});

// Clinic staff routes
Route::middleware(['auth', 'role:nurse'])->group(function () {
    Route::get('/clinic/dashboard', [App\Http\Controllers\Clinic\DashboardController::class, 'index'])->name('clinic.dashboard');
});

// Patient routes
Route::middleware(['auth', 'role:patient'])->group(function () {
    Route::get('/patient/dashboard', [App\Http\Controllers\Patient\DashboardController::class, 'index'])->name('patient.dashboard');
    
    // PWA Pharmacy routes
    Route::get('/pwa/pharmacy', [App\Http\Controllers\PwaPharmacyController::class, 'index'])->name('pwa.pharmacy.index');
    Route::get('/pwa/pharmacy/search', [App\Http\Controllers\PwaPharmacyController::class, 'search'])->name('pwa.pharmacy.search');
});

// Donor routes
Route::middleware(['auth', 'role:donor'])->group(function () {
    Route::get('/donor/dashboard', [App\Http\Controllers\Donor\DashboardController::class, 'index'])->name('donor.dashboard');
});

// Pharmacist routes
Route::middleware(['auth', 'role:primary_pharmacist|senior_pharmacist|clinic_pharmacist'])->group(function () {
    Route::get('/pharmacy/dashboard', [App\Http\Controllers\Pharmacy\DashboardController::class, 'index'])->name('pharmacy.dashboard');
});