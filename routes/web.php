<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\DokterMedicalRecordController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;

// Halaman Utama
Route::get('/', [AuthController::class, 'home'])->name('home');

// Default login route (untuk kompatibilitas)
Route::get('/login', function () {
    return redirect()->route('patient.login');
})->name('login');

// Auth Routes
Route::prefix('auth')->group(function () {
    // Patient Login
    Route::get('/patient/login', [AuthController::class, 'showPatientLogin'])->name('patient.login.show');
    Route::post('/patient/login', [AuthController::class, 'patientLogin']);

    // Patient Register
    Route::get('/patient/register', [AuthController::class, 'showPatientRegister'])->name('patient.register.show');
    Route::post('/patient/register', [AuthController::class, 'patientRegister']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin & Dokter Login (Hidden route - tidak ada link di home)
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login.show');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.delete');
    
    // Doctor Management (Jadwal Dokter)
    Route::get('/doctors', [DoctorController::class, 'index'])->name('admin.doctors');
    Route::post('/doctors', [DoctorController::class, 'store'])->name('admin.doctors.store');
    Route::get('/doctors/{id}', [DoctorController::class, 'show'])->name('admin.doctors.show');
    Route::get('/doctors/{id}/edit', [DoctorController::class, 'edit'])->name('admin.doctors.edit');
    Route::put('/doctors/{id}', [DoctorController::class, 'update'])->name('admin.doctors.update');
    Route::delete('/doctors/{id}', [DoctorController::class, 'destroy'])->name('admin.doctors.delete');
    
    // Appointment Management
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('admin.appointments');
    Route::get('/appointments/{id}', [AppointmentController::class, 'show'])->name('admin.appointments.show');
    Route::put('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('admin.appointments.status');
    
    // Medical Records
    Route::get('/medical-records', [MedicalRecordController::class, 'index'])->name('admin.medical-records');
    Route::get('/medical-records/create/{appointment_id}', [MedicalRecordController::class, 'create'])->name('admin.create-medical-record');
    Route::post('/medical-records', [MedicalRecordController::class, 'store'])->name('admin.medical-records.store');
    Route::get('/medical-records/{id}', [MedicalRecordController::class, 'show'])->name('admin.medical-records.show');
    
    // Invoice Management
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('admin.invoices');
    Route::get('/invoices/create/{medical_record_id}', [InvoiceController::class, 'create'])->name('admin.create-invoice');
    Route::post('/invoices', [InvoiceController::class, 'store'])->name('admin.invoices.store');
    Route::get('/invoices/{id}', [InvoiceController::class, 'show'])->name('admin.invoices.show');
    Route::put('/invoices/{id}/status', [InvoiceController::class, 'updateStatus'])->name('admin.invoices.status');
    
    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('admin.reports');
    Route::post('/reports/generate', [ReportController::class, 'generate'])->name('admin.reports.generate');
    Route::get('/reports/download/{id}', [ReportController::class, 'download'])->name('admin.reports.download');
    
    // PDF Generation Routes
    Route::get('/medical-records/{id}/prescription', [MedicalRecordController::class, 'printPrescription'])->name('admin.print.prescription');
    Route::get('/medical-records/{id}/referral', [MedicalRecordController::class, 'printReferral'])->name('admin.print.referral');
    Route::get('/invoices/{id}/print', [InvoiceController::class, 'printInvoice'])->name('admin.print.invoice');
    
    // Logout
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
});

// Dokter Routes
Route::prefix('dokter')->middleware(['auth', 'dokter'])->group(function () {
    Route::get('/dashboard', [DokterController::class, 'dashboard'])->name('dokter.dashboard');
    
    // Appointment Management
    Route::get('/appointments', [DokterController::class, 'appointments'])->name('dokter.appointments');
    Route::put('/appointments/{id}/status', [DokterController::class, 'updateAppointmentStatus'])->name('dokter.appointments.status');
    
    // Medical Records
    Route::get('/medical-records', [DokterController::class, 'medicalRecords'])->name('dokter.medical-records');
    Route::get('/medical-records/{id}', [DokterMedicalRecordController::class, 'show'])->name('dokter.medical-records.show');
    Route::get('/medical-records/create/{appointment_id}', [DokterController::class, 'createMedicalRecord'])->name('dokter.create-medical-record');
    Route::post('/medical-records', [DokterMedicalRecordController::class, 'store'])->name('dokter.medical-records.store');
    
    // Logout
    Route::post('/logout', [DokterController::class, 'logout'])->name('dokter.logout');
});

// Patient Routes
Route::prefix('patient')->name('patient.')->group(function () {
    // Authentication
    Route::get('/login', [PatientController::class, 'showLogin'])->name('login');
    Route::post('/login', [PatientController::class, 'login']);
    Route::get('/register', [PatientController::class, 'showRegister'])->name('register');
    Route::post('/register', [PatientController::class, 'register']);
    Route::post('/logout', [PatientController::class, 'logout'])->name('logout');

    // Protected routes
    Route::middleware(['auth', 'patient'])->group(function () {
        Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');
        Route::get('/appointments', [PatientController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/create', [PatientController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments', [PatientController::class, 'storeAppointment'])->name('appointments.store');
        Route::get('/history', [PatientController::class, 'history'])->name('history');
        Route::get('/invoices', [PatientController::class, 'invoices'])->name('invoices');
        Route::get('/doctor-schedule', [PatientController::class, 'doctorSchedule'])->name('doctor-schedule');
        Route::get('/available-slots', [PatientController::class, 'getAvailableSlots'])->name('available-slots');
        Route::get('/profile', [PatientController::class, 'profile'])->name('profile');
        Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');
        Route::put('/change-password', [PatientController::class, 'changePassword'])->name('change-password');

        // Prescription routes
        Route::get('/prescription/{id}', [PatientController::class, 'viewPrescription'])->name('prescription.view');
        Route::get('/print/prescription/{id}', [PatientController::class, 'printPrescription'])->name('print.prescription');
        
        // Referral routes
        Route::get('/print/referral/{id}', [PatientController::class, 'printReferral'])->name('print.referral');
        
        // Invoice routes
        Route::get('/print/invoice/{id}', [PatientController::class, 'printInvoice'])->name('print.invoice');
    });
});
