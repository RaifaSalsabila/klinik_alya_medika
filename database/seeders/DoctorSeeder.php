<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctorData = [
            [
                'user_email' => 'ahmad.wijaya@alyamedika.com',
                'specialty' => 'Penyakit Dalam',
                'schedule' => [
                    'Senin' => ['start' => '08:00', 'end' => '12:00'],
                    'Rabu' => ['start' => '08:00', 'end' => '12:00'],
                    'Jumat' => ['start' => '08:00', 'end' => '12:00']
                ],
                'is_active' => true
            ],
            [
                'user_email' => 'siti.nurhaliza@alyamedika.com',
                'specialty' => 'Kebidanan dan Kandungan',
                'schedule' => [
                    'Selasa' => ['start' => '09:00', 'end' => '13:00'],
                    'Kamis' => ['start' => '09:00', 'end' => '13:00'],
                    'Sabtu' => ['start' => '09:00', 'end' => '13:00']
                ],
                'is_active' => true
            ],
            [
                'user_email' => 'budi.santoso@alyamedika.com',
                'specialty' => 'Anak',
                'schedule' => [
                    'Senin' => ['start' => '13:00', 'end' => '17:00'],
                    'Rabu' => ['start' => '13:00', 'end' => '17:00'],
                    'Jumat' => ['start' => '13:00', 'end' => '17:00']
                ],
                'is_active' => true
            ],
            [
                'user_email' => 'maria.magdalena@alyamedika.com',
                'specialty' => 'Jantung dan Pembuluh Darah',
                'schedule' => [
                    'Selasa' => ['start' => '14:00', 'end' => '18:00'],
                    'Kamis' => ['start' => '14:00', 'end' => '18:00'],
                    'Sabtu' => ['start' => '14:00', 'end' => '18:00']
                ],
                'is_active' => true
            ]
        ];

        foreach ($doctorData as $data) {
            $user = User::where('email', $data['user_email'])->first();
            if ($user) {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialty' => $data['specialty'],
                    'schedule' => $data['schedule'],
                    'is_active' => $data['is_active']
                ]);
            }
        }
    }
}
