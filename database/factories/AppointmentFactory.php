<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Doctor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => User::factory()->patient(),
            'doctor_id' => Doctor::factory(),
            'appointment_date' => fake()->dateTimeBetween('now', '+30 days'),
            'appointment_time' => fake()->time('H:i'),
            'queue_number' => fake()->numberBetween(1, 20),
            'status' => fake()->randomElement(['Menunggu', 'Diterima', 'Selesai', 'Batal']),
            'complaint' => fake()->randomElement([
                'Demam tinggi',
                'Sakit kepala',
                'Batuk dan pilek',
                'Nyeri perut',
                'Sakit gigi',
                'Pemeriksaan rutin',
                'Mual dan muntah',
                'Sesak napas',
                'Ruam kulit',
                'Sakit tenggorokan'
            ]),
        ];
    }

    /**
     * Create a pending appointment.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Menunggu',
        ]);
    }

    /**
     * Create a completed appointment.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Selesai',
        ]);
    }

    /**
     * Create an accepted appointment.
     */
    public function accepted(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Diterima',
        ]);
    }

    /**
     * Create a cancelled appointment.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Batal',
        ]);
    }
}
