<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Appointment - {{ $startDate }} s/d {{ $endDate }}</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', serif;
            margin: 0;
            padding: 0;
            background: white;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }

        .clinic-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .clinic-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .clinic-address {
            font-size: 11px;
            color: #666;
            margin-bottom: 8px;
        }

        .clinic-contact {
            font-size: 10px;
            color: #888;
        }

        .report-title {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin: 25px 0;
            color: #2c3e50;
            text-decoration: underline;
            text-underline-offset: 5px;
        }

        .report-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2c3e50;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
            color: #495057;
        }

        .info-value {
            color: #212529;
            flex: 1;
        }

        .summary-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .appointments-table th {
            background: #2c3e50;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appointments-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
            vertical-align: top;
        }

        .appointments-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .patient-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .doctor-name {
            color: #495057;
            font-weight: 500;
        }

        .appointment-date {
            color: #6c757d;
            font-size: 10px;
        }

        .status-menunggu {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-diterima {
            background: #d1ecf1;
            color: #0c5460;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-selesai {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-batal {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .queue-number {
            background: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
        }

        .complaint-text {
            max-width: 200px;
            font-size: 10px;
            color: #6c757d;
            line-height: 1.3;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            padding-top: 20px;
        }

        .footer-info {
            font-size: 10px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 8px;
            height: 40px;
        }

        .signature-text {
            font-size: 11px;
            color: #666;
            font-weight: bold;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="clinic-name">KLINIK ALYA MEDIKA</div>
        <div class="clinic-subtitle">Pusat Pelayanan Kesehatan Terpercaya</div>
        <div class="clinic-address">
            Jl. Garuda Sakti No.km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau<br>
            Telp: (021) 1234-5678 | Email: info@klinikalya.com
        </div>
        <div class="clinic-contact">
            Website: www.klinikalya.com | Jam Operasional: 08:00 - 17:00 WIB
        </div>
    </div>

    <!-- Report Title -->
    <div class="report-title">LAPORAN APPOINTMENT</div>

    <!-- Report Info -->
    <div class="report-info">
        <div class="info-row">
            <span class="info-label">Periode:</span>
            <span class="info-value">{{ \Carbon\Carbon::parse($startDate)->format('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Generate:</span>
            <span class="info-value">{{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Appointment:</span>
            <span class="info-value">{{ $appointments->count() }} appointment</span>
        </div>
        <div class="info-row">
            <span class="info-label">Filter:</span>
            <span class="info-value">{{ ucfirst($filter) }}</span>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-number">{{ $appointments->count() }}</div>
            <div class="stat-label">Total Appointment</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $appointments->where('status', 'Menunggu')->count() }}</div>
            <div class="stat-label">Menunggu</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $appointments->where('status', 'Diterima')->count() }}</div>
            <div class="stat-label">Diterima</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $appointments->where('status', 'Selesai')->count() }}</div>
            <div class="stat-label">Selesai</div>
        </div>
    </div>

    <!-- Appointments Table -->
    @if($appointments->count() > 0)
    <table class="appointments-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Pasien</th>
                <th width="15%">Dokter</th>
                <th width="12%">Tanggal</th>
                <th width="8%">Waktu</th>
                <th width="8%">Antrian</th>
                <th width="10%">Status</th>
                <th width="27%">Keluhan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $index => $appointment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="patient-name">{{ $appointment->patient->name }}</div>
                    <div style="font-size: 10px; color: #6c757d;">{{ $appointment->patient->phone ?? 'Tidak tersedia' }}</div>
                </td>
                <td>
                    <div class="doctor-name">{{ $appointment->doctor->name }}</div>
                    <div style="font-size: 10px; color: #6c757d;">{{ $appointment->doctor->specialty }}</div>
                </td>
                <td>
                    <div class="appointment-date">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                    </div>
                </td>
                <td>
                    <div style="font-size: 10px;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                    </div>
                </td>
                <td>
                    <span class="queue-number">#{{ $appointment->queue_number }}</span>
                </td>
                <td>
                    @switch($appointment->status)
                        @case('Menunggu')
                            <span class="status-menunggu">Menunggu</span>
                            @break
                        @case('Diterima')
                            <span class="status-diterima">Diterima</span>
                            @break
                        @case('Selesai')
                            <span class="status-selesai">Selesai</span>
                            @break
                        @case('Batal')
                            <span class="status-batal">Batal</span>
                            @break
                        @default
                            <span class="status-menunggu">{{ $appointment->status }}</span>
                    @endswitch
                </td>
                <td>
                    <div class="complaint-text">
                        {{ Str::limit($appointment->complaint, 100) }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h4>Tidak Ada Data Appointment</h4>
        <p>Belum ada data appointment dalam periode yang dipilih.</p>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-info">
            <strong>Dicetak:</strong> {{ \Carbon\Carbon::now()->format('d F Y, H:i') }} WIB | 
            <strong>Dokumen sah digital</strong>
        </div>
        
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-text">Admin Klinik</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-text">Direktur Klinik</div>
            </div>
        </div>
    </div>
</body>
</html>

