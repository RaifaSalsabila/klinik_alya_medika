<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin',
            'phone' => '081234567890',
            'nik' => '1234567890123456',
            'address' => 'Jl. Admin No. 1',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
    }
}
