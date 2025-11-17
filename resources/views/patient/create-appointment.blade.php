@extends('patient.includes.header')

@section('title', 'Buat Janji Temu')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Buat Janji Temu Baru</h1>
                <p class="page-subtitle">Daftar janji dengan dokter pilihan Anda</p>
            </div>
            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('patient.appointments.store') }}" method="POST" onsubmit="return validateAppointment()">
        @csrf
        
        <div class="row">
            <!-- Doctor Selection -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-user-md me-2"></i>
                            Pilih Dokter
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($doctors->count() > 0)
                            <div class="row">
                                @foreach($doctors as $doctor)
                                <div class="col-12 mb-3">
                                    <div class="doctor-card" onclick="selectDoctor({{ $doctor->id }}, event)">
                                        <input type="radio" name="doctor_id" value="{{ $doctor->id }}" id="doctor_{{ $doctor->id }}" class="d-none" required>
                                        <div class="doctor-info">
                                            <div class="d-flex align-items-center">
                                                <div class="doctor-avatar">
                                                    <i class="fas fa-user-md"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="doctor-name">{{ $doctor->name }}</h6>
                                                    <p class="doctor-specialty">{{ $doctor->specialty }}</p>
                                                    <div class="doctor-schedule">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <span>
                                                            @if(is_array($doctor->schedule))
                                                                @foreach($doctor->schedule as $day => $times)
                                                                    {{ ucfirst($day) }}: {{ $times['start'] ?? '' }}-{{ $times['end'] ?? '' }}<br>
                                                                @endforeach
                                                            @else
                                                                {{ $doctor->schedule }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="doctor-status">
                                                    @if($doctor->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-user-md fa-3x mb-3 d-block"></i>
                                <p>Tidak ada dokter yang tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Appointment Details -->
            <div class="col-12 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="fas fa-calendar-alt me-2"></i>
                            Detail Appointment
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <label for="appointment_date" class="form-label">
                                    Tanggal Appointment <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                <small class="text-muted">Pilih tanggal minimal besok</small>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="appointment_time" class="form-label">
                                    Waktu Appointment <span class="text-danger">*</span>
                                </label>
                                <select class="form-control" id="appointment_time" name="appointment_time" required disabled>
                                    <option value="">Pilih tanggal dan dokter terlebih dahulu</option>
                                </select>
                                <small class="text-muted">Pilih waktu yang tersedia (interval 30 menit)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="complaint" class="form-label">
                                Keluhan <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="complaint" name="complaint" rows="4"
                                      placeholder="Jelaskan keluhan atau gejala yang Anda alami..." required></textarea>
                            <small class="text-muted">Deskripsikan keluhan Anda dengan detail untuk membantu dokter</small>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi Penting:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Appointment akan diproses dalam 1x24 jam</li>
                                <li>Nomor antrian akan diberikan setelah appointment diterima</li>
                                <li>Pastikan Anda datang sesuai jadwal yang ditentukan</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="d-flex flex-column flex-sm-row justify-content-center gap-2 gap-sm-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Buat Appointment
                            </button>
                            <a href="{{ route('patient.appointments') }}" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-times me-2"></i>
                                Batal
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('scripts')
<style>
.doctor-card {
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
}

.doctor-card:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.15);
}

.doctor-card.selected {
    border-color: #667eea;
    background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
}

.doctor-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.doctor-name {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.doctor-specialty {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.doctor-schedule {
    color: #3b82f6;
    font-size: 0.85rem;
    font-weight: 500;
}

.doctor-status {
    margin-left: auto;
}

.form-control {
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    padding: 12px 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-lg {
    padding: 12px 32px;
    font-size: 1.1rem;
    border-radius: 10px;
}
</style>

<script>
function selectDoctor(doctorId, event) {
    // Remove selected class from all cards
    document.querySelectorAll('.doctor-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    event.currentTarget.classList.add('selected');

    // Check the radio button
    document.getElementById('doctor_' + doctorId).checked = true;

    // Load available slots if date is selected
    loadAvailableSlots();
}

function validateAppointment() {
    const doctorId = document.querySelector('input[name="doctor_id"]:checked');
    const appointmentDate = document.getElementById('appointment_date').value;
    const appointmentTime = document.getElementById('appointment_time').value;
    const complaint = document.getElementById('complaint').value.trim();
    
    if (!doctorId) {
        alert('Pilih dokter terlebih dahulu!');
        return false;
    }
    
    if (!appointmentDate) {
        alert('Pilih tanggal appointment!');
        return false;
    }
    
    if (!appointmentTime) {
        alert('Pilih waktu appointment!');
        return false;
    }
    
    if (!complaint) {
        alert('Isi keluhan Anda!');
        return false;
    }
    
    // Check if date is not in the past
    const selectedDate = new Date(appointmentDate);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (selectedDate <= today) {
        alert('Tanggal appointment harus minimal besok!');
        return false;
    }
    
    return true;
}

// Set minimum date to tomorrow and add event listeners
document.addEventListener('DOMContentLoaded', function() {
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    const tomorrowStr = tomorrow.toISOString().split('T')[0];
    document.getElementById('appointment_date').value = tomorrowStr;

    // Add event listeners for dynamic slot loading
    document.getElementById('appointment_date').addEventListener('change', loadAvailableSlots);
});

function loadAvailableSlots() {
    const doctorId = document.querySelector('input[name="doctor_id"]:checked');
    const dateInput = document.getElementById('appointment_date');
    const timeSelect = document.getElementById('appointment_time');

    if (!doctorId || !dateInput.value) {
        timeSelect.innerHTML = '<option value="">Pilih tanggal dan dokter terlebih dahulu</option>';
        timeSelect.disabled = true;
        return;
    }

    // Show loading
    timeSelect.innerHTML = '<option value="">Memuat slot tersedia...</option>';
    timeSelect.disabled = true;

    // Fetch available slots
    fetch(`/patient/available-slots?doctor_id=${doctorId.value}&date=${dateInput.value}`)
        .then(response => response.json())
        .then(data => {
            timeSelect.innerHTML = '<option value="">Pilih Waktu</option>';

            if (data.success && data.slots.length > 0) {
                data.slots.forEach(slot => {
                    const option = document.createElement('option');
                    option.value = slot + ':00'; // Add seconds for database format
                    option.textContent = slot;
                    timeSelect.appendChild(option);
                });
                timeSelect.disabled = false;
            } else {
                timeSelect.innerHTML = '<option value="">Tidak ada slot tersedia pada tanggal ini</option>';
                timeSelect.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error loading slots:', error);
            timeSelect.innerHTML = '<option value="">Error memuat slot</option>';
            timeSelect.disabled = true;
        });
}
</script>
@endsection
