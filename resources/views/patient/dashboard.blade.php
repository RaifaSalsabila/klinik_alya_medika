@extends('patient.includes.header')

@section('title', 'Dashboard Pasien')

@section('content')
    <!-- Welcome Section -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Selamat Datang, {{ $user->name }}!</h1>
                <p class="page-subtitle">Kelola janji temu dan lihat riwayat medis Anda</p>
            </div>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i>
                {{ date('d F Y') }}
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number">{{ $appointments->count() }}</div>
                <div class="stat-label">Total Janji Temu</div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #dcfce7, #bbf7d0); color: #166534;">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-number">{{ $medical_records->count() }}</div>
                <div class="stat-label">Rekam Medis</div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e;">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div class="stat-number">{{ $invoices->count() }}</div>
                <div class="stat-label">Invoice</div>
            </div>
        </div>

        <div class="col-6 col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3;">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-number">{{ $doctors->count() }}</div>
                <div class="stat-label">Dokter Tersedia</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Appointments -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-calendar-alt me-2"></i>
                        Janji Temu Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($appointments as $appointment)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded">
                        <div class="d-flex align-items-center mb-2 mb-sm-0 me-sm-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $appointment->doctor->name }}</h6>
                                <small class="text-muted">{{ $appointment->doctor->specialty }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-secondary me-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                    </span>
                                    <span class="badge bg-info me-2">
                                        <i class="fas fa-clock me-1"></i>
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                    </span>
                                    @if($appointment->queue_number)
                                    <span class="badge bg-warning">
                                        <i class="fas fa-sort-numeric-up me-1"></i>
                                        Antrian #{{ $appointment->queue_number }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-start text-sm-end w-100">
                            <span class="status-badge status-{{ strtolower(str_replace(' ', '', $appointment->status)) }}">
                                {{ $appointment->status }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                        <p>Belum ada appointment</p>
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>
                            Buat Appointment
                        </a>
                    </div>
                    @endforelse

                    @if($appointments->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Appointment
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Medical Records -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-file-medical me-2"></i>
                        Rekam Medis Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($medical_records as $record)
                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center mb-3 p-3 border rounded">
                        <div class="d-flex align-items-center mb-2 mb-sm-0 me-sm-3">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-stethoscope"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $record->doctor->name }}</h6>
                                <small class="text-muted">{{ $record->doctor->specialty }}</small>
                                <div class="mt-1">
                                    <span class="badge bg-secondary me-2">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($record->created_at)->format('d/m/Y') }}
                                    </span>
                                    <span class="badge {{ $record->service_type == 'rawat_jalan' ? 'bg-info' : 'bg-warning' }}">
                                        {{ $record->service_type == 'rawat_jalan' ? 'Rawat Jalan' : 'Rawat Inap' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-start text-sm-end w-100">
                            <a href="{{ route('patient.history') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-file-medical fa-3x mb-3 d-block"></i>
                        <p>Belum ada rekam medis</p>
                    </div>
                    @endforelse

                    @if($medical_records->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('patient.history') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Riwayat
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-6 col-md-3 mb-2 mb-md-3">
                            <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Buat Appointment
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-3">
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-primary w-100">
                                <i class="fas fa-calendar-alt me-2"></i>
                                Lihat Appointment
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-3">
                            <a href="{{ route('patient.history') }}" class="btn btn-outline-success w-100">
                                <i class="fas fa-file-medical me-2"></i>
                                Riwayat Medis
                            </a>
                        </div>
                        <div class="col-6 col-md-3 mb-2 mb-md-3">
                            <a href="{{ route('patient.profile') }}" class="btn btn-outline-info w-100">
                                <i class="fas fa-user me-2"></i>
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

