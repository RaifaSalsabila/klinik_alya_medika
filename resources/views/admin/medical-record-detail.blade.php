<div class="medical-record-detail">
    <!-- Patient & Doctor Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="info-card">
                <h6 class="info-title">
                    <i class="fas fa-user me-2"></i>
                    Informasi Pasien
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nama:</span>
                        <span class="info-value">{{ $record->patient->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIK:</span>
                        <span class="info-value">{{ $record->patient->nik ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Telepon:</span>
                        <span class="info-value">{{ $record->patient->phone ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Alamat:</span>
                        <span class="info-value">{{ $record->patient->address ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="info-card">
                <h6 class="info-title">
                    <i class="fas fa-user-md me-2"></i>
                    Informasi Dokter
                </h6>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Dokter:</span>
                        <span class="info-value">{{ $record->doctor->name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Spesialisasi:</span>
                        <span class="info-value">{{ $record->doctor->specialty }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($record->created_at)->format('d F Y H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Pelayanan:</span>
                        <span class="service-badge service-{{ $record->service_type }}">
                            @if($record->service_type == 'rawat_jalan')
                                Rawat Jalan
                            @else
                                Rawat Inap
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical Information -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h6 class="info-title">
                    <i class="fas fa-file-medical me-2"></i>
                    Informasi Medis
                </h6>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Keluhan Pasien:</label>
                        <div class="medical-content">{{ $record->complaint }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Diagnosa:</label>
                        <div class="medical-content">{{ $record->diagnosis }}</div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Tindakan yang Dilakukan:</label>
                        <div class="medical-content">{{ $record->treatment }}</div>
                    </div>
                    @if($record->notes)
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Catatan Tambahan:</label>
                        <div class="medical-content">{{ $record->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Prescription (for Rawat Jalan) -->
    @if($record->service_type == 'rawat_jalan' && $record->prescription_data)
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h6 class="info-title">
                    <i class="fas fa-prescription me-2"></i>
                    Resep Obat
                </h6>
                <div class="prescription-info">
                    <div class="prescription-header mb-3">
                        <span class="prescription-number">Resep Obat</span>
                        <span class="prescription-date">{{ \Carbon\Carbon::parse($record->created_at)->format('d F Y') }}</span>
                    </div>
                    <div class="prescription-list">
                        @if($record->prescription_data)
                            @php
                                // Decode JSON prescription data from prescription_data field
                                $medicines = json_decode($record->prescription_data, true);
                            @endphp
                            
                            @if($medicines && is_array($medicines) && count($medicines) > 0)
                                @foreach($medicines as $index => $medicine)
                                    @if(!empty($medicine['name']))
                                    <div class="medicine-item">
                                        <div class="medicine-header">
                                            <span class="medicine-number">{{ $index + 1 }}.</span>
                                            <span class="medicine-name">{{ $medicine['name'] }}</span>
                                            <span class="medicine-dosage">{{ $medicine['dosage'] ?? 'Dosis tidak diisi' }}</span>
                                        </div>
                                        <div class="medicine-details">
                                            <div class="medicine-quantity">
                                                <i class="fas fa-capsules me-1"></i>
                                                Jumlah: {{ $medicine['quantity'] ?? '0' }} tablet/kapsul
                                            </div>
                                            <div class="medicine-instructions">
                                                <i class="fas fa-info-circle me-1"></i>
                                                {{ $medicine['instructions'] ?? 'Cara pakai tidak diisi' }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="prescription-text">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Tidak ada resep obat yang diisi
                                </div>
                            @endif
                        @else
                            <div class="prescription-text">
                                <i class="fas fa-info-circle me-2"></i>
                                Resep obat tidak tersedia
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Referral Letter (for Rawat Inap) -->
    @if($record->service_type == 'rawat_inap' && isset($record->referralLetter))
    <div class="row mb-4">
        <div class="col-12">
            <div class="info-card">
                <h6 class="info-title">
                    <i class="fas fa-file-medical-alt me-2"></i>
                    Surat Rujukan
                </h6>
                <div class="referral-info">
                    <div class="referral-header mb-3">
                        <span class="referral-number">Surat Rujukan</span>
                        <span class="referral-date">{{ \Carbon\Carbon::parse($record->created_at)->format('d F Y') }}</span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rujukan ke:</label>
                            <div class="referral-content">{{ $record->referralLetter->referred_to ?? '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Alasan Rujukan:</label>
                            <div class="referral-content">{{ $record->referralLetter->reason ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.medical-record-detail {
    padding: 20px;
}

.info-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 20px;
    border: 1px solid #e2e8f0;
}

.info-title {
    color: #3b82f6;
    font-weight: 600;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e2e8f0;
}

.info-grid {
    display: grid;
    gap: 12px;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #e2e8f0;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #64748b;
    min-width: 100px;
}

.info-value {
    color: #1e293b;
    font-weight: 500;
}

.medical-content {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    min-height: 60px;
    white-space: pre-wrap;
}

.prescription-header, .referral-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: white;
    padding: 12px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
}

.prescription-number, .referral-number {
    font-weight: 600;
    color: #3b82f6;
}

.prescription-date, .referral-date {
    color: #64748b;
    font-size: 0.9rem;
}

.prescription-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.medicine-item {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px;
    transition: all 0.2s ease;
}

.medicine-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
}

.medicine-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.medicine-number {
    background: #3b82f6;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

.medicine-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 1rem;
}

.medicine-dosage {
    background: #f1f5f9;
    color: #475569;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85rem;
    font-weight: 500;
}

.medicine-details {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-left: 36px;
}

.medicine-quantity {
    color: #64748b;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
}

.medicine-instructions {
    color: #475569;
    font-size: 0.9rem;
    display: flex;
    align-items: flex-start;
    line-height: 1.4;
}

.prescription-text {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    min-height: 60px;
    white-space: pre-wrap;
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
    line-height: 1.5;
}

.referral-content {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    min-height: 50px;
}

.service-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.service-rawat-jalan {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #166534;
    border: 1px solid #86efac;
}

.service-rawat-inap {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
    border: 1px solid #f59e0b;
}
</style>

