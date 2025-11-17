<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $selectedDays = fake()->randomElements($days, fake()->numberBetween(3, 6));
        $startTime = fake()->time('H:i');
        $endTime = date('H:i', strtotime($startTime) + fake()->numberBetween(4, 8) * 3600);

        // Create schedule as JSON array
        $schedule = [];
        foreach ($selectedDays as $day) {
            $schedule[$day] = [
                'start' => $startTime,
                'end' => $endTime
            ];
        }

        return [
            'user_id' => \App\Models\User::factory()->create(['role' => 'dokter'])->id,
            'specialty' => fake()->randomElement([
                'Umum', 'Penyakit Dalam', 'Anak', 'Kandungan', 'Jantung', 'THT', 'Mata', 'Kulit'
            ]),
            'schedule' => $schedule,
            'is_active' => fake()->boolean(80), // 80% chance of being active
        ];
    }

    /**
     * Create an active doctor.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Create an inactive doctor.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
