<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\ReportHistory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function index()
    {
        $recentReports = ReportHistory::orderBy('created_at', 'desc')->limit(10)->get();
        return view('admin.reports', compact('recentReports'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:doctors,appointments,revenue',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:pdf',
            'filter' => 'nullable|in:all,active,completed'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $filter = $request->filter ?? 'all';
        $reportType = $request->report_type;
        $format = $request->format;

        $data = null;
        $recordCount = 0;
        $fileName = '';

        switch ($reportType) {
            case 'doctors':
                $doctors = Doctor::withCount(['appointments' => function($query) use ($startDate, $endDate) {
                    $query->whereBetween('appointment_date', [$startDate, $endDate]);
                }])->get();

                // Apply filter if needed
                if ($filter === 'active') {
                    $doctors = $doctors->where('is_active', true);
                }

                $data = $doctors;
                $recordCount = $doctors->count();
                $fileName = 'laporan-dokter-' . $startDate . '-to-' . $endDate . '.pdf';
                $pdf = Pdf::loadView('admin.pdf.report-doctors', compact('doctors', 'startDate', 'endDate', 'filter'));
                break;

            case 'appointments':
                $appointments = Appointment::with(['patient', 'doctor'])
                    ->whereBetween('appointment_date', [$startDate, $endDate])
                    ->get();

                // Apply filter if needed
                if ($filter === 'completed') {
                    $appointments = $appointments->where('status', 'Selesai');
                } elseif ($filter === 'active') {
                    $appointments = $appointments->whereIn('status', ['Menunggu', 'Diterima']);
                }

                $data = $appointments;
                $recordCount = $appointments->count();
                $fileName = 'laporan-appointment-' . $startDate . '-to-' . $endDate . '.pdf';
                $pdf = Pdf::loadView('admin.pdf.report-appointments', compact('appointments', 'startDate', 'endDate', 'filter'));
                break;

            case 'revenue':
                $invoices = Invoice::with(['patient', 'medicalRecord.doctor'])
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();

                // Apply filter if needed
                if ($filter === 'completed') {
                    $invoices = $invoices->where('status', 'Lunas');
                } elseif ($filter === 'active') {
                    $invoices = $invoices->where('status', 'Belum Lunas');
                }

                $data = $invoices;
                $recordCount = $invoices->count();
                $fileName = 'laporan-pendapatan-' . $startDate . '-to-' . $endDate . '.pdf';
                $pdf = Pdf::loadView('admin.pdf.report-revenue', compact('invoices', 'startDate', 'endDate', 'filter'));
                break;
        }

        // Save the PDF file to storage
        $filePath = 'reports/' . $fileName;
        $pdf->save(storage_path('app/public/' . $filePath));

        // Prevent duplicate report creation using database transaction with lock
        // This ensures only one report history is saved even if request comes twice
        DB::transaction(function () use ($reportType, $startDate, $endDate, $format, $filter, $fileName, $recordCount, $filePath) {
            // Check if same report was created in last 2 seconds (with lock to prevent race condition)
            $recentReport = ReportHistory::where('report_type', $reportType)
                ->where('start_date', $startDate)
                ->where('end_date', $endDate)
                ->where('filter', $filter)
                ->where('created_at', '>=', Carbon::now()->subSeconds(2))
                ->lockForUpdate()
                ->first();

            // Only create history if no recent duplicate exists
            if (!$recentReport) {
                ReportHistory::create([
                    'report_type' => $reportType,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'format' => $format,
                    'filter' => $filter,
                    'file_name' => $fileName,
                    'file_path' => $filePath,
                    'record_count' => $recordCount
                ]);
            }
        });

        return $pdf->download($fileName);
    }

    public function download($id)
    {
        $report = ReportHistory::findOrFail($id);

        $filePath = storage_path('app/public/' . $report->file_path);

        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan'], 404);
        }

        return response()->download($filePath, $report->file_name);
    }
}
