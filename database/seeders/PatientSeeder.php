<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\ReferralLetter;
use App\Models\Invoice;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample patients
        $patients = [
            [
                'name' => 'Ahmad Wijaya',
                'email' => 'ahmad.wijaya@email.com',
                'phone' => '081234567890',
                'nik' => '3171234567890123',
                'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
                'role' => 'pasien',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@email.com',
                'phone' => '081234567891',
                'nik' => '3171234567890124',
                'address' => 'Jl. Sudirman No. 456, Jakarta Selatan',
                'role' => 'pasien',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'phone' => '081234567892',
                'nik' => '3171234567890125',
                'address' => 'Jl. Thamrin No. 789, Jakarta Pusat',
                'role' => 'pasien',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Dewi Kartika',
                'email' => 'dewi.kartika@email.com',
                'phone' => '081234567893',
                'nik' => '3171234567890126',
                'address' => 'Jl. Gatot Subroto No. 321, Jakarta Selatan',
                'role' => 'pasien',
                'password' => Hash::make('password123')
            ],
            [
                'name' => 'Rizki Pratama',
                'email' => 'rizki.pratama@email.com',
                'phone' => '081234567894',
                'nik' => '3171234567890127',
                'address' => 'Jl. Kebon Jeruk No. 654, Jakarta Barat',
                'role' => 'pasien',
                'password' => Hash::make('password123')
            ]
        ];

        foreach ($patients as $patientData) {
            $patient = User::create($patientData);

            // Create appointments for each patient
            $this->createAppointments($patient);
        }
    }

    private function createAppointments($patient)
    {
        $doctors = \App\Models\Doctor::all();

        if ($doctors->count() > 0) {
            // Create 2-3 appointments per patient
            $appointmentCount = rand(2, 3);

            for ($i = 0; $i < $appointmentCount; $i++) {
                $doctor = $doctors->random();
                $appointmentDate = now()->subDays(rand(1, 30));

                $appointment = Appointment::create([
                    'patient_id' => $patient->id,
                    'doctor_id' => $doctor->id,
                    'appointment_date' => $appointmentDate,
                    'appointment_time' => $appointmentDate->copy()->setTime(rand(8, 16), rand(0, 1) * 30),
                    'queue_number' => rand(1, 10),
                    'status' => ['Menunggu', 'Diterima', 'Selesai', 'Batal'][rand(0, 3)],
                    'complaint' => $this->getRandomComplaint()
                ]);

                // Create medical record for completed appointments
                if ($appointment->status === 'Selesai') {
                    $this->createMedicalRecord($appointment);
                }
            }
        }
    }

    private function createMedicalRecord($appointment)
    {
        $serviceType = ['rawat_jalan', 'rawat_inap'][rand(0, 1)];

        $medicalRecord = MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'complaint' => $appointment->complaint,
            'diagnosis' => $this->getRandomDiagnosis(),
            'treatment' => $this->getRandomTreatment(),
            'service_type' => $serviceType,
            'prescription_data' => $serviceType === 'rawat_jalan' ? $this->getRandomPrescription() : null,
            'notes' => 'Pasien dalam kondisi stabil dan dapat melanjutkan aktivitas normal.'
        ]);

        // Create prescription for Rawat Jalan
        if ($serviceType === 'rawat_jalan') {
            $prescription = Prescription::create([
                'medical_record_id' => $medicalRecord->id,
                'patient_id' => $appointment->patient_id,
                'prescription_number' => 'RX' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT)
            ]);

            // Add prescription items
            $medicines = $this->getRandomMedicines();
            foreach ($medicines as $medicine) {
                PrescriptionItem::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $medicine['name'],
                    'dosage' => $medicine['dosage'],
                    'quantity' => $medicine['quantity'],
                    'instructions' => $medicine['instructions']
                ]);
            }
        }

        // Create referral letter for Rawat Inap
        if ($serviceType === 'rawat_inap') {
            ReferralLetter::create([
                'medical_record_id' => $medicalRecord->id,
                'patient_id' => $appointment->patient_id,
                'referral_number' => 'REF' . str_pad($medicalRecord->id, 6, '0', STR_PAD_LEFT),
                'referred_to' => $this->getRandomHospital(),
                'reason' => $this->getRandomReferralReason()
            ]);
        }

        // Create invoice
        $this->createInvoice($medicalRecord);
    }

    private function createInvoice($medicalRecord)
    {
        $consultationFee = 50000;
        $medicationFee = rand(0, 200000);
        $treatmentFee = rand(0, 300000);
        $otherFee = rand(0, 100000);
        $totalAmount = $consultationFee + $medicationFee + $treatmentFee + $otherFee;

        Invoice::create([
            'patient_id' => $medicalRecord->patient_id,
            'medical_record_id' => $medicalRecord->id,
            'consultation_fee' => $consultationFee,
            'medication_fee' => $medicationFee,
            'treatment_fee' => $treatmentFee,
            'other_fee' => $otherFee,
            'total_amount' => $totalAmount,
            'status' => ['Belum Lunas', 'Lunas'][rand(0, 1)]
        ]);
    }

    private function getRandomComplaint()
    {
        $complaints = [
            'Demam tinggi selama 3 hari',
            'Sakit kepala dan pusing',
            'Batuk kering dan sesak napas',
            'Nyeri perut bagian bawah',
            'Sakit tenggorokan dan suara serak',
            'Mual dan muntah',
            'Nyeri sendi dan otot',
            'Gangguan tidur dan kelelahan',
            'Ruam kulit dan gatal-gatal',
            'Sakit gigi dan gusi bengkak'
        ];

        return $complaints[array_rand($complaints)];
    }

    private function getRandomDiagnosis()
    {
        $diagnoses = [
            'Infeksi saluran pernapasan atas',
            'Gastritis akut',
            'Hipertensi ringan',
            'Diabetes mellitus tipe 2',
            'Anemia defisiensi besi',
            'Migrain',
            'Dermatitis kontak',
            'Gingivitis',
            'Bronkitis akut',
            'Gangguan kecemasan'
        ];

        return $diagnoses[array_rand($diagnoses)];
    }

    private function getRandomTreatment()
    {
        $treatments = [
            'Pemberian antibiotik dan istirahat total',
            'Terapi fisik dan obat anti-inflamasi',
            'Kontrol tekanan darah dan diet rendah garam',
            'Manajemen gula darah dan olahraga teratur',
            'Suplemen zat besi dan vitamin C',
            'Obat pereda nyeri dan relaksasi',
            'Krim topikal dan antihistamin',
            'Pembersihan gigi dan obat kumur',
            'Inhalasi dan ekspektoran',
            'Konseling dan terapi relaksasi'
        ];

        return $treatments[array_rand($treatments)];
    }

    private function getRandomPrescription()
    {
        $prescriptions = [
            'Paracetamol 500mg 3x1 sehari setelah makan',
            'Amoxicillin 500mg 3x1 sehari selama 7 hari',
            'Ibuprofen 400mg 3x1 sehari jika nyeri',
            'Omeprazole 20mg 1x1 sehari sebelum makan',
            'Metformin 500mg 2x1 sehari setelah makan'
        ];

        return $prescriptions[array_rand($prescriptions)];
    }

    private function getRandomMedicines()
    {
        $medicines = [
            [
                'name' => 'Paracetamol 500mg',
                'dosage' => '500mg',
                'quantity' => rand(10, 30),
                'instructions' => '3x1 sehari setelah makan'
            ],
            [
                'name' => 'Amoxicillin 500mg',
                'dosage' => '500mg',
                'quantity' => rand(20, 40),
                'instructions' => '3x1 sehari selama 7 hari'
            ],
            [
                'name' => 'Ibuprofen 400mg',
                'dosage' => '400mg',
                'quantity' => rand(10, 20),
                'instructions' => '3x1 sehari jika nyeri'
            ],
            [
                'name' => 'Omeprazole 20mg',
                'dosage' => '20mg',
                'quantity' => rand(14, 28),
                'instructions' => '1x1 sehari sebelum makan'
            ],
            [
                'name' => 'Metformin 500mg',
                'dosage' => '500mg',
                'quantity' => rand(30, 60),
                'instructions' => '2x1 sehari setelah makan'
            ]
        ];

        return array_slice($medicines, 0, rand(1, 3));
    }

    private function getRandomHospital()
    {
        $hospitals = [
            'RSUD Cipto Mangunkusumo',
            'RS Siloam Jakarta',
            'RS Pondok Indah',
            'RS Medistra',
            'RS Jakarta',
            'RS Omni Pulomas',
            'RS Mayapada Jakarta',
            'RS Hermina Kemayoran'
        ];

        return $hospitals[array_rand($hospitals)];
    }

    private function getRandomReferralReason()
    {
        $reasons = [
            'Memerlukan perawatan intensif dan monitoring 24 jam',
            'Kondisi pasien memerlukan tindakan operasi',
            'Perlu pemeriksaan laboratorium dan radiologi lanjutan',
            'Memerlukan konsultasi dengan spesialis lain',
            'Kondisi kritis yang memerlukan perawatan khusus'
        ];

        return $reasons[array_rand($reasons)];
    }
}
