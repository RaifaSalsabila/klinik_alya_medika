<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\MedicalRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function __construct()
    {
        // Middleware akan diatur di routes
    }

    public function index(Request $request)
    {
        $query = Invoice::with(['patient', 'medicalRecord']);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            if ($request->status !== 'all') {
                $query->where('status', $request->status);
            }
            // If status is 'all', don't filter by status
        } else {
            // Default: show unpaid invoices first
            $query->where('status', 'Belum Lunas');
        }
        
        $invoices = $query->orderBy('created_at', 'desc')->get();

        return view('admin.invoices', compact('invoices'));
    }

    public function create($medical_record_id)
    {
        $medical_record = MedicalRecord::with(['patient', 'doctor', 'invoice'])->findOrFail($medical_record_id);
        
        // Check if invoice already exists
        if ($medical_record->invoice) {
            return redirect()->route('admin.medical-records')
                ->with('error', 'Invoice untuk rekam medis ini sudah dibuat.');
        }
        
        return view('admin.create-invoice', compact('medical_record'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medical_record_id' => 'required|exists:medical_records,id',
            'consultation_fee' => 'required|numeric|min:0',
            'medication_fee' => 'required|numeric|min:0',
            'treatment_fee' => 'required|numeric|min:0',
            'other_fee' => 'nullable|numeric|min:0'
        ]);

        $medical_record = MedicalRecord::with('invoice')->findOrFail($request->medical_record_id);
        
        // Check if invoice already exists
        if ($medical_record->invoice) {
            return redirect()->route('admin.medical-records')
                ->with('error', 'Invoice untuk rekam medis ini sudah dibuat.');
        }

        $total_amount = $request->consultation_fee + $request->medication_fee + 
                       $request->treatment_fee + ($request->other_fee ?? 0);

        Invoice::create([
            'patient_id' => $medical_record->patient_id,
            'medical_record_id' => $request->medical_record_id,
            'consultation_fee' => $request->consultation_fee,
            'medication_fee' => $request->medication_fee,
            'treatment_fee' => $request->treatment_fee,
            'other_fee' => $request->other_fee ?? 0,
            'total_amount' => $total_amount,
            'status' => 'Belum Lunas'
        ]);

        return redirect()->route('admin.invoices')->with('success', 'Invoice berhasil dibuat.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['patient', 'medicalRecord.doctor'])
            ->findOrFail($id);
        
        return view('admin.invoice-detail', compact('invoice'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Belum Lunas,Lunas'
        ]);

        $invoice = Invoice::findOrFail($id);
        $invoice->update(['status' => $request->status]);

        return redirect()->route('admin.invoices')->with('success', 'Status invoice berhasil diperbarui.');
    }

    public function printInvoice($invoiceId)
    {
        $invoice = Invoice::with(['patient', 'medicalRecord.doctor'])
            ->findOrFail($invoiceId);
        
        $pdf = Pdf::loadView('admin.pdf.invoice', compact('invoice'));
        return $pdf->download('invoice-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
