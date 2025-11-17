<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Invoice;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function showLogin()
    {
        return view('patient.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function($query) use ($request) {
            $query->where('email', $request->login)
                  ->orWhere('phone', $request->login);
        })->where('role', 'pasien')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended(route('patient.dashboard'));
        }

        return back()->withErrors([
            'login' => 'Kata sandi atau email salah.',
        ])->withInput();
    }

    public function showRegister()
    {
        return view('patient.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'nik' => 'required|string|size:16|unique:users',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nik' => $request->nik,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'pasien',
            'email_verified_at' => now()
        ]);

        return redirect()->route('patient.login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Get user's appointments
        $appointments = Appointment::with(['doctor'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get user's medical records
        $medical_records = MedicalRecord::with(['doctor'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get user's invoices
        $invoices = Invoice::with(['medicalRecord'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get active doctors
        $doctors = Doctor::with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(function($doctor) {
                return $doctor->name;
            });

        return view('patient.dashboard', compact('user', 'appointments', 'medical_records', 'invoices', 'doctors'));
    }

    public function appointments(Request $request)
    {
        $user = Auth::user();
        
        $query = Appointment::with(['doctor'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(10);

        return view('patient.appointments', compact('appointments'));
    }

    public function createAppointment()
    {
        $doctors = Doctor::with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(function($doctor) {
                return $doctor->name;
            });

        return view('patient.create-appointment', compact('doctors'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required|string',
            'complaint' => 'required|string|max:1000',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        // Check if doctor is available on the selected date and time
        if (!$doctor->isAvailable($request->appointment_date, $request->appointment_time)) {
            return back()->withErrors([
                'appointment_time' => 'Dokter tidak tersedia pada waktu yang dipilih. Silakan pilih waktu lain.'
            ])->withInput();
        }

        // Generate queue number
        $queueNumber = $this->generateQueueNumber($request->doctor_id, $request->appointment_date);

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'complaint' => $request->complaint,
            'queue_number' => $queueNumber,
            'status' => 'Menunggu',
        ]);

        return redirect()->route('patient.appointments')->with('success', 'Appointment berhasil dibuat! Nomor antrian Anda: ' . $queueNumber);
    }

    public function history()
    {
        $user = Auth::user();
        
        $medical_records = MedicalRecord::with(['doctor', 'prescription', 'referralLetter', 'invoice'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.history', compact('medical_records'));
    }

    public function invoices()
    {
        $user = Auth::user();
        
        $invoices = Invoice::with(['medicalRecord'])
            ->where('patient_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('patient.invoices', compact('invoices'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('patient.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'address' => 'required|string|max:500',
        ]);

        User::where('id', $user->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('patient.login');
    }

    public function viewPrescription($id)
    {
        try {
            $user = Auth::user();
            $record = MedicalRecord::where('id', $id)
                ->where('patient_id', $user->id)
                ->with('invoice')
                ->first();

            if (!$record) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Rekam medis tidak ditemukan'
                ], 404);
            }

            // Check if invoice is paid
            if (!$record->invoice || $record->invoice->status !== 'Lunas') {
                return response()->json([
                    'success' => false,
                    'message' => 'Resep dapat dilihat setelah pembayaran lunas'
                ], 403);
            }

            // Debug logging
            Log::info('View Prescription Debug:', [
                'record_id' => $id,
                'user_id' => $user->id,
                'service_type' => $record->service_type,
                'has_prescription' => !empty($record->prescription_data),
                'prescription' => $record->prescription_data
            ]);

            if ($record->service_type !== 'rawat_jalan') {
                return response()->json([
                    'success' => false,
                    'message' => 'Bukan rawat jalan'
                ], 400);
            }

            if (!$record->prescription_data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resep obat tidak tersedia'
                ], 404);
            }

            $prescription = json_decode($record->prescription_data, true);
            
            // Check if prescription is valid JSON and has data
            if (!$prescription || !is_array($prescription) || empty($prescription)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Data resep obat tidak valid'
                ], 400);
            }
            
            return response()->json([
                'success' => true,
                'prescription' => $prescription
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('View Prescription Error:', [
                'error' => $e->getMessage(),
                'record_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    public function printPrescription($id)
    {
        $user = Auth::user();
        $record = MedicalRecord::where('id', $id)
            ->where('patient_id', $user->id)
            ->with(['patient', 'doctor', 'invoice'])
            ->first();

        if (!$record) {
            abort(404, 'Rekam medis tidak ditemukan');
        }

        // Check if invoice is paid
        if (!$record->invoice || $record->invoice->status !== 'Lunas') {
            abort(403, 'Resep dapat dilihat setelah pembayaran lunas');
        }

        if ($record->service_type !== 'rawat_jalan' || !$record->prescription_data) {
            abort(404, 'Resep obat tidak tersedia');
        }

        $medicalRecord = $record;
        $prescriptionData = json_decode($medicalRecord->prescription_data, true);
        $prescriptionNumber = 'RX' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT);
        
        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.prescription', compact('medicalRecord', 'prescriptionData', 'prescriptionNumber'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set filename
        $filename = 'Resep_Obat_' . $medicalRecord->patient->name . '_' . date('Y-m-d') . '.pdf';
        
        // Return PDF download
        return $pdf->download($filename);
    }

    public function printReferral($id)
    {
        $user = Auth::user();
        $medicalRecord = MedicalRecord::where('id', $id)
            ->where('patient_id', $user->id)
            ->with(['patient', 'doctor', 'referralLetter'])
            ->first();

        if (!$medicalRecord) {
            abort(404, 'Rekam medis tidak ditemukan');
        }

        if ($medicalRecord->service_type !== 'rawat_inap' || !$medicalRecord->referralLetter) {
            abort(404, 'Surat rujukan tidak tersedia');
        }

        $referralNumber = 'REF' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT);
        
        // Generate PDF using DomPDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.referral', compact('medicalRecord', 'referralNumber'));
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Set filename
        $filename = 'Surat_Rujukan_' . $medicalRecord->patient->name . '_' . date('Y-m-d') . '.pdf';
        
        // Return PDF download
        return $pdf->download($filename);
    }

    public function printInvoice($id)
    {
        $user = Auth::user();
        $invoice = Invoice::where('id', $id)
            ->where('patient_id', $user->id)
            ->with(['patient', 'medicalRecord.doctor'])
            ->first();

        if (!$invoice) {
            abort(404, 'Invoice tidak ditemukan');
        }
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.pdf.invoice', compact('invoice'));
        return $pdf->download('invoice-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    private function generateQueueNumber($doctorId, $appointmentDate)
    {
        $date = Carbon::parse($appointmentDate)->format('Y-m-d');
        
        $lastAppointment = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->orderBy('queue_number', 'desc')
            ->first();

        if ($lastAppointment) {
            return $lastAppointment->queue_number + 1;
        }

        return 1;
    }

    public function doctorSchedule()
    {
        $doctors = Doctor::with('user')
            ->where('is_active', true)
            ->get()
            ->sortBy(function($doctor) {
                return $doctor->name;
            });

        return view('patient.doctor-schedule', compact('doctors'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today'
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $availableSlots = $doctor->getAvailableSlots($request->date);

        return response()->json([
            'success' => true,
            'slots' => array_values($availableSlots)
        ]);
    }
}
