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

// Test route for services API (public for testing)
Route::get('/test-services-api', function () {
    $services = App\Models\Service::all();
    return response()->json($services);
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
    
    // HOD routes (moved before parameterized routes to avoid conflicts)
    Route::get('/admin/doctor/hods', [App\Http\Controllers\Admin\DoctorController::class, 'listHODs'])->name('admin.doctor.hods');
    Route::put('/admin/doctor/assign-hod/{user}', [App\Http\Controllers\Admin\DoctorController::class, 'assignHOD'])->name('admin.doctor.assign-hod');
    Route::put('/admin/doctor/assign-doctor-role/{user}', [App\Http\Controllers\Admin\DoctorController::class, 'assignDoctorRole'])->name('admin.doctor.assign-doctor-role');
    
    // Parameterized routes (must come after HOD routes)
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
    Route::post('/admin/book-appointment/search-patients', [App\Http\Controllers\Admin\BookAppointmentController::class, 'searchPatients'])->name('admin.book-appointment.search-patients');
    Route::post('/admin/book-appointment', [App\Http\Controllers\Admin\BookAppointmentController::class, 'store'])->name('admin.book-appointment.store');
    Route::post('/admin/book-appointment/walk-in-patient', [App\Http\Controllers\Admin\BookAppointmentController::class, 'storeWalkInPatient'])->name('admin.book-appointment.walk-in-patient');
    Route::get('/admin/book-appointment/payment', [App\Http\Controllers\Admin\BookAppointmentController::class, 'showAppointmentPayment'])->name('admin.book-appointment.payment');
    
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
    Route::get('/admin/invoice', [App\Http\Controllers\Admin\PaymentController::class, 'invoiceList'])->name('admin.payments.invoice.list');
    
    // Paystack routes
    Route::post('/admin/payments/paystack/initialize', [App\Http\Controllers\Admin\PaymentController::class, 'initializePaystack'])->name('admin.payments.paystack.initialize');
    Route::get('/admin/payments/paystack/callback', [App\Http\Controllers\Admin\PaymentController::class, 'handlePaystackCallback'])->name('admin.payments.paystack.callback');

    // Custom payment status pages
    Route::get('/admin/payments/success', function () {
        return view('admin.payments.success');
    })->name('admin.payments.success');
    Route::get('/admin/payments/failed', function () {
        return view('admin.payments.failed');
    })->name('admin.payments.failed');
    Route::get('/admin/payments/pending', [App\Http\Controllers\Admin\PaymentController::class, 'showPendingPayment'])->name('admin.payments.pending');

// Admin Wallet Top-Up routes
Route::get('/admin/wallet/topup', [App\Http\Controllers\Admin\PaymentController::class, 'showTopUpForm'])->name('admin.payment.topup');
Route::post('/admin/wallet/topup/initialize', [App\Http\Controllers\Admin\PaymentController::class, 'initializeTopUp'])->name('admin.payment.initialize-topup');

// Service Management routes
Route::get('/admin/services', [App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('admin.services.index');
Route::get('/admin/services/all', [App\Http\Controllers\Admin\ServiceController::class, 'showAll'])->name('admin.services.all');
Route::get('/admin/services/create', [App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('admin.services.create');
Route::post('/admin/services', [App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('admin.services.store');
Route::get('/admin/services/{service}/edit', [App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('admin.services.edit');
Route::put('/admin/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('admin.services.update');
Route::delete('/admin/services/{service}', [App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('admin.services.destroy');
Route::get('/admin/services/api', [App\Http\Controllers\Admin\ServiceController::class, 'apiIndex'])->name('admin.services.api');
Route::get('/admin/services/test', [App\Http\Controllers\Admin\ServiceController::class, 'test'])->name('admin.services.test');

// Appointment Payment routes
Route::match(['get', 'post'], '/admin/appointment/payment/initialize', [App\Http\Controllers\Admin\PaymentController::class, 'initializeAppointmentPayment'])->name('admin.appointment.payment.initialize');
});

// Payment verification route (must be outside auth middleware for Paystack callbacks)
Route::get('/admin/wallet/topup/verify', [App\Http\Controllers\Admin\PaymentController::class, 'verifyPayment'])->name('admin.payment.verify');
Route::get('/admin/wallet/test-webhook', function () {
    return view('admin.wallet.test_webhook');
})->name('admin.wallet.test-webhook');

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
    
    // Appointments routes
    Route::get('/doctor/appointments', [App\Http\Controllers\Doctor\DashboardController::class, 'appointments'])->name('doctor.appointments');
    
    // Appointment details routes
    Route::get('/doctor/appointments/{appointment}/details', [App\Http\Controllers\Doctor\DashboardController::class, 'showAppointmentDetails'])->name('doctor.appointments.details');
    Route::post('/doctor/appointments/{appointment}/save-details', [App\Http\Controllers\Doctor\DashboardController::class, 'saveAppointmentDetails'])->name('doctor.appointments.save-details');
    
    // Notification routes
    Route::post('/doctor/notifications/{notification}/mark-as-read', [App\Http\Controllers\Doctor\DashboardController::class, 'markNotificationAsRead'])->name('doctor.notifications.mark-as-read-single');
    
    // Clean Appointments routes for testing
    Route::get('/doctor/appointments-clean', function () {
        // Get the authenticated doctor's ID
        $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
        
        // Get filter parameters
        $search = request()->get('search');
        $filter = request()->get('filter', 'all'); // all, chat, direct
        $tab = request()->get('tab', 'upcoming'); // upcoming, cancelled, completed
        
        // Build the base query for appointments
        $baseQuery = \App\Models\Appointment::with(['patient.patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId);
            
        // Apply search filter
        if ($search) {
            $baseQuery->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }
        
        // Apply type filter
        if ($filter !== 'all') {
            $baseQuery->where('type', $filter);
        }
        
        // Clone the base query for each tab
        $upcomingQuery = clone $baseQuery;
        $cancelledQuery = clone $baseQuery;
        $completedQuery = clone $baseQuery;
        
        // Apply status filters for each tab
        $upcomingQuery->where(function($query) {
            $query->where(function($q) {
                // Include appointments that are pending/new (requests)
                $q->whereIn('status', ['pending', 'new']);
            })->orWhere(function($q) {
                // OR confirmed future appointments
                $q->where('status', 'confirmed')
                  ->where('appointment_time', '>', now());
            });
        });
        
        $cancelledQuery->where('status', 'cancelled');
        $completedQuery->where('status', 'completed');
        
        // Get paginated results for the active tab
        $perPage = 10;
        switch ($tab) {
            case 'cancelled':
                $appointments = $cancelledQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(['tab' => 'cancelled', 'search' => $search, 'filter' => $filter]);
                break;
            case 'completed':
                $appointments = $completedQuery->orderBy('appointment_time', 'desc')->paginate($perPage)->appends(['tab' => 'completed', 'search' => $search, 'filter' => $filter]);
                break;
            case 'upcoming':
            default:
                $appointments = $upcomingQuery->orderBy('appointment_time', 'asc')->paginate($perPage)->appends(['tab' => 'upcoming', 'search' => $search, 'filter' => $filter]);
                break;
        }
        
        // Get counts for all tabs (without pagination)
        $upcomingCount = $upcomingQuery->count();
        $cancelledCount = $cancelledQuery->count();
        $completedCount = $completedQuery->count();
        
        return view('doctor.appointments_clean', compact(
            'appointments',
            'upcomingCount', 
            'cancelledCount', 
            'completedCount',
            'search',
            'filter',
            'tab'
        ));
    })->name('doctor.appointments.clean');
    
    // Requests routes
    Route::get('/doctor/requests', [App\Http\Controllers\Doctor\DashboardController::class, 'requests'])->name('doctor.requests');
    Route::post('/doctor/requests/{appointment}/accept', [App\Http\Controllers\Doctor\DashboardController::class, 'acceptRequest'])->name('doctor.requests.accept');
    Route::post('/doctor/requests/{appointment}/reject', [App\Http\Controllers\Doctor\DashboardController::class, 'rejectRequest'])->name('doctor.requests.reject');
    Route::get('/doctor/requests/count', [App\Http\Controllers\Doctor\DashboardController::class, 'getRequestCount'])->name('doctor.requests.count');
    
    // Appointment session routes
    Route::post('/doctor/appointments/{appointment}/start', [App\Http\Controllers\Doctor\DashboardController::class, 'startAppointment'])->name('doctor.appointments.start');
    Route::post('/doctor/appointments/{appointment}/end', [App\Http\Controllers\Doctor\DashboardController::class, 'endAppointment'])->name('doctor.appointments.end');
    Route::get('/doctor/appointments/{appointment}/details', [App\Http\Controllers\Doctor\DashboardController::class, 'showAppointmentDetails'])->name('doctor.appointments.details');
    Route::post('/doctor/appointments/{appointment}/save-details', [App\Http\Controllers\Doctor\DashboardController::class, 'saveAppointmentDetails'])->name('doctor.appointments.save-details');
    Route::get('/doctor/patients/{patient}/appointment-history', [App\Http\Controllers\Doctor\DashboardController::class, 'showAppointmentHistory'])->name('doctor.patients.appointment-history');
    Route::get('/doctor/appointments/{appointment}/end', [App\Http\Controllers\Doctor\DashboardController::class, 'endAppointment'])->name('doctor.appointments.end.get');
    
    // Notification routes
    Route::post('/doctor/notifications/mark-as-read', [App\Http\Controllers\Doctor\DashboardController::class, 'markNotificationsAsRead'])->name('doctor.notifications.mark-as-read');
    
    // Prescription routes
    Route::post('/doctor/consultations/prescribe',[App\Http\Controllers\Doctor\PrescriptionController::class, 'prescribe'])->name('doctor.consultations.prescribe');
    Route::get('/doctor/prescriptions/{id}', [App\Http\Controllers\Doctor\PrescriptionController::class, 'checkFulfillment'])->name('doctor.prescriptions.check');
    
    // Patient profile route
    Route::get('/doctor/patient/{patient}', [App\Http\Controllers\Doctor\DashboardController::class, 'showPatient'])->name('doctor.patient.show');
    
    // Patient index route
    Route::get('/doctor/patient', [App\Http\Controllers\Doctor\DashboardController::class, 'indexPatient'])->name('doctor.patient.index');
    
    // Test doctor patient routes
    /*
    Route::get('/doctor/test-patient-routes', function () {
        // Get a patient that has appointments with the current doctor
        $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
        $patient = \App\Models\Patient::with('user')
            ->whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->first();
            
        if (!$patient) {
            return response()->json(['error' => 'No patients found for this doctor']);
        }
        
        try {
            $indexRoute = route('doctor.patient.index');
            $showRoute = route('doctor.patient.show', $patient);
            
            return response()->json([
                'success' => true,
                'routes' => [
                    'index' => $indexRoute,
                    'show' => $showRoute
                ],
                'patient' => [
                    'id' => $patient->id,
                    'name' => $patient->user->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Route generation failed: ' . $e->getMessage()
            ]);
        }
    })->name('doctor.test.patient.routes');
    */
    
    // Test patient routes view
    /*
    Route::get('/doctor/test-patient-routes-view', function () {
        return view('doctor.test-patient-routes');
    })->name('doctor.test.patient.routes.view');
    */
    
    // Link debug view
    /*
    Route::get('/doctor/link-debug', [App\Http\Controllers\Doctor\DashboardController::class, 'linkDebug'])->name('doctor.link.debug');
    */
    
    // Link test page
    /*
    Route::get('/doctor/link-test', function () {
        $samplePatient = \App\Models\Patient::with('user')
            ->whereHas('appointments', function ($query) {
                $query->where('doctor_id', \Illuminate\Support\Facades\Auth::user()->id);
            })
            ->first();
        return view('doctor.link-test', compact('samplePatient'));
    })->name('doctor.link.test');
    */
    
    // Link verification test page
    /*
    Route::get('/doctor/link-verification-test', function () {
        return view('doctor.link-verification-test');
    })->name('doctor.link.verification.test');
    */
    
    // Debug appointment data
    /*
    Route::get('/doctor/debug-appointment-data', function () {
        $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
        
        // Get a sample appointment with all relationships
        $appointment = \App\Models\Appointment::with(['patient.patient', 'appointmentReason', 'doctor'])
            ->where('doctor_id', $doctorId)
            ->first();
            
        if (!$appointment) {
            return response()->json(['error' => 'No appointments found for this doctor']);
        }
        
        // Check if patient relationship exists
        $hasPatient = isset($appointment->patient);
        $hasPatientPatient = $hasPatient && isset($appointment->patient->patient);
        $patientId = $hasPatientPatient ? $appointment->patient->patient->id : null;
        
        // Try to generate the route
        $route = null;
        if ($patientId) {
            try {
                $route = route('doctor.patient.show', $patientId);
            } catch (\Exception $e) {
                $route = 'Error generating route: ' . $e->getMessage();
            }
        }
        
        return response()->json([
            'appointment_id' => $appointment->id,
            'has_patient' => $hasPatient,
            'has_patient_patient' => $hasPatientPatient,
            'patient_id' => $patientId,
            'route' => $route,
            'patient_data' => $hasPatient ? [
                'id' => $appointment->patient->id,
                'name' => $appointment->patient->name,
                'email' => $appointment->patient->email,
                'patient_relationship' => $hasPatientPatient ? [
                    'id' => $appointment->patient->patient->id,
                    'user_id' => $appointment->patient->patient->user_id,
                ] : null
            ] : null,
            'appointment_data' => $appointment->toArray()
        ]);
    })->name('doctor.debug.appointment.data');
    */
    
    // Simple link test
    /*
    Route::get('/doctor/simple-link-test', function () {
        $samplePatient = \App\Models\Patient::with('user')
            ->whereHas('appointments', function ($query) {
                $query->where('doctor_id', \Illuminate\Support\Facades\Auth::user()->id);
            })
            ->first();
        return view('doctor.simple-link-test', compact('samplePatient'));
    })->name('doctor.simple.link.test');
    */
    
    // Debug route
    /*
    Route::get('/doctor/debug', function () {
        return view('doctor.debug');
    })->name('doctor.debug');
    */
    
    // Test appointments route
    /*
    Route::get('/doctor/test-appointments', function () {
        return view('doctor.test-appointments');
    })->name('doctor.test.appointments');
    */
    
    // Route test page
    /*
    Route::get('/doctor/route-test', function () {
        return view('doctor.route-test');
    })->name('doctor.route.test');
    */
    
    // Simple patient profile test
    /*
    Route::get('/doctor/test-patient-profile', function () {
        // Get a patient with appointments
        $patient = \App\Models\Patient::with('user')->first();
        
        if (!$patient) {
            return response()->json(['error' => 'No patients found']);
        }
        
        try {
            $route = route('doctor.patient.show', $patient->id);
            return response()->json([
                'success' => true,
                'route' => $route,
                'patient_id' => $patient->id,
                'patient_name' => $patient->user->name ?? 'Unknown'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Route generation failed: ' . $e->getMessage()
            ]);
        }
    })->name('doctor.test.patient.profile');
    */
    
    // Patient test page
    /*
    Route::get('/doctor/patient-test', function () {
        return view('doctor.patient-test');
    })->name('doctor.patient.test');
    */
    
    // Test patient data route
    /*
    Route::get('/doctor/test-patient-data', function () {
        $doctorId = Auth::id();
        echo "Doctor ID: " . $doctorId . "<br>";
        
        // Test 1: Count all patients
        $allPatients = \App\Models\Patient::count();
        echo "Total patients: " . $allPatients . "<br>";
        
        // Test 2: Count appointments for this doctor
        $doctorAppointments = \App\Models\Appointment::where('doctor_id', $doctorId)->count();
        echo "Doctor appointments: " . $doctorAppointments . "<br>";
        
        // Test 3: Count patients with appointments for this doctor (using join)
        $patientsWithAppointments = \App\Models\Patient::join('appointments', 'patients.user_id', '=', 'appointments.patient_id')
            ->where('appointments.doctor_id', $doctorId)
            ->distinct()
            ->count('patients.id');
        echo "Patients with appointments: " . $patientsWithAppointments . "<br>";
        
        // Test 4: Get actual patient data
        if ($patientsWithAppointments > 0) {
            $patients = \App\Models\Patient::with('user')
                ->join('appointments', 'patients.user_id', '=', 'appointments.patient_id')
                ->where('appointments.doctor_id', $doctorId)
                ->select('patients.*')
                ->distinct()
                ->limit(5)
                ->get();
                
            echo "<h3>Sample patients:</h3>";
            foreach ($patients as $patient) {
                echo "Patient ID: " . $patient->id . ", User ID: " . $patient->user_id . ", Name: " . ($patient->user->name ?? 'N/A') . "<br>";
            }
        }
        
        return;
    })->name('doctor.test.patient.data');
    */
    
    // Debug route to check requests data
    /*
    Route::get('/doctor/debug-requests', function () {
        $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
        $requests = \App\Models\Appointment::with(['patient.patient', 'appointmentReason'])
            ->where('doctor_id', $doctorId)
            ->whereIn('status', ['pending', 'new'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'requests_count' => $requests->count(),
            'requests' => $requests->map(function ($request) {
                return [
                    'id' => $request->id,
                    'patient_exists' => $request->patient ? true : false,
                    'patient_patient_exists' => $request->patient && $request->patient->patient ? true : false,
                    'patient_data' => $request->patient ? [
                        'id' => $request->patient->id,
                        'name' => $request->patient->name,
                        'patient_id' => $request->patient->patient ? $request->patient->patient->id : null,
                    ] : null,
                ];
            })
        ]);
    })->name('doctor.debug.requests');
    */
    
    // Debug route to check patient data
    /*
    Route::get('/doctor/debug-patients', function () {
        $doctorId = \Illuminate\Support\Facades\Auth::user()->id;
        
        // Get patients who have appointments with this doctor
        $patients = \App\Models\Patient::with('user')
            ->whereHas('appointments', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->get();
            
        // Also get all appointments for this doctor
        $appointments = \App\Models\Appointment::with(['patient', 'patient.user'])
            ->where('doctor_id', $doctorId)
            ->get();
            
        // Get all patients (for comparison)
        $allPatients = \App\Models\Patient::with('user')->get();
            
        return response()->json([
            'doctor_id' => $doctorId,
            'patients_count' => $patients->count(),
            'patients' => $patients->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'user_id' => $patient->user_id,
                    'user_name' => $patient->user ? $patient->user->name : null,
                    'user_email' => $patient->user ? $patient->user->email : null,
                ];
            }),
            'appointments_count' => $appointments->count(),
            'appointments' => $appointments->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'patient_name' => $appointment->patient ? $appointment->patient->name : null,
                    'patient_has_user' => $appointment->patient ? ($appointment->patient->user ? true : false) : false,
                ];
            }),
            'all_patients_count' => $allPatients->count(),
            'all_patients' => $allPatients->map(function ($patient) {
                return [
                    'id' => $patient->id,
                    'user_id' => $patient->user_id,
                    'user_name' => $patient->user ? $patient->user->name : null,
                    'user_email' => $patient->user ? $patient->user->email : null,
                ];
            }),
        ]);
    })->name('doctor.debug.patients');
    */
    
    // Debug links page
    /*
    Route::get('/doctor/debug-links', [App\Http\Controllers\Doctor\DashboardController::class, 'debugLinks'])->name('doctor.debug.links');
    */
    
    // Debug patients page
    /*
    Route::get('/doctor/debug-patients-view', function () {
        return view('doctor.debug-patients');
    })->name('doctor.debug.patients.view');
    */
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

// Test routes to debug HOD access
Route::get('/test-hod-route', function () {
    return response()->json(['message' => 'HOD route test working']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/test', function () {
        return response()->json(['message' => 'Admin test route working']);
    });
});

// Test route for debugging appointment accept
Route::get('/test-accept-appointment/{id}', function ($id) {
    $appointment = \App\Models\Appointment::find($id);
    if (!$appointment) {
        return response()->json(['error' => 'Appointment not found']);
    }
    
    try {
        $appointment->update([
            'status' => 'confirmed',
            'appointment_time' => $appointment->appointment_time
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Appointment accepted successfully',
            'appointment' => $appointment
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
})->middleware('auth');

// Test route to create a cancelled appointment
Route::get('/test-create-cancelled-appointment', function () {
    try {
        $appointment = \App\Models\Appointment::create([
            'doctor_id' => 2, // Assuming doctor ID 2 exists
            'patient_id' => 3, // Assuming patient ID 3 exists
            'appointment_time' => now()->addDays(2),
            'type' => 'direct',
            'status' => 'cancelled',
            'reason' => 'Test cancelled appointment',
            'appointment_reason_id' => 1 // Assuming reason ID 1 exists
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Cancelled appointment created successfully',
            'appointment' => $appointment
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
})->middleware('auth');

// Test route to manually create an appointment
/*
Route::get('/test-create-appointment', function () {
    try {
        // Create a test appointment
        $appointment = new \App\Models\Appointment();
        $appointment->patient_id = 3; // Assuming patient ID 3 exists
        $appointment->doctor_id = 5; // Assuming doctor ID 5 exists
        $appointment->appointment_time = now()->addDays(2);
        $appointment->type = 'telehealth';
        $appointment->status = 'pending';
        $appointment->notes = 'Test appointment created manually';
        
        if ($appointment->save()) {
            // Create notification for the doctor
            $doctor = \App\Models\User::find(5);
            $patient = \App\Models\User::find(3);
            if ($doctor && $patient) {
                $message = "New appointment request: {$patient->name} scheduled for " . $appointment->appointment_time->format('M d, Y g:i A');
                \App\Models\Notification::create([
                    'user_id' => $doctor->id,
                    'type' => 'appointment',
                    'message' => $message,
                    'is_read' => false,
                    'channel' => 'database',
                ]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Appointment created successfully',
                'appointment' => $appointment
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save appointment'
            ]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating appointment: ' . $e->getMessage()
        ]);
    }
})->name('test.create.appointment');
*/

// Test route to debug patient profile links
Route::get('/test-patient-link', function () {
    // Get a specific appointment to test with
    $appointment = \App\Models\Appointment::with(['patient.patient'])->first();
    
    if (!$appointment) {
        return response()->json(['error' => 'No appointments found']);
    }
    
    if (!$appointment->patient) {
        return response()->json(['error' => 'Patient relationship not loaded']);
    }
    
    // Check if patient->patient relationship exists
    if (!$appointment->patient->patient) {
        return response()->json([
            'error' => 'Patient->patient relationship not loaded',
            'patient_data' => $appointment->patient,
            'patient_patient_data' => $appointment->patient->patient,
            'patient_patient_exists' => isset($appointment->patient->patient),
            'patient_patient_type' => gettype($appointment->patient->patient)
        ]);
    }
    
    // Check if the patient model has an ID
    $patient = $appointment->patient->patient;
    if (!$patient->id) {
        return response()->json([
            'error' => 'Patient model has no ID',
            'patient_data' => $patient
        ]);
    }
    
    // Try to generate the route
    try {
        // Test different ways of generating the route
        $route1 = route('doctor.patient.show', $patient);
        $route2 = route('doctor.patient.show', $patient->id);
        $route3 = route('doctor.patient.show', ['patient' => $patient->id]);
        
        return response()->json([
            'success' => true,
            'routes' => [
                'route1 (model)' => $route1,
                'route2 (id)' => $route2,
                'route3 (array)' => $route3
            ],
            'patient_id' => $patient->id,
            'patient_name' => $appointment->patient->name,
            'patient_data' => [
                'id' => $patient->id,
                'user_id' => $patient->user_id,
                'class' => get_class($patient),
                'route_key_name' => $patient->getRouteKeyName()
            ],
            'appointment_patient_data' => [
                'id' => $appointment->patient->id,
                'name' => $appointment->patient->name
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Route generation failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
})->middleware('auth');
