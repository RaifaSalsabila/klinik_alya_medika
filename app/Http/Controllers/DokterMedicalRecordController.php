<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ReferralLetter;
use Barryvdh\DomPDF\Facade\Pdf;

class DokterMedicalRecordController extends Controller
{
    public function show($id)
    {
        $record = MedicalRecord::with(['patient', 'doctor', 'prescription.prescriptionItems', 'referralLetter'])
            ->findOrFail($id);
        
        // Verify that the record belongs to the logged-in doctor
        $user = Auth::user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        
        if ($record->doctor_id !== $doctor->id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat rekam medis ini.');
        }
        
        return view('admin.medical-record-detail', compact('record'));
    }

    public function store(Request $request)
    {
        Log::info('=== STORE MEDICAL RECORD DEBUG (DOKTER) ===');
        Log::info('Request data:', $request->all());
        
        try {
            // Dynamic validation based on service type
            $validationRules = [
                'appointment_id' => 'required|exists:appointments,id',
                'complaint' => 'required|string',
                'diagnosis' => 'required|string',
                'treatment' => 'required|string',
                'service_type' => 'required|in:rawat_jalan,rawat_inap',
                'prescription' => 'nullable|string',
                'notes' => 'nullable|string',
            ];
            
            // Add medicines validation only for rawat_jalan
            if ($request->service_type === 'rawat_jalan') {
                if (!$request->medicines || !is_array($request->medicines)) {
                    return back()->withErrors([
                        'medicines' => 'Minimal isi satu obat dengan nama obat.'
                    ])->withInput();
                }
                
                $medicines = array_filter($request->medicines, function($medicine) {
                    return !empty($medicine['name']);
                });
                
                if (empty($medicines)) {
                    return back()->withErrors([
                        'medicines' => 'Minimal isi satu obat dengan nama obat.'
                    ])->withInput();
                }
                
                foreach ($medicines as $index => $medicine) {
                    $validationRules["medicines.{$index}.name"] = 'required|string';
                    $validationRules["medicines.{$index}.dosage"] = 'nullable|string';
                    $validationRules["medicines.{$index}.quantity"] = 'nullable|integer|min:1';
                    $validationRules["medicines.{$index}.instructions"] = 'nullable|string';
                }
            }
            
            // Add referral validation only for rawat_inap
            if ($request->service_type === 'rawat_inap') {
                $validationRules['referral_to'] = 'required|string';
                $validationRules['referral_reason'] = 'required|string';
            }
            
            $request->validate($validationRules);
            
            Log::info('Validation passed successfully');

            $appointment = Appointment::findOrFail($request->appointment_id);
            Log::info('Appointment found:', ['id' => $appointment->id, 'patient_id' => $appointment->patient_id, 'doctor_id' => $appointment->doctor_id]);
            
            // Pastikan appointment ini milik dokter yang login
            $user = Auth::user();
            $doctor = Doctor::where('user_id', $user->id)->first();
            
            if ($appointment->doctor_id !== $doctor->id) {
                return redirect()->route('dokter.appointments')
                    ->with('error', 'Akses ditolak.');
            }
            
            // Prepare prescription data for storage
            $prescriptionData = null;
            if ($request->service_type == 'rawat_jalan' && $request->medicines) {
                $validMedicines = array_filter($request->medicines, function($medicine) {
                    return !empty($medicine['name']);
                });
                $prescriptionData = json_encode(array_values($validMedicines));
            }

            $medical_record = MedicalRecord::create([
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'appointment_id' => $request->appointment_id,
                'complaint' => $request->complaint,
                'diagnosis' => $request->diagnosis,
                'treatment' => $request->treatment,
                'service_type' => $request->service_type,
                'prescription_data' => $prescriptionData,
                'notes' => $request->notes
            ]);
            
            Log::info('Medical record created:', ['id' => $medical_record->id, 'service_type' => $medical_record->service_type]);

            // Jika Rawat Jalan, buat resep obat
            if ($request->service_type == 'rawat_jalan' && $request->medicines) {
                Log::info('Creating prescription for rawat_jalan');
                
                $validMedicines = array_filter($request->medicines, function($medicine) {
                    return !empty($medicine['name']);
                });
                
                if (!empty($validMedicines)) {
                    $prescription = Prescription::create([
                        'medical_record_id' => $medical_record->id,
                        'patient_id' => $appointment->patient_id,
                        'prescription_number' => 'RX' . str_pad($medical_record->id, 6, '0', STR_PAD_LEFT)
                    ]);
                    Log::info('Prescription created:', ['id' => $prescription->id]);

                    foreach ($validMedicines as $index => $medicine) {
                        Log::info("Creating prescription item {$index}:", $medicine);
                        PrescriptionItem::create([
                            'prescription_id' => $prescription->id,
                            'medicine_name' => $medicine['name'] ?? '',
                            'dosage' => $medicine['dosage'] ?? '',
                            'quantity' => $medicine['quantity'] ?? 0,
                            'instructions' => $medicine['instructions'] ?? ''
                        ]);
                    }
                }
            }

            // Jika Rawat Inap, buat surat rujukan
            if ($request->service_type == 'rawat_inap') {
                Log::info('Creating referral letter for rawat_inap');
                ReferralLetter::create([
                    'medical_record_id' => $medical_record->id,
                    'patient_id' => $appointment->patient_id,
                    'referral_number' => 'REF' . str_pad($medical_record->id, 6, '0', STR_PAD_LEFT),
                    'referred_to' => $request->referral_to,
                    'reason' => $request->referral_reason
                ]);
            }

            // Update appointment status ke Selesai
            $appointment->update(['status' => 'Selesai']);

            Log::info('=== STORE MEDICAL RECORD SUCCESS ===');

            return redirect()->route('dokter.appointments')
                ->with('success', 'Rekam medis berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('Error creating medical record:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors([
                'error' => 'Terjadi kesalahan saat membuat rekam medis: ' . $e->getMessage()
            ])->withInput();
        }
    }
}

