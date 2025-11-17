<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Dokter - {{ $startDate }} s/d {{ $endDate }}</title>
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

        .doctors-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .doctors-table th {
            background: #2c3e50;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .doctors-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
            vertical-align: top;
        }

        .doctors-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .doctors-table tr:hover {
            background: #e9ecef;
        }

        .doctor-name {
            font-weight: bold;
            color: #2c3e50;
        }

        .specialty {
            color: #6c757d;
            font-style: italic;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .schedule-info {
            font-size: 10px;
            color: #6c757d;
            max-width: 150px;
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

        .page-break {
            page-break-before: always;
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
    <div class="report-title">LAPORAN DATA DOKTER</div>

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
            <span class="info-label">Total Dokter:</span>
            <span class="info-value">{{ $doctors->count() }} dokter</span>
        </div>
        <div class="info-row">
            <span class="info-label">Dokter Aktif:</span>
            <span class="info-value">{{ $doctors->where('is_active', true)->count() }} dokter</span>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-number">{{ $doctors->count() }}</div>
            <div class="stat-label">Total Dokter</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $doctors->where('is_active', true)->count() }}</div>
            <div class="stat-label">Dokter Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $doctors->where('is_active', false)->count() }}</div>
            <div class="stat-label">Dokter Non-Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $doctors->pluck('specialty')->unique()->count() }}</div>
            <div class="stat-label">Spesialisasi</div>
        </div>
    </div>

    <!-- Doctors Table -->
    @if($doctors->count() > 0)
    <table class="doctors-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Nama Dokter</th>
                <th width="15%">Spesialisasi</th>
                <th width="15%">Kontak</th>
                <th width="20%">Jadwal Praktik</th>
                <th width="10%">Status</th>
                <th width="15%">Tanggal Bergabung</th>
            </tr>
        </thead>
        <tbody>
            @foreach($doctors as $index => $doctor)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="doctor-name">{{ $doctor->name }}</div>
                </td>
                <td>
                    <div class="specialty">{{ $doctor->specialty }}</div>
                </td>
                <td>
                    <div style="font-size: 10px;">
                        <div>{{ $doctor->phone ?? 'Tidak tersedia' }}</div>
                        <div style="color: #6c757d;">{{ $doctor->email ?? 'Tidak tersedia' }}</div>
                    </div>
                </td>
                <td>
                    <div class="schedule-info">
                        @if($doctor->schedule)
                            @if(is_array($doctor->schedule))
                                @foreach($doctor->schedule as $day => $times)
                                    <div><strong>{{ ucfirst($day) }}:</strong> {{ $times['start'] ?? '' }}-{{ $times['end'] ?? '' }}</div>
                                @endforeach
                            @else
                                {{ $doctor->schedule }}
                            @endif
                        @else
                            <span style="color: #6c757d; font-style: italic;">Belum diatur</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($doctor->is_active)
                        <span class="status-active">Aktif</span>
                    @else
                        <span class="status-inactive">Non-Aktif</span>
                    @endif
                </td>
                <td>
                    <div style="font-size: 10px;">
                        {{ \Carbon\Carbon::parse($doctor->created_at)->format('d/m/Y') }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h4>Tidak Ada Data Dokter</h4>
        <p>Belum ada data dokter dalam periode yang dipilih.</p>
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

