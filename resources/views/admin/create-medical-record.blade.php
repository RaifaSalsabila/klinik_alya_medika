@extends('admin.includes.header')

@section('title', 'Input Rekam Medis')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">Input Rekam Medis</h1>
                <p class="page-subtitle">Form input rekam medis pasien</p>
            </div>
            <a href="{{ route('admin.appointments') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Kembali
            </a>
        </div>
    </div>

        <!-- Patient Info -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                Informasi Pasien
                            </h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Nama:</span>
                                    <span class="info-value">{{ $appointment->patient->name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">NIK:</span>
                                    <span class="info-value">{{ $appointment->patient->nik ?? '-' }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Telepon:</span>
                                    <span class="info-value">{{ $appointment->patient->phone ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-section">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user-md me-2"></i>
                                Informasi Dokter
                            </h5>
                            <div class="info-grid">
                                <div class="info-item">
                                    <span class="info-label">Dokter:</span>
                                    <span class="info-value">{{ $appointment->doctor->name }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Spesialisasi:</span>
                                    <span class="info-value">{{ $appointment->doctor->specialty }}</span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label">Tanggal:</span>
                                    <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
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

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading">Terjadi kesalahan:</h6>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <form action="{{ route('admin.medical-records.store') }}" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
            
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-file-medical me-2"></i>
                        Form Rekam Medis
                    </h5>

                    <div class="row">
                        <!-- Keluhan -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Keluhan Pasien</label>
                            <textarea class="form-control" name="complaint" rows="4" placeholder="Masukkan keluhan yang disampaikan pasien..." required></textarea>
                        </div>

                        <!-- Diagnosa -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Diagnosa</label>
                            <textarea class="form-control" name="diagnosis" rows="3" placeholder="Masukkan diagnosa dokter..." required></textarea>
                        </div>

                        <!-- Tindakan -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Tindakan yang Dilakukan</label>
                            <textarea class="form-control" name="treatment" rows="3" placeholder="Masukkan tindakan medis yang dilakukan..." required></textarea>
                        </div>

                        <!-- Jenis Pelayanan -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Jenis Pelayanan <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="service-type-card" onclick="selectServiceType('rawat_jalan')">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" name="service_type" value="rawat_jalan" id="rawat_jalan" class="me-3" required>
                                            <div>
                                                <h6 class="mb-1">Rawat Jalan</h6>
                                                <small class="text-muted">Pasien dapat pulang setelah pemeriksaan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="service-type-card" onclick="selectServiceType('rawat_inap')">
                                        <div class="d-flex align-items-center">
                                            <input type="radio" name="service_type" value="rawat_inap" id="rawat_inap" class="me-3" required>
                                            <div>
                                                <h6 class="mb-1">Rawat Inap</h6>
                                                <small class="text-muted">Pasien memerlukan perawatan intensif</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resep Obat (untuk Rawat Jalan) -->
                        <div class="col-md-12 mb-4" id="prescription-section" style="display: none;">
                            <label class="form-label">Resep Obat</label>
                            <div class="alert alert-info mb-3">
                                <h6 class="mb-2"><i class="fas fa-lightbulb me-2"></i>Contoh Obat Umum:</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small><strong>Paracetamol:</strong> 500mg, 3x1 sehari</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small><strong>Amoxicillin:</strong> 250mg, 3x1 sehari</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small><strong>Ibuprofen:</strong> 400mg, 3x1 sehari</small>
                                    </div>
                                    <div class="col-md-3">
                                        <small><strong>Omeprazole:</strong> 20mg, 1x1 sebelum makan</small>
                                    </div>
                                </div>
                            </div>
                            <div id="medicines-container">
                                <div class="medicine-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Nama Obat</label>
                                            <input type="text" class="form-control" name="medicines[0][name]" placeholder="Nama obat">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Dosis</label>
                                            <input type="text" class="form-control" name="medicines[0][dosage]" placeholder="500mg, 250mg, 1 tablet">
                                            <small class="text-muted">Contoh: 500mg, 250mg</small>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label">Jumlah</label>
                                            <input type="number" class="form-control" name="medicines[0][quantity]" placeholder="10" min="1">
                                            <small class="text-muted">Jumlah total</small>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Cara Pakai</label>
                                            <input type="text" class="form-control" name="medicines[0][instructions]" placeholder="3x1 sehari setelah makan">
                                            <small class="text-muted">Contoh: 3x1 sehari, 2x1 sebelum makan</small>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMedicine(this)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="addMedicine()">
                                    <i class="fas fa-plus me-2"></i>Tambah Obat
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="addCommonMedicine('Paracetamol', '500mg', '3x1 sehari setelah makan')">
                                    <i class="fas fa-pills me-2"></i>Paracetamol
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="addCommonMedicine('Amoxicillin', '250mg', '3x1 sehari setelah makan')">
                                    <i class="fas fa-pills me-2"></i>Amoxicillin
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" onclick="addCommonMedicine('Ibuprofen', '400mg', '3x1 sehari setelah makan')">
                                    <i class="fas fa-pills me-2"></i>Ibuprofen
                                </button>
                            </div>
                        </div>

                        <!-- Surat Rujukan (untuk Rawat Inap) -->
                        <div class="col-md-12 mb-4" id="referral-section" style="display: none;">
                            <label class="form-label">Surat Rujukan</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Rujukan ke</label>
                                    <input type="text" class="form-control" name="referral_to" placeholder="Nama rumah sakit/klinik">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Alasan Rujukan</label>
                                    <input type="text" class="form-control" name="referral_reason" placeholder="Alasan rujukan">
                                </div>
                            </div>
                        </div>

                        <!-- Catatan Tambahan -->
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Masukkan catatan tambahan jika ada..."></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.appointments') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Simpan Rekam Medis
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    let medicineCount = 1;

    function selectServiceType(type) {
        // Remove selected class from all cards
        document.querySelectorAll('.service-type-card').forEach(card => {
            card.classList.remove('selected');
        });
        
        // Add selected class to clicked card
        event.currentTarget.classList.add('selected');
        
        // Check the radio button
        document.getElementById(type).checked = true;
        
        // Show/hide sections based on service type
        if (type === 'rawat_jalan') {
            document.getElementById('prescription-section').style.display = 'block';
            document.getElementById('referral-section').style.display = 'none';
        } else if (type === 'rawat_inap') {
            document.getElementById('prescription-section').style.display = 'none';
            document.getElementById('referral-section').style.display = 'block';
            
            // Clear medicines data when selecting rawat_inap
            clearMedicinesData();
        }
        
        console.log('Service type selected:', type);
    }
    
    function clearMedicinesData() {
        // Clear all medicine inputs
        const medicineInputs = document.querySelectorAll('#medicines-container input');
        medicineInputs.forEach(input => {
            input.value = '';
        });
        
        // Remove all medicine items except the first one
        const medicineItems = document.querySelectorAll('.medicine-item');
        for (let i = 1; i < medicineItems.length; i++) {
            medicineItems[i].remove();
        }
        
        // Reset medicine count
        medicineCount = 1;
    }

    function addMedicine() {
        const container = document.getElementById('medicines-container');
        const newMedicine = document.createElement('div');
        newMedicine.className = 'medicine-item border rounded p-3 mb-3';
        newMedicine.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Nama Obat</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][name]" placeholder="Nama obat">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dosis</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][dosage]" placeholder="500mg, 250mg, 1 tablet">
                    <small class="text-muted">Contoh: 500mg, 250mg</small>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="medicines[${medicineCount}][quantity]" placeholder="10" min="1">
                    <small class="text-muted">Jumlah total</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cara Pakai</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][instructions]" placeholder="3x1 sehari setelah makan">
                    <small class="text-muted">Contoh: 3x1 sehari, 2x1 sebelum makan</small>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMedicine(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newMedicine);
        medicineCount++;
    }

    function addCommonMedicine(name, dosage, instructions) {
        const container = document.getElementById('medicines-container');
        const newMedicine = document.createElement('div');
        newMedicine.className = 'medicine-item border rounded p-3 mb-3';
        newMedicine.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Nama Obat</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][name]" value="${name}" readonly>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dosis</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][dosage]" value="${dosage}">
                    <small class="text-muted">Contoh: 500mg, 250mg</small>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Jumlah</label>
                    <input type="number" class="form-control" name="medicines[${medicineCount}][quantity]" placeholder="10" min="1" value="10">
                    <small class="text-muted">Jumlah total</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cara Pakai</label>
                    <input type="text" class="form-control" name="medicines[${medicineCount}][instructions]" value="${instructions}">
                    <small class="text-muted">Contoh: 3x1 sehari, 2x1 sebelum makan</small>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="button" class="btn btn-outline-danger btn-sm w-100" onclick="removeMedicine(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(newMedicine);
        medicineCount++;
    }

    function removeMedicine(button) {
        button.closest('.medicine-item').remove();
    }

    function validateForm() {
        const serviceType = document.querySelector('input[name="service_type"]:checked');
        if (!serviceType) {
            alert('Pilih jenis pelayanan terlebih dahulu!');
            return false;
        }

        if (serviceType.value === 'rawat_jalan') {
            // Check if at least one medicine is filled
            const medicineInputs = document.querySelectorAll('#medicines-container input[name*="[name]"]');
            let hasValidMedicine = false;
            
            medicineInputs.forEach(input => {
                if (input.value.trim() !== '') {
                    hasValidMedicine = true;
                }
            });
            
            if (!hasValidMedicine) {
                alert('Untuk Rawat Jalan, minimal isi satu obat!');
                return false;
            }
        }

        // Debug: Log form data
        const formData = new FormData(document.querySelector('form'));
        console.log('Form data being submitted:');
        for (let [key, value] of formData.entries()) {
            console.log(key, value);
        }

        return true;
    }
</script>
@endsection
