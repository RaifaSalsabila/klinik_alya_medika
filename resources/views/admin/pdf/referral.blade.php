<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Rujukan Rawat Inap</title>
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
        .referral-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
        }
        .referral-number {
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
        .content {
            margin: 20px 0;
            text-align: justify;
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

    <div class="referral-title">SURAT RUJUKAN RAWAT INAP</div>

    <div class="referral-number">
        No. Rujukan: {{ $referralNumber }}
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

    <div class="content">
        <p>Dengan hormat,</p>
        
        <p>Berdasarkan pemeriksaan yang telah dilakukan pada pasien di atas, kami memerlukan rujukan untuk rawat inap dengan pertimbangan sebagai berikut:</p>
        
        <p><strong>Keluhan:</strong> {{ $medicalRecord->complaint }}</p>
        <p><strong>Diagnosa:</strong> {{ $medicalRecord->diagnosis }}</p>
        <p><strong>Tindakan yang telah dilakukan:</strong> {{ $medicalRecord->treatment }}</p>
        
        <p><strong>Rujukan ke:</strong> {{ $medicalRecord->referralLetter->referred_to ?? '-' }}</p>
        <p><strong>Alasan rujukan:</strong> {{ $medicalRecord->referralLetter->reason ?? '-' }}</p>
        
        <p>Demikian surat rujukan ini kami buat untuk dapat dipergunakan seperlunya.</p>
    </div>

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


