@extends('admin.includes.header')

@section('title', 'Buat Invoice')

@section('content')
    <style>
        .invoice-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .info-card-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 20px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .info-card-custom h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }
        .info-grid-custom {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 8px 15px;
            font-size: 0.95rem;
        }
        .info-label-custom {
            font-weight: 600;
            opacity: 0.9;
        }
        .info-value-custom {
            font-weight: 500;
        }
        .fee-input-card {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            transition: all 0.3s ease;
        }
        .fee-input-card:hover {
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
        }
        .fee-input-card:focus-within {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .total-summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            position: sticky;
            top: 20px;
        }
        .total-amount-display {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 15px 0;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        .breakdown-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .breakdown-item:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 10px;
            padding-top: 15px;
        }
        .service-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .service-rawat_jalan {
            background: rgba(34, 197, 94, 0.2);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }
        .service-rawat_inap {
            background: rgba(239, 68, 68, 0.2);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>

    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-file-invoice-dollar me-2"></i>
                    Buat Invoice
                </h1>
                <p class="page-subtitle">Pembuatan invoice untuk rekam medis pasien</p>
            </div>
            <a href="{{ route('admin.medical-records') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="invoice-container">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <!-- Patient & Doctor Info -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="info-card-custom">
                    <h5>
                        <i class="fas fa-user me-2"></i>
                        Informasi Pasien
                    </h5>
                    <div class="info-grid-custom">
                        <div class="info-label-custom">Nama:</div>
                        <div class="info-value-custom">{{ $medical_record->patient->name }}</div>
                        
                        <div class="info-label-custom">NIK:</div>
                        <div class="info-value-custom">{{ $medical_record->patient->nik ?? '-' }}</div>
                        
                        <div class="info-label-custom">Telepon:</div>
                        <div class="info-value-custom">{{ $medical_record->patient->phone ?? '-' }}</div>
                        
                        <div class="info-label-custom">Alamat:</div>
                        <div class="info-value-custom">{{ Str::limit($medical_record->patient->address ?? '-', 40) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="info-card-custom">
                    <h5>
                        <i class="fas fa-user-md me-2"></i>
                        Informasi Dokter & Layanan
                    </h5>
                    <div class="info-grid-custom">
                        <div class="info-label-custom">Dokter:</div>
                        <div class="info-value-custom">{{ $medical_record->doctor->name }}</div>
                        
                        <div class="info-label-custom">Spesialisasi:</div>
                        <div class="info-value-custom">{{ $medical_record->doctor->specialty }}</div>
                        
                        <div class="info-label-custom">Jenis Layanan:</div>
                        <div class="info-value-custom">
                            @if($medical_record->service_type == 'rawat_jalan')
                                <span class="service-badge service-rawat_jalan">
                                    <i class="fas fa-walking me-1"></i>Rawat Jalan
                                </span>
                            @else
                                <span class="service-badge service-rawat_inap">
                                    <i class="fas fa-bed me-1"></i>Rawat Inap
                                </span>
                            @endif
                        </div>
                        
                        <div class="info-label-custom">Tanggal:</div>
                        <div class="info-value-custom">{{ \Carbon\Carbon::parse($medical_record->created_at)->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.invoices.store') }}" method="POST">
            @csrf
            <input type="hidden" name="medical_record_id" value="{{ $medical_record->id }}">
            
            <div class="row">
                <!-- Invoice Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                Detail Biaya Layanan
                            </h5>

                            <div class="row">
                                <!-- Biaya Konsultasi -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-doctor me-2 text-primary"></i>
                                        Biaya Konsultasi
                                    </label>
                                    <div class="fee-input-card p-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-coins"></i> Rp
                                            </span>
                                            <input type="number" class="form-control border-0" name="consultation_fee" 
                                                   id="consultation_fee" value="50000" min="0" onchange="calculateTotal()" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Biaya Obat -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-pills me-2 text-success"></i>
                                        Biaya Obat
                                    </label>
                                    <div class="fee-input-card p-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-success text-white">
                                                <i class="fas fa-coins"></i> Rp
                                            </span>
                                            <input type="number" class="form-control border-0" name="medication_fee" 
                                                   id="medication_fee" value="0" min="0" onchange="calculateTotal()">
                                        </div>
                                    </div>
                                </div>

                                <!-- Biaya Tindakan -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-hand-holding-medical me-2 text-info"></i>
                                        Biaya Tindakan
                                    </label>
                                    <div class="fee-input-card p-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-info text-white">
                                                <i class="fas fa-coins"></i> Rp
                                            </span>
                                            <input type="number" class="form-control border-0" name="treatment_fee" 
                                                   id="treatment_fee" value="0" min="0" onchange="calculateTotal()">
                                        </div>
                                    </div>
                                </div>

                                <!-- Biaya Lainnya -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-list me-2 text-warning"></i>
                                        Biaya Lainnya
                                    </label>
                                    <div class="fee-input-card p-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-warning text-white">
                                                <i class="fas fa-coins"></i> Rp
                                            </span>
                                            <input type="number" class="form-control border-0" name="other_fee" 
                                                   id="other_fee" value="0" min="0" onchange="calculateTotal()">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <a href="{{ route('admin.medical-records') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Batal
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Buat Invoice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Summary -->
                <div class="col-lg-4">
                    <div class="total-summary-card">
                        <h6 class="mb-3">
                            <i class="fas fa-calculator me-2"></i>
                            Ringkasan Total
                        </h6>
                        <div class="total-amount-display" id="totalAmount">
                            Rp 50.000
                        </div>
                        <small class="opacity-75">
                            <i class="fas fa-info-circle me-1"></i>
                            Total biaya yang akan dibayar pasien
                        </small>

                        <div class="mt-4">
                            <h6 class="mb-3">
                                <i class="fas fa-receipt me-2"></i>
                                Rincian Biaya
                            </h6>
                            <div class="breakdown-item">
                                <span><i class="fas fa-user-doctor me-2"></i>Konsultasi:</span>
                                <span id="consultationDisplay">Rp 50.000</span>
                            </div>
                            <div class="breakdown-item">
                                <span><i class="fas fa-pills me-2"></i>Obat:</span>
                                <span id="medicationDisplay">Rp 0</span>
                            </div>
                            <div class="breakdown-item">
                                <span><i class="fas fa-hand-holding-medical me-2"></i>Tindakan:</span>
                                <span id="treatmentDisplay">Rp 0</span>
                            </div>
                            <div class="breakdown-item">
                                <span><i class="fas fa-list me-2"></i>Lainnya:</span>
                                <span id="otherDisplay">Rp 0</span>
                            </div>
                            <div class="breakdown-item" style="border-top: 2px solid rgba(255,255,255,0.3);">
                                <span><i class="fas fa-total me-2"></i>Total:</span>
                                <span id="totalDisplay">Rp 50.000</span>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <div class="alert alert-light mb-0" style="background: rgba(255,255,255,0.1); border: none;">
                                <i class="fas fa-shield-alt me-2"></i>
                                <small>Transaksi aman dan terjamin</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    function calculateTotal() {
        const consultation = parseInt(document.getElementById('consultation_fee').value) || 0;
        const medication = parseInt(document.getElementById('medication_fee').value) || 0;
        const treatment = parseInt(document.getElementById('treatment_fee').value) || 0;
        const other = parseInt(document.getElementById('other_fee').value) || 0;
        
        const total = consultation + medication + treatment + other;
        
        // Update total amount
        document.getElementById('totalAmount').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('totalDisplay').textContent = 'Rp ' + total.toLocaleString('id-ID');
        
        // Update breakdown
        document.getElementById('consultationDisplay').textContent = 'Rp ' + consultation.toLocaleString('id-ID');
        document.getElementById('medicationDisplay').textContent = 'Rp ' + medication.toLocaleString('id-ID');
        document.getElementById('treatmentDisplay').textContent = 'Rp ' + treatment.toLocaleString('id-ID');
        document.getElementById('otherDisplay').textContent = 'Rp ' + other.toLocaleString('id-ID');
    }

    // Initialize calculation
    calculateTotal();
</script>
@endsection

