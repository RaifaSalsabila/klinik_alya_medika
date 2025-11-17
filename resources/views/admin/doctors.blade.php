@extends('admin.includes.header')

@section('title', 'Kelola Dokter')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Jadwal Dokter</h1>
                <p class="page-subtitle">Manajemen jadwal praktik dokter</p>
            </div>
            @if($availableUsers->count() > 0)
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                <i class="fas fa-plus me-2"></i>
                Tambah Jadwal Dokter
            </button>
            @else
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle me-2"></i>
                Semua dokter sudah memiliki jadwal
            </div>
            @endif
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Doctors Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title">
                <i class="fas fa-user-md me-2"></i>
                Daftar Dokter
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th>Nama Dokter</th>
                            <th>Spesialisasi</th>
                            <th>Jadwal Praktik</th>
                            <th>Kontak</th>
                            <th>Status</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doctors as $index => $doctor)
                        <tr>
                            <td>
                                <span class="badge bg-light text-dark">{{ $index + 1 }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $doctor->name }}</div>
                                        <small class="text-muted">{{ $doctor->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="specialty-simple">{{ $doctor->specialty }}</div>
                            </td>
                            <td>
                                <div class="schedule-simple">
                                    @if(is_array($doctor->schedule))
                                        @foreach($doctor->schedule as $day => $times)
                                            {{ ucfirst($day) }}: {{ $times['start'] ?? '' }}-{{ $times['end'] ?? '' }}<br>
                                        @endforeach
                                    @else
                                        {{ $doctor->schedule }}
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="contact-simple">{{ $doctor->phone }}</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $doctor->is_active ? 'active' : 'inactive' }}">
                                    {{ $doctor->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-outline-primary" onclick="editDoctor({{ $doctor->id }})" title="Edit Dokter">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-success" onclick="viewDoctor({{ $doctor->id }})" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteDoctor({{ $doctor->id }})" title="Hapus Dokter">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="empty-state">
                                <i class="fas fa-user-md"></i>
                                <h5>Belum Ada Data Dokter</h5>
                                <p>Mulai dengan menambahkan data dokter pertama</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jadwal Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.doctors.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Pilih Dokter</label>
                                <select class="form-select" name="user_id" required>
                                    <option value="">Pilih Dokter...</option>
                                    @foreach($availableUsers as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hanya dokter yang belum memiliki jadwal yang ditampilkan</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Spesialisasi</label>
                                <input type="text" class="form-control" name="specialty" placeholder="Masukkan spesialisasi dokter" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Hari Praktik</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Senin" id="senin">
                                            <label class="form-check-label" for="senin">Senin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Selasa" id="selasa">
                                            <label class="form-check-label" for="selasa">Selasa</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Rabu" id="rabu">
                                            <label class="form-check-label" for="rabu">Rabu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Kamis" id="kamis">
                                            <label class="form-check-label" for="kamis">Kamis</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Jumat" id="jumat">
                                            <label class="form-check-label" for="jumat">Jumat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Sabtu" id="sabtu">
                                            <label class="form-check-label" for="sabtu">Sabtu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Minggu" id="minggu">
                                            <label class="form-check-label" for="minggu">Minggu</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jam Praktik</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label small">Dari</label>
                                        <input type="time" class="form-control" name="start_time" value="08:00">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Sampai</label>
                                        <input type="time" class="form-control" name="end_time" value="17:00">
                                    </div>
                                </div>
                                <div class="schedule-preview" id="schedulePreview" style="display: none;">
                                    <i class="fas fa-eye me-2"></i>
                                    <strong>Preview:</strong> <span id="previewText"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Doctor Modal -->
    <div class="modal fade" id="editDoctorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Jadwal Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editDoctorForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Nama Dokter</label>
                                <input type="text" class="form-control" id="edit_name" disabled>
                                <small class="text-muted">Untuk mengubah nama, edit di menu Kelola Akun</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Spesialisasi</label>
                                <input type="text" class="form-control" name="specialty" id="edit_specialty" placeholder="Masukkan spesialisasi dokter" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Hari Praktik</label>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Senin" id="edit_senin">
                                            <label class="form-check-label" for="edit_senin">Senin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Selasa" id="edit_selasa">
                                            <label class="form-check-label" for="edit_selasa">Selasa</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Rabu" id="edit_rabu">
                                            <label class="form-check-label" for="edit_rabu">Rabu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Kamis" id="edit_kamis">
                                            <label class="form-check-label" for="edit_kamis">Kamis</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Jumat" id="edit_jumat">
                                            <label class="form-check-label" for="edit_jumat">Jumat</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Sabtu" id="edit_sabtu">
                                            <label class="form-check-label" for="edit_sabtu">Sabtu</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="Minggu" id="edit_minggu">
                                            <label class="form-check-label" for="edit_minggu">Minggu</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Jam Praktik</label>
                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label small">Dari</label>
                                        <input type="time" class="form-control" name="start_time" id="edit_start_time">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small">Sampai</label>
                                        <input type="time" class="form-control" name="end_time" id="edit_end_time">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select class="form-select" name="is_active" id="edit_is_active" required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Dokter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Doctor Modal -->
    <div class="modal fade" id="viewDoctorModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Dokter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4" id="doctorDetail">
                    <!-- Doctor detail will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
.specialty-simple {
    font-size: 0.875rem;
    color: #6b7280;
    background: #f9fafb;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
    max-width: 200px;
    word-break: break-word;
    line-height: 1.4;
}

.schedule-simple {
    font-size: 0.8rem;
    color: #6b7280;
    background: #f8fafc;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    border-left: 3px solid #3b82f6;
    max-width: 180px;
    word-break: break-word;
    line-height: 1.3;
    display: inline-block;
}

.contact-simple {
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    display: inline-block;
}

.status-badge.active {
    background: #dcfce7;
    color: #166534;
    border: 1px solid #bbf7d0;
}

.status-badge.inactive {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

.action-buttons {
    display: flex;
    gap: 4px;
    flex-wrap: wrap;
}

.action-buttons .btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background: white;
    color: #6b7280;
    transition: all 0.2s ease;
}

.action-buttons .btn:hover {
    background: #f9fafb;
    border-color: #9ca3af;
    color: #374151;
}

.action-buttons .btn i {
    font-size: 0.75rem;
}

.table th {
    background: #f9fafb;
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
    padding: 1rem 0.75rem;
}

.table td {
    border: none;
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f3f4f6;
}

.table tbody tr:hover {
    background: #f9fafb;
}

.card {
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    padding: 1rem 1.5rem;
}

.card-title {
    color: #111827;
    font-weight: 600;
    font-size: 1.125rem;
}

.btn-primary {
    background: #111827;
    border-color: #111827;
    color: white;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    background: #374151;
    border-color: #374151;
    transform: translateY(-1px);
}

.page-title {
    color: #111827;
    font-weight: 700;
    font-size: 2rem;
}

.page-subtitle {
    color: #6b7280;
    font-size: 1rem;
}
</style>
@endsection

@section('scripts')
<script>
    function editDoctor(id) {
        // Fetch doctor data and populate form
        fetch(`/admin/doctors/${id}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(doctor => {
                console.log('Doctor data received:', doctor);
                
                // Populate form fields
                document.getElementById('edit_name').value = doctor.name || '';
                document.getElementById('edit_specialty').value = doctor.specialty || '';
                document.getElementById('edit_is_active').value = doctor.is_active ? '1' : '0';

                // Parse schedule to extract days and times
                const schedule = doctor.schedule || {};

                // Clear all checkboxes in edit modal first (only within edit modal)
                const editModal = document.getElementById('editDoctorModal');
                editModal.querySelectorAll('input[name="days[]"]').forEach(cb => cb.checked = false);

                if (typeof schedule === 'object' && schedule !== null && !Array.isArray(schedule) && Object.keys(schedule).length > 0) {
                    // New JSON format: {"Senin": {"start": "08:00", "end": "17:00"}}
                    const days = Object.keys(schedule);
                    if (days.length > 0) {
                        // Get time from first day (assuming all days have same time)
                        const firstDay = schedule[days[0]];
                        if (firstDay && firstDay.start) {
                            document.getElementById('edit_start_time').value = firstDay.start || '';
                        }
                        if (firstDay && firstDay.end) {
                            document.getElementById('edit_end_time').value = firstDay.end || '';
                        }

                        // Check the appropriate checkboxes
                        days.forEach(day => {
                            const dayMap = {
                                'Senin': 'edit_senin',
                                'Selasa': 'edit_selasa',
                                'Rabu': 'edit_rabu',
                                'Kamis': 'edit_kamis',
                                'Jumat': 'edit_jumat',
                                'Sabtu': 'edit_sabtu',
                                'Minggu': 'edit_minggu'
                            };

                            const checkboxId = dayMap[day];
                            if (checkboxId) {
                                const checkbox = document.getElementById(checkboxId);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            }
                        });
                    }
                } else if (typeof schedule === 'string') {
                    // Fallback for old string format: "Senin, Selasa 08:00-17:00"
                    const timeMatch = schedule.match(/(\d{2}:\d{2})-(\d{2}:\d{2})/);
                    if (timeMatch) {
                        document.getElementById('edit_start_time').value = timeMatch[1];
                        document.getElementById('edit_end_time').value = timeMatch[2];
                    }

                    // Parse days - handle different day formats
                    const scheduleParts = schedule.split(' ');
                    if (scheduleParts.length > 0) {
                        const daysString = scheduleParts[0];
                        const days = daysString.split(', ');

                        // Check the appropriate checkboxes
                        days.forEach(day => {
                            const dayMap = {
                                'Senin': 'edit_senin',
                                'Selasa': 'edit_selasa',
                                'Rabu': 'edit_rabu',
                                'Kamis': 'edit_kamis',
                                'Jumat': 'edit_jumat',
                                'Sabtu': 'edit_sabtu',
                                'Minggu': 'edit_minggu'
                            };

                            const checkboxId = dayMap[day];
                            if (checkboxId) {
                                const checkbox = document.getElementById(checkboxId);
                                if (checkbox) {
                                    checkbox.checked = true;
                                }
                            }
                        });
                    }
                }

                // Set form action
                document.getElementById('editDoctorForm').action = `/admin/doctors/${id}`;

                // Show modal
                new bootstrap.Modal(document.getElementById('editDoctorModal')).show();
            })
            .catch(error => {
                console.error('Error fetching doctor data:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Gagal memuat data dokter. Silakan coba lagi.'
                });
            });
    }

    function viewDoctor(id) {
        // Load doctor detail via AJAX
        fetch(`/admin/doctors/${id}`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('doctorDetail').innerHTML = html;
                new bootstrap.Modal(document.getElementById('viewDoctorModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Gagal memuat detail dokter', 'error');
            });
    }

    function deleteDoctor(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus data dokter ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/admin/doctors/' + id;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Auto-generate schedule preview
    function updateSchedulePreview() {
        const days = Array.from(document.querySelectorAll('input[name="days[]"]:checked')).map(cb => cb.value);
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = document.querySelector('input[name="end_time"]').value;

        const preview = document.getElementById('schedulePreview');
        const previewText = document.getElementById('previewText');

        if (days.length > 0 && startTime && endTime) {
            const schedule = days.join(', ') + ' ' + startTime + '-' + endTime;
            previewText.textContent = schedule;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    // Add event listeners for schedule preview
    document.addEventListener('DOMContentLoaded', function() {
        const dayCheckboxes = document.querySelectorAll('input[name="days[]"]');
        const timeInputs = document.querySelectorAll('input[name="start_time"], input[name="end_time"]');

        dayCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSchedulePreview);
        });

        timeInputs.forEach(input => {
            input.addEventListener('change', updateSchedulePreview);
        });
    });
</script>
@endsection
