<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Prescription;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PrescriptionItem>
 */
class PrescriptionItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'prescription_id' => Prescription::factory(),
            'medicine_name' => fake()->randomElement([
                'Paracetamol 500mg',
                'Amoxicillin 500mg',
                'Ibuprofen 400mg',
                'Omeprazole 20mg',
                'Cetirizine 10mg',
                'Dexamethasone 0.5mg',
                'Metformin 500mg',
                'Amlodipine 5mg',
                'Simvastatin 10mg',
                'Furosemide 40mg'
            ]),
            'dosage' => fake()->randomElement(['500mg', '400mg', '20mg', '10mg', '0.5mg', '5mg', '40mg']),
            'quantity' => fake()->numberBetween(5, 30),
            'instructions' => fake()->randomElement([
                '3x1 sehari setelah makan',
                '2x1 sehari sebelum makan',
                '1x1 sehari saat tidur',
                '4x1 sehari',
                '2x1 sehari',
                '1x1 sehari'
            ]),
        ];
    }
}
