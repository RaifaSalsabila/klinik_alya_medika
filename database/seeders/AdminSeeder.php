<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Pertama
        User::create([
            'name' => 'Administrator',
            'username' => 'admin2',
            'email' => 'admin@alyamedika.com',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'role' => 'admin',
            'address' => 'Jl. Klinik Alya Medika No. 123, Jakarta',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);

        // Admin Kedua
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'phone' => '081234567891',
            'nik' => '1234567890123457',
            'role' => 'admin',
            'address' => 'Jl. Klinik Alya Medika No. 123, Jakarta',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now()
        ]);
    }
}
