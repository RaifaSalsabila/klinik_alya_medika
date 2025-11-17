<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function dashboard()
    {
        $stats = [
            'total_patients' => User::where('role', 'pasien')->count(),
            'total_doctors' => Doctor::count(),
            'active_doctors' => Doctor::where('is_active', true)->count(),
            'total_appointments' => Appointment::count(),
            'pending_appointments' => Appointment::where('status', 'Menunggu')->count(),
            'completed_appointments' => Appointment::where('status', 'Selesai')->count(),
            'total_revenue' => Invoice::where('status', 'Lunas')->sum('total_amount'),
            'total_invoices' => Invoice::count(),
            'paid_invoices' => Invoice::where('status', 'Lunas')->count(),
            'unpaid_invoices' => Invoice::where('status', 'Belum Lunas')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'today_revenue' => Invoice::where('status', 'Lunas')->whereDate('created_at', today())->sum('total_amount')
        ];

        $recent_appointments = Appointment::with(['patient', 'doctor'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_appointments'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login.show');
    }
}
