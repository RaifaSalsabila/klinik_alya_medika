@extends('patient.includes.header')

@section('title', 'Invoice Saya')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Invoice Saya</h1>
                <p class="page-subtitle">Lihat dan download invoice pembayaran</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-file-invoice me-2"></i>
                Total: {{ $invoices->count() }} Invoice
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Invoices List -->
    <div class="card">
        <div class="card-body">
            @forelse($invoices as $invoice)
            <div class="invoice-item border rounded p-4 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-file-invoice"></i>
                            </div>
                            <div>
                                <h5 class="mb-1">Invoice #{{ $invoice->id }}</h5>
                                <p class="text-muted mb-0">
                                    {{ $invoice->medicalRecord ? $invoice->medicalRecord->doctor->name : 'Dokter tidak tersedia' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="invoice-detail">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}
                                </div>
                                <div class="invoice-detail">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('H:i') }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-detail">
                                    <i class="fas fa-tag text-info me-2"></i>
                                    <strong>Status:</strong> 
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '', $invoice->status)) }}">
                                        {{ $invoice->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="invoice-summary text-end">
                            <div class="amount-display mb-3">
                                <h4 class="text-primary mb-0">
                                    Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                </h4>
                                <small class="text-muted">Total Pembayaran</small>
                            </div>
                            
                            <div class="invoice-actions">
                                <a href="{{ route('patient.print.invoice', $invoice->id) }}" 
                                   class="btn btn-primary btn-sm" target="_blank">
                                    <i class="fas fa-download me-1"></i>
                                    Download PDF
                                </a>
                                
                                @if($invoice->status == 'Belum Lunas')
                                <button class="btn btn-outline-warning btn-sm ms-2" 
                                        onclick="showPaymentInfo()">
                                    <i class="fas fa-credit-card me-1"></i>
                                    Lihat Detail Pembayaran
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Invoice Details (Collapsible) -->
                <div class="invoice-details mt-3" style="display: none;">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Detail Biaya</h6>
                            <div class="cost-breakdown">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Konsultasi:</span>
                                    <span>Rp {{ number_format($invoice->consultation_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Obat:</span>
                                    <span>Rp {{ number_format($invoice->medication_fee, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Tindakan:</span>
                                    <span>Rp {{ number_format($invoice->treatment_fee, 0, ',', '.') }}</span>
                                </div>
                                @if($invoice->other_fee > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Biaya Lainnya:</span>
                                    <span>Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-3">Informasi Pembayaran</h6>
                            <div class="payment-info">
                                <p class="mb-2">
                                    <i class="fas fa-building me-2"></i>
                                    <strong>Bank:</strong> BCA
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-credit-card me-2"></i>
                                    <strong>No. Rekening:</strong> 1234567890
                                </p>
                                <p class="mb-2">
                                    <i class="fas fa-user me-2"></i>
                                    <strong>Atas Nama:</strong> Klinik Alya Medika
                                </p>
                                <p class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Catatan:</strong> Gunakan ID Invoice sebagai referensi
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-outline-secondary btn-sm" onclick="toggleDetails(this)">
                        <i class="fas fa-chevron-down me-1"></i>
                        Lihat Detail
                    </button>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-file-invoice fa-4x mb-4 d-block"></i>
                <h5>Belum ada invoice</h5>
                <p class="mb-4">Anda belum memiliki invoice pembayaran</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-2"></i>
                    Buat Janji Temu
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $invoices->links() }}
    </div>
    @endif

    <!-- Payment Info Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Informasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Panduan Pembayaran:</strong>
                    </div>
                    <div class="payment-guide">
                        <p><strong>1. Transfer ke rekening berikut:</strong></p>
                        <div class="bg-light p-3 rounded mb-3">
                            <p class="mb-1"><strong>Bank:</strong> BCA</p>
                            <p class="mb-1"><strong>No. Rekening:</strong> 1234567890</p>
                            <p class="mb-0"><strong>Atas Nama:</strong> Klinik Alya Medika</p>
                        </div>
                        <p><strong>2. Gunakan ID Invoice sebagai referensi transfer</strong></p>
                        <p><strong>3. Setelah transfer, konfirmasi ke admin klinik</strong></p>
                        <p><strong>4. Status invoice akan diupdate setelah pembayaran dikonfirmasi</strong></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<style>
.invoice-item {
    background: white;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0 !important;
}

.invoice-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-color: #667eea !important;
}

.invoice-detail {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.amount-display {
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 10px;
    padding: 1rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-belum-lunas {
    background: #fef3c7;
    color: #92400e;
}

.status-lunas {
    background: #dcfce7;
    color: #166534;
}

.cost-breakdown {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
}

.payment-info {
    background: #f8fafc;
    border-radius: 10px;
    padding: 1rem;
}

.invoice-details {
    border-top: 1px solid #e2e8f0;
    padding-top: 1rem;
}
</style>

<script>
function toggleDetails(button) {
    const details = button.closest('.invoice-item').querySelector('.invoice-details');
    const icon = button.querySelector('i');
    
    if (details.style.display === 'none') {
        details.style.display = 'block';
        icon.className = 'fas fa-chevron-up me-1';
        button.innerHTML = '<i class="fas fa-chevron-up me-1"></i> Sembunyikan Detail';
    } else {
        details.style.display = 'none';
        icon.className = 'fas fa-chevron-down me-1';
        button.innerHTML = '<i class="fas fa-chevron-down me-1"></i> Lihat Detail';
    }
}

function showPaymentInfo() {
    const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
    modal.show();
}
</script>
@endsection

