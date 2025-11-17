<div class="invoice-detail-container">
    <style>
        .invoice-detail-container {
            padding: 20px;
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .invoice-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .invoice-date {
            opacity: 0.9;
        }
        .info-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .info-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
        }
        .info-label {
            font-weight: 600;
            color: #64748b;
        }
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        .fee-breakdown {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 2px solid #e2e8f0;
        }
        .fee-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .fee-item:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 10px;
            padding-top: 15px;
            border-top: 2px solid #667eea;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-belum-lunas {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-lunas {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
    </style>

    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="invoice-number">
            <i class="fas fa-file-invoice me-2"></i>
            Invoice #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
        </div>
        <div class="invoice-date">
            <i class="fas fa-calendar me-2"></i>
            {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y H:i') }}
        </div>
        <div class="mt-3">
            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $invoice->status)) }}">
                <i class="fas fa-{{ $invoice->status == 'Lunas' ? 'check-circle' : 'clock' }} me-2"></i>
                {{ $invoice->status }}
            </span>
        </div>
    </div>

    <div class="row">
        <!-- Patient Info -->
        <div class="col-md-6 mb-3">
            <div class="info-section">
                <h6 class="info-title">
                    <i class="fas fa-user me-2"></i>
                    Informasi Pasien
                </h6>
                <div class="info-grid">
                    <div class="info-label">Nama:</div>
                    <div class="info-value">{{ $invoice->patient->name }}</div>
                    
                    <div class="info-label">NIK:</div>
                    <div class="info-value">{{ $invoice->patient->nik ?? '-' }}</div>
                    
                    <div class="info-label">Telepon:</div>
                    <div class="info-value">{{ $invoice->patient->phone ?? '-' }}</div>
                    
                    <div class="info-label">Alamat:</div>
                    <div class="info-value">{{ $invoice->patient->address ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Doctor Info -->
        <div class="col-md-6 mb-3">
            <div class="info-section">
                <h6 class="info-title">
                    <i class="fas fa-user-md me-2"></i>
                    Informasi Dokter
                </h6>
                <div class="info-grid">
                    <div class="info-label">Dokter:</div>
                    <div class="info-value">{{ $invoice->medicalRecord->doctor->name }}</div>
                    
                    <div class="info-label">Spesialisasi:</div>
                    <div class="info-value">{{ $invoice->medicalRecord->doctor->specialty }}</div>
                    
                    <div class="info-label">Jenis Layanan:</div>
                    <div class="info-value">
                        @if($invoice->medicalRecord->service_type == 'rawat_jalan')
                            <span class="badge bg-success">
                                <i class="fas fa-walking me-1"></i>Rawat Jalan
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-bed me-1"></i>Rawat Inap
                            </span>
                        @endif
                    </div>
                    
                    <div class="info-label">Tanggal:</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($invoice->medicalRecord->created_at)->format('d M Y H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Info -->
    <div class="row">
        <div class="col-12 mb-3">
            <div class="info-section">
                <h6 class="info-title">
                    <i class="fas fa-file-medical me-2"></i>
                    Informasi Medis
                </h6>
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <strong>Keluhan:</strong>
                        <p class="mb-0 text-muted">{{ $invoice->medicalRecord->complaint }}</p>
                    </div>
                    <div class="col-md-12 mb-2">
                        <strong>Diagnosa:</strong>
                        <p class="mb-0 text-muted">{{ $invoice->medicalRecord->diagnosis }}</p>
                    </div>
                    <div class="col-md-12">
                        <strong>Tindakan:</strong>
                        <p class="mb-0 text-muted">{{ $invoice->medicalRecord->treatment }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fee Breakdown -->
    <div class="fee-breakdown">
        <h6 class="mb-3" style="color: #667eea; font-weight: 600;">
            <i class="fas fa-money-bill-wave me-2"></i>
            Rincian Biaya
        </h6>
        
        <div class="fee-item">
            <span><i class="fas fa-user-doctor me-2 text-primary"></i>Biaya Konsultasi:</span>
            <span>Rp {{ number_format($invoice->consultation_fee, 0, ',', '.') }}</span>
        </div>
        
        <div class="fee-item">
            <span><i class="fas fa-pills me-2 text-success"></i>Biaya Obat:</span>
            <span>Rp {{ number_format($invoice->medication_fee, 0, ',', '.') }}</span>
        </div>
        
        <div class="fee-item">
            <span><i class="fas fa-hand-holding-medical me-2 text-info"></i>Biaya Tindakan:</span>
            <span>Rp {{ number_format($invoice->treatment_fee, 0, ',', '.') }}</span>
        </div>
        
        <div class="fee-item">
            <span><i class="fas fa-list me-2 text-warning"></i>Biaya Lainnya:</span>
            <span>Rp {{ number_format($invoice->other_fee ?? 0, 0, ',', '.') }}</span>
        </div>
        
        <div class="fee-item" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px; border-radius: 10px; margin-top: 20px;">
            <span style="font-size: 1.2rem;">
                <i class="fas fa-calculator me-2"></i>Total Pembayaran:
            </span>
            <span style="font-size: 1.5rem; font-weight: 700;">
                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex justify-content-end gap-2 mt-3">
        <a href="{{ route('admin.print.invoice', $invoice->id) }}" class="btn btn-success" target="_blank">
            <i class="fas fa-print me-2"></i>
            Cetak Invoice
        </a>
        @if($invoice->status == 'Belum Lunas')
        <button type="button" class="btn btn-primary" onclick="closeModalAndMarkAsPaid({{ $invoice->id }})">
            <i class="fas fa-check-circle me-2"></i>
            Tandai Sebagai Lunas
        </button>
        @endif
    </div>
</div>

