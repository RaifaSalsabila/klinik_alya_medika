@extends('dokter.includes.header')

@section('title', 'Kelola Janji Temu')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Kelola Janji Temu</h1>
                <p class="page-subtitle">Manajemen jadwal dan status janji temu</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i>
                {{ date('d F Y') }}
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
                            <th>No. Antrian</th>
                            <th>Pasien</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $index => $appointment)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $appointment->queue_number ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $appointment->patient->name }}</div>
                                        <small class="text-muted">{{ $appointment->patient->phone }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-semibold">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $appointment->status)) }}">
                                    {{ $appointment->status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    @if($appointment->status == 'Diterima')
                                    <a href="{{ route('dokter.create-medical-record', $appointment->id) }}" class="btn btn-outline-primary"
                                       data-bs-toggle="tooltip" title="Buat Rekam Medis">
                                        <i class="fas fa-file-medical"></i>
                                    </a>
                                    @elseif($appointment->status == 'Selesai')
                                    <span class="text-success" data-bs-toggle="tooltip" title="Janji Temu Selesai">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    @elseif($appointment->status == 'Menunggu')
                                    <span class="text-info" data-bs-toggle="tooltip" title="Menunggu Persetujuan Admin">
                                        <i class="fas fa-clock"></i>
                                    </span>
                                    @else
                                    <span class="text-muted" data-bs-toggle="tooltip" title="Janji Temu Dibatalkan">
                                        <i class="fas fa-ban"></i>
                                    </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                                Tidak ada janji temu
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
</script>
@endsection

