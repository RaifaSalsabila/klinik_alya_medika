<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor'])
            ->orderBy('appointment_date', 'desc');

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $appointments = $query->get();

        return view('admin.appointments', compact('appointments'));
    }

    public function show($id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])
            ->findOrFail($id);
        
        return view('admin.appointment-detail', compact('appointment'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diterima,Selesai,Batal'
        ]);

        $appointment = Appointment::findOrFail($id);
        $updateData = ['status' => $request->status];

        // Generate queue number when status changes to "Diterima"
        if ($request->status == 'Diterima' && $appointment->status != 'Diterima') {
            if (!$appointment->queue_number) {
                $updateData['queue_number'] = $this->generateQueueNumber(
                    $appointment->doctor_id, 
                    $appointment->appointment_date
                );
            }
        }

        $appointment->update($updateData);

        $message = 'Status appointment berhasil diperbarui.';
        if ($request->status == 'Diterima' && isset($updateData['queue_number'])) {
            $message .= ' Nomor antrian: ' . $updateData['queue_number'];
        }

        return redirect()->route('admin.appointments')->with('success', $message);
    }

    // Method untuk generate nomor antrian otomatis
    private function generateQueueNumber($doctorId, $appointmentDate)
    {
        $lastAppointment = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $appointmentDate)
            ->orderBy('queue_number', 'desc')
            ->first();
        
        return $lastAppointment ? $lastAppointment->queue_number + 1 : 1;
    }
}
