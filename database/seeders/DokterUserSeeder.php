<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DokterUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $doctors = [
            [
                'name' => 'Dr. Ahmad Wijaya, Sp.PD',
                'email' => 'ahmad.wijaya@alyamedika.com',
                'phone' => '081234567890',
            ],
            [
                'name' => 'Dr. Siti Nurhaliza, Sp.OG',
                'email' => 'siti.nurhaliza@alyamedika.com',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Dr. Budi Santoso, Sp.A',
                'email' => 'budi.santoso@alyamedika.com',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Dr. Maria Magdalena, Sp.JP',
                'email' => 'maria.magdalena@alyamedika.com',
                'phone' => '081234567893',
            ]
        ];

        foreach ($doctors as $doctor) {
            $existingUser = User::where('email', $doctor['email'])->first();

            // Generate username from email (before @)
            $username = explode('@', $doctor['email'])[0];

            if (!$existingUser) {
                User::create([
                    'name' => $doctor['name'],
                    'username' => $username,
                    'email' => $doctor['email'],
                    'phone' => $doctor['phone'],
                    'nik' => '0000000000000000', // NIK default untuk dokter
                    'role' => 'dokter',
                    'address' => 'Klinik Alya Medika',
                    'password' => Hash::make('dokter123'),
                    'email_verified_at' => now()
                ]);
            } else {
                // Update password if user exists
                $existingUser->update([
                    'password' => Hash::make('dokter123')
                ]);
            }
        }
    }
}
