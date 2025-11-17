@extends('dokter.includes.header')

@section('title', 'Dashboard Dokter')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Dashboard Dokter</h1>
                <p class="page-subtitle">Overview jadwal dan janji temu</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i>
                {{ date('d F Y') }}
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="stat-number">{{ $stats['total_appointments'] }}</div>
                        <div class="stat-label">Total Janji Temu</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="stat-number">{{ $stats['pending_appointments'] }}</div>
                        <div class="stat-label">Menunggu</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="stat-number">{{ $stats['completed_appointments'] }}</div>
                        <div class="stat-label">Selesai</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="stat-number">{{ $stats['today_appointments'] }}</div>
                        <div class="stat-label">Janji Hari Ini</div>
                        <small class="text-muted">{{ $stats['today_pending'] }} menunggu</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                Janji Temu Terbaru
            </h5>
        </div>
        <div class="card-body">
            @if($pending_appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pending_appointments as $index => $appointment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower($appointment->status) }}">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('dokter.create-medical-record', $appointment->id) }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-file-medical me-1"></i>
                                            Input Rekam Medis
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('dokter.appointments') }}" class="btn btn-outline-primary">
                        <i class="fas fa-eye me-2"></i>
                        Lihat Semua Janji Temu
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h5>Tidak Ada Janji Temu</h5>
                    <p>Belum ada janji temu yang menunggu</p>
                </div>
            @endif
        </div>
    </div>
@endsection

