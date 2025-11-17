@extends('dokter.includes.header')

@section('title', 'Rekam Medis')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Rekam Medis</h1>
                <p class="page-subtitle">Manajemen data rekam medis pasien</p>
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

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Pasien</th>
                            <th>Tanggal</th>
                            <th>Jenis Pelayanan</th>
                            <th>Diagnosa</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($medical_records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $record->patient->name }}</div>
                                        <small class="text-muted">{{ $record->patient->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($record->created_at)->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($record->service_type == 'rawat_jalan')
                                    <span class="badge bg-success">Rawat Jalan</span>
                                @else
                                    <span class="badge bg-warning">Rawat Inap</span>
                                @endif
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 200px;" title="{{ $record->diagnosis }}">
                                    {{ $record->diagnosis }}
                                </div>
                            </td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm" onclick="viewRecord({{ $record->id }})"
                                        data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-file-medical fa-3x mb-3 d-block"></i>
                                Tidak ada rekam medis
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- View Record Modal -->
    <div class="modal fade" id="viewRecordModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Rekam Medis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="recordDetail">
                    <!-- Record detail will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function viewRecord(recordId) {
        // Load record detail via AJAX
        fetch(`/dokter/medical-records/${recordId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('recordDetail').innerHTML = html;
                new bootstrap.Modal(document.getElementById('viewRecordModal')).show();
            })
            .catch(error => {
                console.error('Error loading record:', error);
                alert('Terjadi kesalahan saat memuat detail rekam medis');
            });
    }
</script>
@endsection

