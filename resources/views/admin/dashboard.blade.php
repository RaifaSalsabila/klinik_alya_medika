@extends('admin.includes.header')

@section('title', 'Dashboard Admin')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Dashboard Admin</h1>
                <p class="page-subtitle">Overview sistem manajemen klinik</p>
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
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['total_patients'] }}</div>
                            <div class="stat-label">Total Pasien</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['active_doctors'] }}</div>
                            <div class="stat-label">Dokter Aktif</div>
                            <small class="text-muted">dari {{ $stats['total_doctors'] }} total</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['today_appointments'] }}</div>
                            <div class="stat-label">Janji Temu Hari Ini</div>
                            <small class="text-muted">{{ $stats['pending_appointments'] }} menunggu</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number stat-revenue">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</div>
                            <div class="stat-label">Pendapatan Lunas</div>
                            <small class="text-muted">{{ $stats['paid_invoices'] }} invoice lunas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['unpaid_invoices'] }}</div>
                            <div class="stat-label">Invoice Belum Lunas</div>
                            <small class="text-muted">dari {{ $stats['total_invoices'] }} total</small>
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
                            <div class="stat-number stat-revenue">Rp {{ number_format($stats['today_revenue'], 0, ',', '.') }}</div>
                            <div class="stat-label">Pendapatan Hari Ini</div>
                            <small class="text-muted">dari invoice lunas</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #84cc16, #65a30d);">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['completed_appointments'] }}</div>
                            <div class="stat-label">Appointment Selesai</div>
                            <small class="text-muted">dari {{ $stats['total_appointments'] }} total</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f97316, #ea580c);">
                            <i class="fas fa-percentage"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <div class="stat-number">{{ $stats['total_invoices'] > 0 ? number_format(($stats['paid_invoices'] / $stats['total_invoices']) * 100, 1) : 0 }}%</div>
                            <div class="stat-label">Tingkat Pembayaran</div>
                            <small class="text-muted">invoice lunas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Appointments -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Janji Temu Terbaru</h5>
                        <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pasien</th>
                                    <th>Dokter</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_appointments as $index => $appointment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $appointment->patient->name }}</td>
                                    <td>{{ $appointment->doctor->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '', $appointment->status)) }}">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada janji temu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
