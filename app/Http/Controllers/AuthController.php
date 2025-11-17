<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Halaman landing/home
    public function home()
    {
        return view('auth.home');
    }

    // Halaman login admin
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    // Halaman login pasien
    public function showPatientLogin()
    {
        return view('auth.patient-login');
    }

    // Halaman daftar pasien
    public function showPatientRegister()
    {
        return view('auth.patient-register');
    }

    // Proses login admin dan dokter
    public function adminLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // Cari user berdasarkan username atau email, bisa admin atau dokter
        $user = User::where(function($query) use ($request) {
            $query->where('username', $request->username)
                  ->orWhere('email', $request->username);
        })->whereIn('role', ['admin', 'dokter'])->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            // Auto-redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dokter.dashboard');
            }
        }

        throw ValidationException::withMessages([
            'username' => 'Username atau password tidak valid.'
        ]);
    }

    // Proses login pasien
    public function patientLogin(Request $request)
    {
        $request->validate([
            'login' => 'required', // bisa email atau phone
            'password' => 'required'
        ]);

        $user = User::where(function($query) use ($request) {
            $query->where('email', $request->login)
                  ->orWhere('phone', $request->login);
        })->where('role', 'pasien')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('patient.dashboard');
        }

        throw ValidationException::withMessages([
            'login' => 'Kata sandi atau email salah.'
        ]);
    }

    // Proses daftar pasien
    public function patientRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'nik' => 'required|string|size:16|unique:users',
            'address' => 'required|string|max:500',
            'password' => 'required|string|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'nik' => $request->nik,
            'address' => $request->address,
            'role' => 'pasien',
            'password' => Hash::make($request->password),
            'email_verified_at' => now()
        ]);

        return redirect()->route('patient.login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    // Logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
