@extends('patient.includes.header')

@section('title', 'Jadwal Dokter')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="h3 mb-1">
                <i class="fas fa-calendar-alt text-primary me-2"></i>
                Jadwal Dokter
            </h2>
            <p class="text-muted mb-0">Lihat jadwal praktik dokter yang tersedia</p>
        </div>
        <div>
            <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <!-- Doctor Schedule Cards -->
    <div class="row">
        @forelse($doctors as $doctor)
        <div class="col-12 col-sm-6 col-lg-6 col-xl-4 mb-4">
            <div class="card h-100 shadow-sm border-0 doctor-card">
                <div class="card-body">
                    <!-- Doctor Header -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="doctor-avatar me-3">
                            <i class="fas fa-user-md text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1 text-primary">{{ $doctor->name }}</h5>
                            <p class="text-muted mb-0 small">{{ $doctor->specialty }}</p>
                        </div>
                        <div class="status-badge">
                            <span class="badge bg-success">Aktif</span>
                        </div>
                    </div>

                    <!-- Schedule Info -->
                    <div class="schedule-info">
                        <div class="schedule-item mb-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-info me-2"></i>
                                <span class="fw-medium">Jadwal Praktik:</span>
                            </div>
                            <div class="schedule-details mt-1">
                                @if($doctor->schedule && is_array($doctor->schedule))
                                    @foreach($doctor->schedule as $day => $times)
                                        <div class="schedule-day">
                                            <span class="day-name">{{ ucfirst($day) }}:</span>
                                            <span class="day-time">{{ $times['start'] ?? '' }} - {{ $times['end'] ?? '' }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="schedule-day">
                                        <span class="day-time text-muted">Jadwal belum diatur</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="schedule-item">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-phone text-success me-2"></i>
                                <span class="fw-medium">Kontak:</span>
                            </div>
                            <div class="contact-details mt-1">
                                <div class="contact-item">
                                    <div class="contact-label">Telepon:</div>
                                    <div class="contact-value">{{ $doctor->phone ?? 'Tidak tersedia' }}</div>
                                </div>
                                <div class="contact-item">
                                    <div class="contact-label">Email:</div>
                                    <div class="contact-value">{{ $doctor->email ?? 'Tidak tersedia' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light border-0">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="fas fa-calendar-plus me-1"></i>
                            Buat Janji
                        </a>
                        <button class="btn btn-outline-info btn-sm" onclick="showDoctorDetails({{ $doctor->id }})">
                            <i class="fas fa-info-circle"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-user-md text-muted" style="font-size: 4rem;"></i>
                <h4 class="text-muted mt-3">Tidak Ada Dokter</h4>
                <p class="text-muted">Belum ada dokter yang terdaftar di sistem.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

<!-- Doctor Details Modal -->
<div class="modal fade" id="doctorDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-md me-2"></i>
                    Detail Dokter
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="doctorDetailsContent">
                <div class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail dokter...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary">
                    <i class="fas fa-calendar-plus me-1"></i>
                    Buat Janji
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.doctor-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef;
}

.doctor-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
}

.doctor-avatar {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.status-badge .badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.schedule-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 1rem;
    margin-top: 1rem;
}

.schedule-item {
    margin-bottom: 1rem;
}

.schedule-item:last-child {
    margin-bottom: 0;
}

.schedule-day {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #e9ecef;
}

.schedule-day:last-child {
    border-bottom: none;
}

.day-name {
    font-weight: 600;
    color: #495057;
    min-width: 80px;
}

.day-time {
    color: #6c757d;
    font-size: 0.9rem;
}

.contact-details {
    margin-top: 1rem;
    font-size: 0.9rem;
}

.contact-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.25rem 0;
    border-bottom: 1px solid #e9ecef;
}

.contact-label {
    font-weight: 500;
    color: #495057;
    min-width: 60px;
}

.contact-value {
    color: #6c757d;
    text-align: right;
    flex-grow: 1;
    min-width: 120px;
}

.card-footer {
    padding: 1rem;
}

.btn-sm {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

@media (max-width: 768px) {
    .doctor-card {
        margin-bottom: 1rem;
    }
    
    .schedule-day {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .day-time {
        margin-top: 0.25rem;
    }
    
    .contact-item {
        flex-direction: column;
        align-items: flex-start;
    }

    .contact-item:last-child {
        border-bottom: none;
    }
    
    .contact-value {
        text-align: left;
        margin-top: 0.25rem;
    }
        .contact-item .contact-value {
        text-align: left;
    }
}
</style>

<script>
function showDoctorDetails(doctorId) {
    const modal = new bootstrap.Modal(document.getElementById('doctorDetailsModal'));
    modal.show();
    
    // Load doctor details (you can implement this if needed)
    document.getElementById('doctorDetailsContent').innerHTML = `
        <div class="text-center">
            <i class="fas fa-user-md text-primary" style="font-size: 3rem;"></i>
            <h5 class="mt-3">Detail Dokter</h5>
            <p class="text-muted">Fitur detail dokter akan segera tersedia.</p>
        </div>
    `;
}
</script>
@endsection

