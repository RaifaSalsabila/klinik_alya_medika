<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep Dokter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            line-height: 1.4;
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
        .prescription-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
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
        .prescription-number {
            text-align: right;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .medicines-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .medicines-table th,
        .medicines-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .medicines-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .doctor-signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            width: 200px;
            margin-left: auto;
            margin-top: 30px;
        }
        .date {
            text-align: right;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="clinic-name">KLINIK ALYA MEDIKA</div>
        <div class="clinic-address">Jl. Garuda Sakti No.km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau<br>Telp: (021) 1234-5678</div>
    </div>

    <div class="prescription-title">RESEP DOKTER</div>

    <div class="prescription-number">
        No. Resep: {{ $prescriptionNumber }}
    </div>

    <div class="patient-info">
        <div class="info-row">
            <div class="info-label">Nama Pasien:</div>
            <div>{{ $medicalRecord->patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Lahir:</div>
            <div>{{ \Carbon\Carbon::parse($medicalRecord->patient->created_at)->format('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Alamat:</div>
            <div>{{ $medicalRecord->patient->address ?? '-' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dokter:</div>
            <div>{{ $medicalRecord->doctor->name }} - {{ $medicalRecord->doctor->specialty }}</div>
        </div>
    </div>

    <table class="medicines-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Obat</th>
                <th style="width: 20%;">Dosis</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 35%;">Cara Pakai</th>
            </tr>
        </thead>
        <tbody>
            @if($prescriptionData && is_array($prescriptionData))
                @foreach($prescriptionData as $index => $medicine)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $medicine['name'] ?? '-' }}</td>
                    <td>{{ $medicine['dosage'] ?? '-' }}</td>
                    <td>{{ $medicine['quantity'] ?? '-' }}</td>
                    <td>{{ $medicine['instructions'] ?? '-' }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada resep obat</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="doctor-signature">
        <div>Dokter yang Memeriksa</div>
        <div class="signature-line"></div>
        <div style="margin-top: 5px;">{{ $medicalRecord->doctor->name }}</div>
        <div>{{ $medicalRecord->doctor->specialty }}</div>
    </div>

    <div class="date">
        Pekanbaru, {{ \Carbon\Carbon::now()->format('d F Y') }}
    </div>
</body>
</html>
