@extends('patient.includes.header')

@section('title', 'Riwayat Medis')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Riwayat Medis Saya</h1>
                <p class="page-subtitle">Lihat hasil pemeriksaan, resep obat, dan surat rujukan</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-file-medical me-2"></i>
                Total: {{ $medical_records->count() }} Rekam Medis
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Medical Records List -->
    <div class="card">
        <div class="card-body">
            @forelse($medical_records as $record)
            @php
                $invoice = $record->invoice ?? null;
                $canViewAll = $invoice && $invoice->status == 'Lunas';
                $canViewPrescription = $record->service_type == 'rawat_jalan' && $record->prescription_data && $canViewAll;
            @endphp
            
            <div class="medical-record-item border rounded p-3 p-md-4 mb-4">
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $record->doctor->name }}</h5>
                                <p class="text-muted mb-0">{{ $record->doctor->specialty }}</p>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6">
                                <div class="record-detail">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($record->created_at)->format('d F Y') }}
                                </div>
                                <div class="record-detail">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }}
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <div class="record-detail">
                                    <i class="fas fa-tag text-info me-2"></i>
                                    <strong>Jenis:</strong>
                                    <span class="service-badge service-{{ $record->service_type }}">
                                        {{ $record->service_type == 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        @if(!$canViewAll)
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-lock me-2"></i>
                            <strong>Informasi terkunci</strong> - Pembayaran belum lunas
                        </div>
                        @else
                        <div class="medical-info">
                            <div class="info-section mb-3">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-comment-dots me-1"></i>
                                    Keluhan
                                </h6>
                                <p class="mb-0">{{ $record->complaint }}</p>
                            </div>
                            
                            <div class="info-section mb-3">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-diagnoses me-1"></i>
                                    Diagnosa
                                </h6>
                                <p class="mb-0">{{ $record->diagnosis }}</p>
                            </div>
                            
                            <div class="info-section">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-procedures me-1"></i>
                                    Tindakan
                                </h6>
                                <p class="mb-0">{{ $record->treatment }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-md-4">
                        <div class="record-actions">
                            <h6 class="text-muted mb-3">Dokumen & Aksi</h6>
                            
                            
                            @if($record->service_type == 'rawat_jalan' && $record->prescription_data)
                            <div class="action-item mb-3">
                                <div class="p-3 border rounded {{ !$canViewPrescription ? 'bg-light' : '' }}">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-prescription text-success me-2"></i>
                                        <strong>Resep Obat</strong>
                                        @if(!$canViewPrescription)
                                            <small class="text-danger ms-2">
                                                <i class="fas fa-lock me-1"></i>
                                                Belum Dibayar
                                            </small>
                                        @endif
                                    </div>
                                    @if($canViewPrescription)
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-outline-success btn-sm" 
                                                    onclick="viewPrescription({{ $record->id }})">
                                                <i class="fas fa-eye me-1"></i>
                                                Lihat
                                            </button>
                                            <a href="{{ route('patient.print.prescription', $record->id) }}" 
                                               class="btn btn-outline-success btn-sm" target="_blank">
                                                <i class="fas fa-download me-1"></i>
                                                Download
                                            </a>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mb-0">
                                            <small>
                                                <i class="fas fa-info-circle me-1"></i>
                                                Resep dapat dilihat setelah pembayaran lunas
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            @if($record->service_type == 'rawat_inap' && $record->referralLetter)
                            <div class="action-item mb-3">
                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                    <div>
                                        <i class="fas fa-file-medical-alt text-warning me-2"></i>
                                        <strong>Surat Rujukan</strong>
                                    </div>
                                    <a href="{{ route('patient.print.referral', $record->id) }}" 
                                       class="btn btn-outline-warning btn-sm" target="_blank">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="action-item mb-3">
                                <div class="d-flex align-items-center justify-content-between p-3 border rounded">
                                    <div>
                                        <i class="fas fa-file-invoice text-info me-2"></i>
                                        <strong>Invoice</strong>
                                        @if($invoice)
                                            <br><small class="text-muted">Status: {{ $invoice->status }}</small>
                                        @else
                                            <br><small class="text-warning">Sedang diproses</small>
                                        @endif
                                    </div>
                                    @if($invoice)
                                        <a href="{{ route('patient.invoices') }}" 
                                           class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye me-1"></i>
                                            Lihat
                                        </a>
                                    @else
                                        <button class="btn btn-outline-secondary btn-sm" disabled>
                                            <i class="fas fa-clock me-1"></i>
                                            Diproses
                                        </button>
                                    @endif
                                </div>
                            </div>
                            
                            @if($record->notes)
                            <div class="notes-section mt-3">
                                <h6 class="text-muted mb-2">
                                    <i class="fas fa-sticky-note me-1"></i>
                                    Catatan Tambahan
                                </h6>
                                <div class="p-3 bg-light rounded">
                                    <p class="mb-0">{{ $record->notes }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-file-medical fa-4x mb-4 d-block"></i>
                <h5>Belum ada riwayat medis</h5>
                <p class="mb-4">Anda belum memiliki rekam medis</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Buat Appointment
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($medical_records->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $medical_records->links() }}
    </div>
    @endif

    <!-- Prescription View Modal -->
    <div class="modal fade" id="prescriptionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-prescription me-2"></i>
                        Resep Obat
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="prescriptionContent">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat resep obat...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-success" onclick="downloadPrescription()">
                        <i class="fas fa-download me-1"></i>
                        Download PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<style>
.medical-record-item {
    background: white;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0 !important;
}

.medical-record-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-color: #667eea !important;
}

.record-detail {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.medical-info {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
}

.info-section {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 1rem;
}

.info-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.service-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.service-rawat-jalan {
    background: #dbeafe;
    color: #1e40af;
}

.service-rawat-inap {
    background: #fef3c7;
    color: #92400e;
}

.record-actions {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
}

.action-item {
    transition: all 0.3s ease;
}

.action-item:hover {
    transform: translateX(5px);
}

.notes-section {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}

.action-item .btn {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
}

.action-item .btn i {
    font-size: 0.7rem;
}
</style>

<script>
let currentPrescriptionId = null;

function viewPrescription(recordId) {
    currentPrescriptionId = recordId;
    const modal = new bootstrap.Modal(document.getElementById('prescriptionModal'));
    modal.show();
    
    // Load prescription data
    loadPrescriptionData(recordId);
}

function loadPrescriptionData(recordId) {
    console.log('Loading prescription for record ID:', recordId);
    
    fetch(`/patient/prescription/${recordId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Prescription data received:', data);
        if (data.success && data.prescription) {
            displayPrescription(data.prescription);
        } else {
            document.getElementById('prescriptionContent').innerHTML = 
                '<div class="alert alert-warning">' + (data.message || 'Tidak ada resep obat yang tersedia') + '</div>';
        }
    })
    .catch(error => {
        console.error('Error loading prescription:', error);
        document.getElementById('prescriptionContent').innerHTML = 
            '<div class="alert alert-danger">Terjadi kesalahan saat memuat resep obat: ' + error.message + '</div>';
    });
}

function displayPrescription(prescription) {
    const content = document.getElementById('prescriptionContent');
    
    console.log('Displaying prescription:', prescription); // Debug log
    
    // Handle both array and object formats
    let medicines = [];
    if (Array.isArray(prescription)) {
        medicines = prescription;
    } else if (typeof prescription === 'object' && prescription !== null) {
        // Convert object to array
        medicines = Object.values(prescription);
    }
    
    console.log('Processed medicines:', medicines);
    
    if (medicines && medicines.length > 0) {
        let html = '<div class="prescription-list">';
        let validMedicines = 0;
        
        medicines.forEach((medicine, index) => {
            if (medicine && medicine.name && medicine.name.trim() !== '') {
                validMedicines++;
                html += `
                    <div class="medicine-item mb-3 p-3 border rounded bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <span class="badge bg-primary me-2">${validMedicines}</span>
                            <h6 class="mb-0 text-primary">${medicine.name}</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block">Dosis:</small>
                                    <strong class="text-dark">${medicine.dosage || 'Tidak diisi'}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block">Jumlah:</small>
                                    <strong class="text-dark">${medicine.quantity || '0'} tablet/kapsul</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-white p-2 rounded border">
                                    <small class="text-muted d-block">Cara Pakai:</small>
                                    <strong class="text-dark">${medicine.instructions || 'Tidak diisi'}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        });
        
        if (validMedicines === 0) {
            html = '<div class="alert alert-warning">Tidak ada obat yang diisi dengan benar</div>';
        } else {
            html += '</div>';
        }
        
        content.innerHTML = html;
    } else {
        content.innerHTML = '<div class="alert alert-info">Tidak ada resep obat yang diisi</div>';
    }
}

function downloadPrescription() {
    if (currentPrescriptionId) {
        window.open(`/patient/print/prescription/${currentPrescriptionId}`, '_blank');
    }
}
</script>
@endsection
