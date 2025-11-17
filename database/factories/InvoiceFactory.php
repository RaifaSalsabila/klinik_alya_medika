<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalRecord;
use App\Models\Invoice;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $consultationFee = fake()->numberBetween(50000, 150000);
        $medicationFee = fake()->numberBetween(0, 100000);
        $treatmentFee = fake()->numberBetween(0, 200000);
        $otherFee = fake()->numberBetween(0, 50000);

        $totalAmount = $consultationFee + $medicationFee + $treatmentFee + $otherFee;

        return [
            'patient_id' => null,
            'medical_record_id' => null,
            'consultation_fee' => $consultationFee,
            'medication_fee' => $medicationFee,
            'treatment_fee' => $treatmentFee,
            'other_fee' => $otherFee,
            'total_amount' => $totalAmount,
            'status' => fake()->randomElement(['Belum Lunas', 'Lunas']),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Invoice $invoice) {
            if (!$invoice->medical_record_id) {
                $medicalRecord = MedicalRecord::factory()->create();
                $invoice->medical_record_id = $medicalRecord->id;
                $invoice->patient_id = $medicalRecord->patient_id;
            } else {
                $medicalRecord = MedicalRecord::find($invoice->medical_record_id);
                $invoice->patient_id = $medicalRecord->patient_id;
            }
        });
    }

    /**
     * Create a paid invoice.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Lunas',
        ]);
    }

    /**
     * Create an unpaid invoice.
     */
    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Belum Lunas',
        ]);
    }

    /**
     * Create an invoice with specific total amount.
     */
    public function withTotal(int $total): static
    {
        return $this->state(function (array $attributes) use ($total) {
            $consultationFee = fake()->numberBetween(50000, min(150000, $total));
            $remaining = $total - $consultationFee;
            $medicationFee = fake()->numberBetween(0, min(100000, $remaining));
            $remaining -= $medicationFee;
            $treatmentFee = fake()->numberBetween(0, min(200000, $remaining));
            $otherFee = $remaining - $treatmentFee;

            return [
                'consultation_fee' => $consultationFee,
                'medication_fee' => $medicationFee,
                'treatment_fee' => $treatmentFee,
                'other_fee' => $otherFee,
                'total_amount' => $total,
            ];
        });
    }
}
