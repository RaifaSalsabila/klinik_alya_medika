<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $startDate }} s/d {{ $endDate }}</title>
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

        .revenue-breakdown {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .breakdown-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
        }

        .breakdown-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }

        .breakdown-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .breakdown-item:last-child {
            border-bottom: none;
            font-weight: bold;
            background: #e9ecef;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 8px 8px;
        }

        .breakdown-label {
            color: #495057;
            font-size: 11px;
        }

        .breakdown-value {
            color: #212529;
            font-weight: 500;
            font-size: 11px;
        }

        .invoices-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .invoices-table th {
            background: #2c3e50;
            color: white;
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .invoices-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
            vertical-align: top;
        }

        .invoices-table tr:nth-child(even) {
            background: #f8f9fa;
        }

        .invoice-number {
            font-weight: bold;
            color: #2c3e50;
            font-size: 10px;
        }

        .patient-name {
            color: #495057;
            font-weight: 500;
        }

        .amount {
            color: #28a745;
            font-weight: bold;
            text-align: right;
        }

        .status-lunas {
            background: #d4edda;
            color: #155724;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-belum-lunas {
            background: #fff3cd;
            color: #856404;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
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

        .currency {
            font-family: 'Courier New', monospace;
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
    <div class="report-title">LAPORAN PENDAPATAN</div>

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
            <span class="info-label">Total Invoice:</span>
            <span class="info-value">{{ $invoices->count() }} invoice</span>
        </div>
        <div class="info-row">
            <span class="info-label">Filter:</span>
            <span class="info-value">{{ ucfirst($filter) }}</span>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-card">
            <div class="stat-number currency">{{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $invoices->where('status', 'Lunas')->count() }}</div>
            <div class="stat-label">Invoice Lunas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $invoices->where('status', 'Belum Lunas')->count() }}</div>
            <div class="stat-label">Belum Lunas</div>
        </div>
        <div class="stat-card">
            <div class="stat-number currency">{{ number_format($invoices->where('status', 'Lunas')->sum('total_amount'), 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Lunas</div>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="revenue-breakdown">
        <div class="breakdown-card">
            <div class="breakdown-title">Rincian Biaya</div>
            <div class="breakdown-item">
                <span class="breakdown-label">Konsultasi:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->sum('consultation_fee'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Obat-obatan:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->sum('medication_fee'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Perawatan:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->sum('treatment_fee'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Lain-lain:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->sum('other_fee'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">TOTAL:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="breakdown-card">
            <div class="breakdown-title">Status Pembayaran</div>
            <div class="breakdown-item">
                <span class="breakdown-label">Lunas:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->where('status', 'Lunas')->sum('total_amount'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Belum Lunas:</span>
                <span class="breakdown-value currency">Rp {{ number_format($invoices->where('status', 'Belum Lunas')->sum('total_amount'), 0, ',', '.') }}</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Persentase Lunas:</span>
                <span class="breakdown-value">{{ $invoices->count() > 0 ? number_format(($invoices->where('status', 'Lunas')->count() / $invoices->count()) * 100, 1) : 0 }}%</span>
            </div>
            <div class="breakdown-item">
                <span class="breakdown-label">Rata-rata per Invoice:</span>
                <span class="breakdown-value currency">Rp {{ $invoices->count() > 0 ? number_format($invoices->avg('total_amount'), 0, ',', '.') : 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    @if($invoices->count() > 0)
    <table class="invoices-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">No. Invoice</th>
                <th width="20%">Pasien</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Total</th>
                <th width="10%">Status</th>
                <th width="20%">Rincian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $index => $invoice)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <div class="invoice-number">#{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</div>
                </td>
                <td>
                    <div class="patient-name">{{ $invoice->patient->name }}</div>
                </td>
                <td>
                    <div style="font-size: 10px;">
                        {{ \Carbon\Carbon::parse($invoice->created_at)->format('d/m/Y') }}
                    </div>
                </td>
                <td>
                    <div class="amount currency">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
                </td>
                <td>
                    @if($invoice->status == 'Lunas')
                        <span class="status-lunas">Lunas</span>
                    @else
                        <span class="status-belum-lunas">Belum Lunas</span>
                    @endif
                </td>
                <td>
                    <div style="font-size: 10px; color: #6c757d;">
                        <div>Konsultasi: Rp {{ number_format($invoice->consultation_fee, 0, ',', '.') }}</div>
                        <div>Obat: Rp {{ number_format($invoice->medication_fee, 0, ',', '.') }}</div>
                        <div>Perawatan: Rp {{ number_format($invoice->treatment_fee, 0, ',', '.') }}</div>
                        @if($invoice->other_fee > 0)
                        <div>Lain-lain: Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <h4>Tidak Ada Data Invoice</h4>
        <p>Belum ada data invoice dalam periode yang dipilih.</p>
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

