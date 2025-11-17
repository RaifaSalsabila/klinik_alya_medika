@extends('patient.includes.header')

@section('title', 'Janji Temu Saya')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Janji Temu Saya</h1>
                <p class="page-subtitle">Kelola dan lihat status janji temu Anda</p>
            </div>
            <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Buat Janji Temu Baru
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter Tabs -->
    <div class="filter-tabs mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('patient.appointments') }}" class="filter-tab {{ request('status') == null ? 'active' : '' }}">
                <i class="fas fa-list me-1"></i>
                Semua
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'Menunggu']) }}" class="filter-tab {{ request('status') == 'Menunggu' ? 'active' : '' }}">
                <i class="fas fa-clock me-1"></i>
                Menunggu
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'Diterima']) }}" class="filter-tab {{ request('status') == 'Diterima' ? 'active' : '' }}">
                <i class="fas fa-check-circle me-1"></i>
                Diterima
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'Selesai']) }}" class="filter-tab {{ request('status') == 'Selesai' ? 'active' : '' }}">
                <i class="fas fa-check-double me-1"></i>
                Selesai
            </a>
            <a href="{{ route('patient.appointments', ['status' => 'Batal']) }}" class="filter-tab {{ request('status') == 'Batal' ? 'active' : '' }}">
                <i class="fas fa-times-circle me-1"></i>
                Batal
            </a>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card">
        <div class="card-body">
            @forelse($appointments as $appointment)
            <div class="appointment-item border rounded p-3 p-md-4 mb-3">
                <div class="row align-items-start align-items-md-center">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user-md"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $appointment->doctor->name }}</h5>
                                <p class="text-muted mb-0">{{ $appointment->doctor->specialty }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="appointment-detail">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}
                                </div>
                                <div class="appointment-detail">
                                    <i class="fas fa-clock text-primary me-2"></i>
                                    <strong>Waktu:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                @if($appointment->queue_number)
                                <div class="appointment-detail">
                                    <i class="fas fa-sort-numeric-up text-warning me-2"></i>
                                    <strong>Antrian:</strong> #{{ $appointment->queue_number }}
                                </div>
                                @endif
                                <div class="appointment-detail">
                                    <i class="fas fa-comment text-info me-2"></i>
                                    <strong>Keluhan:</strong> {{ Str::limit($appointment->complaint, 50) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4 text-start text-md-end mt-3 mt-md-0">
                        <div class="mb-2">
                            <span class="status-badge status-{{ strtolower(str_replace(' ', '', $appointment->status)) }}">
                                {{ $appointment->status }}
                            </span>
                        </div>

                        <div class="appointment-actions">
                            @if($appointment->status == 'Menunggu')
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Menunggu konfirmasi dari klinik
                                </small>
                            @elseif($appointment->status == 'Diterima')
                                <small class="text-success d-block mb-2">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Appointment diterima, silakan datang sesuai jadwal
                                </small>
                            @elseif($appointment->status == 'Selesai')
                                <small class="text-success d-block mb-2">
                                    <i class="fas fa-check-double me-1"></i>
                                    Appointment selesai
                                </small>
                                <a href="{{ route('patient.history') }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-file-medical me-1"></i>
                                    Lihat Hasil
                                </a>
                            @elseif($appointment->status == 'Batal')
                                <small class="text-danger d-block mb-2">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Appointment dibatalkan
                                </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center text-muted py-5">
                <i class="fas fa-calendar-times fa-4x mb-4 d-block"></i>
                <h5>Belum ada appointment</h5>
                <p class="mb-4">Anda belum membuat appointment dengan dokter</p>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Buat Appointment Pertama
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($appointments->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $appointments->links() }}
    </div>
    @endif
@endsection

@section('scripts')
<style>
.filter-tabs {
    background: white;
    border-radius: 15px;
    padding: 1rem;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.filter-tab {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    color: #64748b;
    font-weight: 500;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.filter-tab:hover {
    color: #667eea;
    background: #f8fafc;
}

.filter-tab.active {
    color: #667eea;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
    border-color: #667eea;
}

.appointment-item {
    background: white;
    transition: all 0.3s ease;
    border: 1px solid #e2e8f0 !important;
}

.appointment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    border-color: #667eea !important;
}

.appointment-detail {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-menunggu {
    background: #fef3c7;
    color: #92400e;
}

.status-diterima {
    background: #dbeafe;
    color: #1e40af;
}

.status-selesai {
    background: #dcfce7;
    color: #166534;
}

.status-batal {
    background: #fee2e2;
    color: #991b1b;
}

.appointment-actions {
    margin-top: 1rem;
}
</style>
@endsection
