<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
            position: relative;
        }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            font-weight: bold;
            opacity: 0.1;
            color: {{ $invoice->status == 'Lunas' ? 'green' : 'red' }};
            z-index: -1;
            pointer-events: none;
            white-space: nowrap;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .clinic-address {
            font-size: 10px;
            color: #666;
        }
        .invoice-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        .invoice-number {
            text-align: right;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .patient-info {
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .billing-table th,
        .billing-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .billing-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .total-section {
            margin-left: auto;
            width: 300px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total-label {
            font-weight: bold;
        }
        .grand-total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 5px;
        }
        .status {
            text-align: center;
            margin: 20px 0;
            font-weight: bold;
            font-size: 14px;
        }
        .status.paid {
            color: green;
        }
        .status.unpaid {
            color: red;
        }
        .date {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">{{ $invoice->status == 'Lunas' ? 'LUNAS' : 'BELUM LUNAS' }}</div>
    
    <div class="header">
        <div class="clinic-name">KLINIK ALYA MEDIKA</div>
        <div class="clinic-address">Jl. Garuda Sakti No.km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau<br>Telp: (021) 1234-5678</div>
    </div>

    <div class="invoice-title">INVOICE</div>

    <div class="invoice-number">
        No. Invoice: #{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
    </div>

    <div class="patient-info">
        <div class="info-row">
            <div class="info-label">Nama Pasien:</div>
            <div>{{ $invoice->patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal:</div>
            <div>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dokter:</div>
            <div>{{ $invoice->medicalRecord->doctor->name }} - {{ $invoice->medicalRecord->doctor->specialty }}</div>
        </div>
    </div>

    <table class="billing-table">
        <thead>
            <tr>
                <th style="width: 60%;">Deskripsi</th>
                <th style="width: 40%; text-align: right;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Biaya Konsultasi</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->consultation_fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Obat-obatan</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->medication_fee, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Biaya Tindakan</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->treatment_fee, 0, ',', '.') }}</td>
            </tr>
            @if($invoice->other_fee > 0)
            <tr>
                <td>Biaya Lain-lain</td>
                <td style="text-align: right;">Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <span class="total-label">Total:</span>
            <span>Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="status {{ $invoice->status == 'Lunas' ? 'paid' : 'unpaid' }}">
        Status: {{ $invoice->status }}
    </div>

    <div class="date">
        Pekanbaru, {{ \Carbon\Carbon::now()->format('d F Y') }}
    </div>
</body>
</html>


