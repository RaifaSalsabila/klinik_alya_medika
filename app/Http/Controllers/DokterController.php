<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Doctor;
use App\Models\User;

class DokterController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if (!$doctor) {
            return redirect()->route('dokter.logout')->with('error', 'Data dokter tidak ditemukan.');
        }

        $stats = [
            'total_appointments' => Appointment::where('doctor_id', $doctor->id)->count(),
            'pending_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'Menunggu')->count(),
            'accepted_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'Diterima')->count(),
            'completed_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->where('status', 'Selesai')->count(),
            'today_appointments' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())->count(),
            'today_pending' => Appointment::where('doctor_id', $doctor->id)
                ->whereDate('appointment_date', today())
                ->whereIn('status', ['Menunggu', 'Diterima'])->count(),
        ];

        // Appointments yang menunggu tindakan dokter (status Diterima atau Menunggu)
        $pending_appointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['Menunggu', 'Diterima'])
            ->orderBy('appointment_date', 'asc')
            ->orderBy('appointment_time', 'asc')
            ->limit(10)
            ->get();

        return view('dokter.dashboard', compact('stats', 'pending_appointments'));
    }

    public function appointments()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        $appointments = Appointment::with(['patient'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);

        return view('dokter.appointments', compact('appointments'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Selesai,Batal'
        ]);

        $appointment = Appointment::findOrFail($id);
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        // Pastikan appointment ini milik dokter yang login
        if ($appointment->doctor_id !== $doctor->id) {
            return redirect()->route('dokter.appointments')
                ->with('error', 'Akses ditolak.');
        }

        $appointment->update(['status' => $request->status]);

        return redirect()->route('dokter.appointments')
            ->with('success', 'Status janji berhasil diperbarui.');
    }

    public function medicalRecords()
    {
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        $medical_records = MedicalRecord::with(['patient', 'appointment'])
            ->where('doctor_id', $doctor->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dokter.medical-records', compact('medical_records'));
    }

    public function createMedicalRecord($appointment_id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointment_id);
        
        // Pastikan appointment ini milik dokter yang login
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if ($appointment->doctor_id !== $doctor->id) {
            return redirect()->route('dokter.appointments')
                ->with('error', 'Akses ditolak.');
        }

        return view('dokter.create-medical-record', compact('appointment'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login.show')->with('success', 'Anda berhasil logout.');
    }
}

