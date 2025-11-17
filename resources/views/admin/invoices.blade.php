@extends('admin.includes.header')

@section('title', 'Kelola Invoice')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Kelola Invoice</h1>
                <p class="page-subtitle">Manajemen tagihan dan pembayaran pasien</p>
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

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.invoices') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Belum Lunas
                </a>
                <a href="{{ route('admin.invoices', ['status' => 'Lunas']) }}" class="filter-tab {{ request('status') == 'Lunas' ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-1"></i>
                    Lunas
                </a>
                <a href="{{ route('admin.invoices', ['status' => 'all']) }}" class="filter-tab {{ request('status') == 'all' ? 'active' : '' }}">
                    <i class="fas fa-list me-1"></i>
                    Semua
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>No. Invoice</th>
                                <th>Pasien</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $index => $invoice)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="invoice-number">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $invoice->patient->name }}</div>
                                            <small class="text-muted">{{ $invoice->patient->phone }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($invoice->created_at)->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="amount text-success">
                                        Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $invoice->status)) }}">
                                        {{ $invoice->status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-primary" onclick="viewInvoice({{ $invoice->id }})"
                                                data-bs-toggle="tooltip" title="Lihat Detail Invoice">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('admin.print.invoice', $invoice->id) }}" class="btn btn-outline-success" target="_blank"
                                           data-bs-toggle="tooltip" title="Cetak Invoice">
                                            <i class="fas fa-print"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-file-invoice fa-3x mb-3 d-block"></i>
                                    Belum ada invoice
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Invoice Modal -->
    <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="invoiceDetail">
                    <!-- Invoice detail will be loaded here -->
                </div>
                
            </div>
        </div>
    </div>

    <!-- Mark as Paid Modal -->
    <div class="modal fade" id="markAsPaidModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="markAsPaidForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menandai invoice ini sebagai sudah lunas?</p>
                        <div class="alert alert-info">
                            <strong>Status:</strong> Belum Lunas → Lunas
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Konfirmasi Lunas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function viewInvoice(invoiceId) {
        // Load invoice detail via AJAX
        fetch(`/admin/invoices/${invoiceId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('invoiceDetail').innerHTML = html;
                new bootstrap.Modal(document.getElementById('viewInvoiceModal')).show();
            });
    }

    function printInvoice(invoiceId) {
        // Open print window
        window.open(`/admin/invoices/${invoiceId}/print`, '_blank');
    }

    function markAsPaid(invoiceId) {
        document.getElementById('markAsPaidForm').action = `/admin/invoices/${invoiceId}/status`;
        
        // Add hidden input for status
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = 'Lunas';
        document.getElementById('markAsPaidForm').appendChild(statusInput);
        
        new bootstrap.Modal(document.getElementById('markAsPaidModal')).show();
    }

    function closeModalAndMarkAsPaid(invoiceId) {
        // Close view invoice modal
        const viewModal = bootstrap.Modal.getInstance(document.getElementById('viewInvoiceModal'));
        viewModal.hide();
        
        // Show mark as paid modal
        setTimeout(() => {
            markAsPaid(invoiceId);
        }, 300);
    }
</script>
@endsection
