<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ReferralLetter;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function index()
    {
        $medical_records = MedicalRecord::with(['patient', 'doctor', 'invoice'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.medical-records', compact('medical_records'));
    }

    public function create($appointment_id)
    {
        $appointment = Appointment::with(['patient', 'doctor'])->findOrFail($appointment_id);
        return view('admin.create-medical-record', compact('appointment'));
    }

    public function show($id)
    {
        $record = MedicalRecord::with(['patient', 'doctor', 'prescription.prescriptionItems', 'referralLetter'])
            ->findOrFail($id);
        
        return view('admin.medical-record-detail', compact('record'));
    }

    public function store(Request $request)
    {
        Log::info('=== STORE MEDICAL RECORD DEBUG ===');
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
                // Check if medicines array exists and is not empty
                if (!$request->medicines || !is_array($request->medicines)) {
                    return back()->withErrors([
                        'medicines' => 'Minimal isi satu obat dengan nama obat.'
                    ])->withInput();
                }
                
                // Filter out empty medicines
                $medicines = array_filter($request->medicines, function($medicine) {
                    return !empty($medicine['name']);
                });
                
                if (empty($medicines)) {
                    return back()->withErrors([
                        'medicines' => 'Minimal isi satu obat dengan nama obat.'
                    ])->withInput();
                }
                
                // Validate each non-empty medicine
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
            
            // Prepare prescription data for storage
            $prescriptionData = null;
            if ($request->service_type == 'rawat_jalan' && $request->medicines) {
                // Filter out empty medicines
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
                
                // Filter out empty medicines before creating prescription
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
                    Log::info('All prescription items created successfully');
                }
            }

            // Jika Rawat Inap, buat surat rujukan
            if ($request->service_type == 'rawat_inap') {
                Log::info('Creating referral letter for rawat_inap');
                $referral = ReferralLetter::create([
                    'medical_record_id' => $medical_record->id,
                    'patient_id' => $appointment->patient_id,
                    'referral_number' => 'REF' . str_pad($medical_record->id, 6, '0', STR_PAD_LEFT),
                    'referred_to' => $request->referral_to,
                    'reason' => $request->referral_reason
                ]);
                Log::info('Referral letter created:', ['id' => $referral->id]);
            }

            // Update appointment status
            Log::info('Updating appointment status to Selesai');
            $appointment->update(['status' => 'Selesai']);
            Log::info('Appointment status updated successfully');

            Log::info('=== MEDICAL RECORD STORED SUCCESSFULLY ===');
            return redirect()->route('admin.medical-records')->with('success', 'Rekam medis berhasil disimpan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing medical record:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan rekam medis: ' . $e->getMessage())->withInput();
        }
    }

    // PDF Generation Methods
    public function printPrescription($medicalRecordId)
    {
        $medicalRecord = MedicalRecord::with(['patient', 'doctor'])
            ->findOrFail($medicalRecordId);
        
        // Check if this is a rawat_jalan record
        if ($medicalRecord->service_type !== 'rawat_jalan') {
            return redirect()->route('admin.medical-records')
                ->with('error', 'Bukan rekam medis rawat jalan.');
        }
        
        // Decode prescription JSON data
        $prescriptionData = null;
        if ($medicalRecord->prescription_data) {
            $prescriptionData = json_decode($medicalRecord->prescription_data, true);
        }
        
        if (!$prescriptionData || !is_array($prescriptionData) || count($prescriptionData) == 0) {
            return redirect()->route('admin.medical-records')
                ->with('error', 'Tidak ada resep obat yang tersedia.');
        }
        
        // Generate prescription number
        $prescriptionNumber = 'RX' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT);
        
        return Pdf::loadView('admin.pdf.prescription', compact('medicalRecord', 'prescriptionData', 'prescriptionNumber'))
            ->download('resep-' . $prescriptionNumber . '.pdf');
    }

    public function printReferral($medicalRecordId)
    {
        $medicalRecord = MedicalRecord::with(['patient', 'doctor', 'referralLetter'])
            ->findOrFail($medicalRecordId);
        
        // Check if referral letter exists
        if (!$medicalRecord->referralLetter) {
            return redirect()->route('admin.medical-records')
                ->with('error', 'Surat rujukan tidak ditemukan.');
        }
        
        // Generate referral number
        $referralNumber = $medicalRecord->referralLetter->referral_number ?? 'REF' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT);
        
        return Pdf::loadView('admin.pdf.referral', compact('medicalRecord', 'referralNumber'))
            ->download('rujukan-' . $referralNumber . '.pdf');
    }
}
