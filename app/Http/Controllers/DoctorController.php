<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function index()
    {
        $doctors = Doctor::with('user')->get();
        
        // Ambil user dokter yang belum punya jadwal
        $availableUsers = User::where('role', 'dokter')
            ->whereDoesntHave('doctorSchedule')  // User dengan relasi doctor schedule
            ->get();
        
        return view('admin.doctors', compact('doctors', 'availableUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id|unique:doctors,user_id',
            'specialty' => 'required|string|max:255',
            'days' => 'required|array|min:1',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Format jadwal sebagai JSON array
        $schedule = [];
        foreach ($request->days as $day) {
            $schedule[$day] = [
                'start' => $request->start_time,
                'end' => $request->end_time
            ];
        }

        Doctor::create([
            'user_id' => $request->user_id,
            'specialty' => $request->specialty,
            'schedule' => $schedule,
            'is_active' => true
        ]);

        return redirect()->route('admin.doctors')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function show($id)
    {
        $doctor = Doctor::findOrFail($id);
        return view('admin.doctor-detail', compact('doctor'));
    }

    public function edit($id)
    {
        $doctor = Doctor::findOrFail($id);

        return response()->json($doctor->toArray());
    }

    public function update(Request $request, $id)
    {
        // Cari dokter
        $doctor = Doctor::findOrFail($id);

        $request->validate([
            'specialty' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'days' => 'required|array|min:1',
            'is_active' => 'required'
        ]);

        // Format jadwal sebagai JSON array
        $schedule = [];
        foreach ($request->days as $day) {
            $schedule[$day] = [
                'start' => $request->start_time,
                'end' => $request->end_time
            ];
        }


        // Update data dokter
        $doctor->update([
            'specialty' => $request->specialty,
            'schedule' => $schedule,
            'is_active' => $request->is_active == '1' || $request->is_active == 1
        ]);

        // Update user data if provided
        $userData = [];
        if ($request->filled('name')) {
            $userData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $userData['email'] = $request->email;
        }
        if ($request->filled('phone')) {
            $userData['phone'] = $request->phone;
        }
        if (!empty($userData)) {
            $doctor->user->update($userData);
        }

        return redirect()->route('admin.doctors')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();

        return redirect()->route('admin.doctors')->with('success', 'Data dokter berhasil dihapus.');
    }
}
