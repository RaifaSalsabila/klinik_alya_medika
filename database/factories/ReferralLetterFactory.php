<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalRecord;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ReferralLetter>
 */
class ReferralLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicalRecord = MedicalRecord::factory()->create();

        return [
            'medical_record_id' => $medicalRecord->id,
            'patient_id' => $medicalRecord->patient_id,
            'referral_number' => 'REF' . fake()->unique()->numberBetween(100000, 999999),
            'referred_to' => fake()->randomElement([
                'RS Cipto Mangunkusumo',
                'RS Siloam Jakarta',
                'RS Pondok Indah',
                'RS Medistra',
                'RS Premier Bintaro',
                'RS Jakarta Medical Center',
                'RS Fatmawati',
                'RS Harapan Kita'
            ]),
            'reason' => fake()->randomElement([
                'Memerlukan perawatan intensif',
                'Perlu konsultasi spesialis',
                'Memerlukan tindakan operasi',
                'Pemeriksaan lanjutan diperlukan',
                'Memerlukan fasilitas yang lebih lengkap',
                'Kondisi pasien memerlukan perawatan khusus',
                'Perlu monitoring 24 jam',
                'Memerlukan peralatan medis khusus'
            ]),
        ];
    }
}
