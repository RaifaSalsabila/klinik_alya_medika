<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\MedicalRecord;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceType = fake()->randomElement(['rawat_jalan', 'rawat_inap']);

        $prescription = null;
        if ($serviceType === 'rawat_jalan') {
            $prescription = json_encode([
                [
                    'name' => fake()->randomElement(['Paracetamol 500mg', 'Amoxicillin 500mg', 'Ibuprofen 400mg', 'Omeprazole 20mg']),
                    'dosage' => fake()->randomElement(['500mg', '400mg', '20mg']),
                    'quantity' => fake()->numberBetween(5, 30),
                    'instructions' => fake()->randomElement(['3x1 sehari setelah makan', '2x1 sehari sebelum makan', '1x1 sehari saat tidur'])
                ]
            ]);
        }

        return [
            'appointment_id' => null,
            'patient_id' => null,
            'doctor_id' => null,
            'complaint' => null,
            'diagnosis' => fake()->randomElement([
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
            ]),
            'treatment' => fake()->randomElement([
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
            ]),
            'service_type' => $serviceType,
            'prescription_data' => $prescription,
            'notes' => fake()->optional(0.7)->sentence(), // 70% chance of having notes
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (MedicalRecord $medicalRecord) {
            if (!$medicalRecord->appointment_id) {
                $appointment = Appointment::factory()->create();
                $medicalRecord->appointment_id = $appointment->id;
                $medicalRecord->patient_id = $appointment->patient_id;
                $medicalRecord->doctor_id = $appointment->doctor_id;
                $medicalRecord->complaint = $appointment->complaint;
            } else {
                $appointment = Appointment::find($medicalRecord->appointment_id);
                $medicalRecord->patient_id = $appointment->patient_id;
                $medicalRecord->doctor_id = $appointment->doctor_id;
                $medicalRecord->complaint = $appointment->complaint;
            }
        });
    }

    /**
     * Create an outpatient medical record.
     */
    public function outpatient(): static
    {
        return $this->state(function (array $attributes) {
            $appointment = Appointment::factory()->create();
            return [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'service_type' => 'rawat_jalan',
                'prescription_data' => json_encode([
                    [
                        'name' => 'Paracetamol 500mg',
                        'dosage' => '500mg',
                        'quantity' => 10,
                        'instructions' => '3x1 sehari setelah makan'
                    ]
                ]),
            ];
        });
    }

    /**
     * Create an inpatient medical record.
     */
    public function inpatient(): static
    {
        return $this->state(function (array $attributes) {
            $appointment = Appointment::factory()->create();
            return [
                'appointment_id' => $appointment->id,
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'service_type' => 'rawat_inap',
                'prescription_data' => null,
            ];
        });
    }
}
