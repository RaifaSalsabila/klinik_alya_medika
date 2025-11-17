@extends('admin.includes.header')

@section('title', 'Kelola Janji Temu')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Lihat Janji Temu</h1>
                <p class="page-subtitle">Daftar janji temu pasien</p>
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

        <!-- Filter Tabs -->
        <div class="filter-tabs">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.appointments') }}" class="filter-tab {{ request('status') == null ? 'active' : '' }}">
                    <i class="fas fa-list me-1"></i>
                    Semua
                </a>
                <a href="{{ route('admin.appointments', ['status' => 'Menunggu']) }}" class="filter-tab {{ request('status') == 'Menunggu' ? 'active' : '' }}">
                    <i class="fas fa-clock me-1"></i>
                    Menunggu
                </a>
                <a href="{{ route('admin.appointments', ['status' => 'Diterima']) }}" class="filter-tab {{ request('status') == 'Diterima' ? 'active' : '' }}">
                    <i class="fas fa-check-circle me-1"></i>
                    Diterima
                </a>
                <a href="{{ route('admin.appointments', ['status' => 'Selesai']) }}" class="filter-tab {{ request('status') == 'Selesai' ? 'active' : '' }}">
                    <i class="fas fa-check-double me-1"></i>
                    Selesai
                </a>
                <a href="{{ route('admin.appointments', ['status' => 'Batal']) }}" class="filter-tab {{ request('status') == 'Batal' ? 'active' : '' }}">
                    <i class="fas fa-times-circle me-1"></i>
                    Batal
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
                                <th>No. Antrian</th>
                                <th>Pasien</th>
                                <th>Dokter</th>
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
                                    <span class="queue-number">{{ $appointment->queue_number ?? '-' }}</span>
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
                                        <div class="fw-semibold">{{ $appointment->doctor->name }}</div>
                                        <small class="text-muted">{{ $appointment->doctor->specialty }}</small>
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
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewAppointment({{ $appointment->id }})"
                                                data-bs-toggle="tooltip" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if($appointment->status == 'Menunggu')
                                        <button class="btn btn-outline-success btn-sm" onclick="updateStatus({{ $appointment->id }}, 'Diterima')" 
                                                data-bs-toggle="tooltip" title="Terima Janji Temu">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm" onclick="updateStatus({{ $appointment->id }}, 'Batal')" 
                                                data-bs-toggle="tooltip" title="Batalkan Janji Temu">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @elseif($appointment->status == 'Diterima')
                                        <button class="btn btn-outline-danger btn-sm" onclick="updateStatus({{ $appointment->id }}, 'Batal')" 
                                                data-bs-toggle="tooltip" title="Batalkan Janji Temu">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
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
    </div>

    <!-- View Appointment Modal -->
    <div class="modal fade" id="viewAppointmentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="appointmentDetail">
                    <!-- Appointment detail will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Janji Temu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin mengubah status janji temu ini?</p>
                        <div class="alert alert-info">
                            <strong>Status Baru:</strong> <span id="newStatus"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Status</button>
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

    function viewAppointment(appointmentId) {
        // Load appointment detail via AJAX
        fetch(`/admin/appointments/${appointmentId}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('appointmentDetail').innerHTML = html;
                new bootstrap.Modal(document.getElementById('viewAppointmentModal')).show();
            })
            .catch(error => {
                console.error('Error loading appointment:', error);
                alert('Terjadi kesalahan saat memuat detail janji temu');
            });
    }

    function updateStatus(appointmentId, status) {
        document.getElementById('updateStatusForm').action = '/admin/appointments/' + appointmentId + '/status';
        document.getElementById('newStatus').textContent = status;
        
        // Remove existing status input if any
        const existingInput = document.querySelector('#updateStatusForm input[name="status"]');
        if (existingInput) {
            existingInput.remove();
        }
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'status';
        statusInput.value = status;
        document.getElementById('updateStatusForm').appendChild(statusInput);
        
        new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
    }
</script>
@endsection
