<div class="appointment-detail-container">
    <style>
        .appointment-detail-container {
            padding: 20px;
        }
        .appointment-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        .appointment-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .appointment-date {
            opacity: 0.9;
        }
        .info-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }
        .info-title {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
        }
        .info-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 12px;
        }
        .info-label {
            font-weight: 600;
            color: #64748b;
        }
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fbbf24;
        }
        .status-diterima {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #60a5fa;
        }
        .status-selesai {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }
        .status-batal {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
    </style>

    <!-- Appointment Header -->
    <div class="appointment-header">
        <div class="appointment-number">
            <i class="fas fa-calendar-check me-2"></i>
            Janji Temu #{{ $appointment->queue_number ?? '-' }}
        </div>
        <div class="appointment-date">
            <i class="fas fa-clock me-2"></i>
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, d F Y') }} 
            at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }} WIB
        </div>
        <div class="mt-3">
            <span class="status-badge status-{{ strtolower(str_replace(' ', '', $appointment->status)) }}">
                {{ $appointment->status }}
            </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Patient Information -->
            <div class="info-section">
                <div class="info-title">
                    <i class="fas fa-user me-2"></i>
                    Informasi Pasien
                </div>
                <div class="info-grid">
                    <div class="info-label">Nama:</div>
                    <div class="info-value">{{ $appointment->patient->name }}</div>
                    
                    <div class="info-label">No. Telepon:</div>
                    <div class="info-value">{{ $appointment->patient->phone }}</div>
                    
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $appointment->patient->email }}</div>
                    
                    <div class="info-label">Alamat:</div>
                    <div class="info-value">{{ $appointment->patient->address }}</div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Doctor Information -->
            <div class="info-section">
                <div class="info-title">
                    <i class="fas fa-user-md me-2"></i>
                    Informasi Dokter
                </div>
                <div class="info-grid">
                    <div class="info-label">Nama Dokter:</div>
                    <div class="info-value">{{ $appointment->doctor->name }}</div>
                    
                    <div class="info-label">Spesialisasi:</div>
                    <div class="info-value">{{ $appointment->doctor->specialty }}</div>
                </div>
            </div>
        </div>
    </div>

    @if($appointment->complaint)
    <!-- Complaint Information -->
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-comment-medical me-2"></i>
            Keluhan Pasien
        </div>
        <div class="info-value">
            {{ $appointment->complaint }}
        </div>
    </div>
    @endif

    <!-- Appointment Details -->
    <div class="info-section">
        <div class="info-title">
            <i class="fas fa-info-circle me-2"></i>
            Detail Janji Temu
        </div>
        <div class="info-grid">
            <div class="info-label">Tanggal:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</div>
            
            <div class="info-label">Waktu:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }} WIB</div>
            
            <div class="info-label">No. Antrian:</div>
            <div class="info-value">{{ $appointment->queue_number ?? '-' }}</div>
            
            <div class="info-label">Dibuat Pada:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($appointment->created_at)->format('d F Y H:i') }} WIB</div>
        </div>
    </div>
</div>

