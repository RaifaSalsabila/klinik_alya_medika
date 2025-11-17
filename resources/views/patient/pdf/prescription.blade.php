<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resep Obat - {{ $record->patient->name }}</title>
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
            line-height: 1.3;
            font-size: 12px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
        }
        
        .clinic-name {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        
        .clinic-subtitle {
            font-size: 11px;
            color: #666;
            margin-bottom: 3px;
        }
        
        .clinic-address {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .clinic-contact {
            font-size: 9px;
            color: #888;
        }
        
        .prescription-title {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
            color: #2c3e50;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
        
        .patient-info {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 15px;
            border-left: 3px solid #2c3e50;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            width: 100px;
            color: #495057;
            font-size: 11px;
        }
        
        .info-value {
            color: #212529;
            font-size: 11px;
            flex: 1;
        }
        
        .medicine-list {
            margin-top: 15px;
        }
        
        .medicine-item {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            background: white;
        }
        
        .medicine-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            padding-bottom: 5px;
            border-bottom: 1px solid #eee;
        }
        
        .medicine-number {
            background: #2c3e50;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 8px;
            font-size: 10px;
        }
        
        .medicine-name {
            font-weight: bold;
            font-size: 13px;
            color: #2c3e50;
        }
        
        .medicine-details {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 8px;
            margin-top: 8px;
        }
        
        .detail-item {
            background: #f8f9fa;
            padding: 6px;
            border-radius: 3px;
            border: 1px solid #e9ecef;
        }
        
        .detail-label {
            font-size: 9px;
            color: #6c757d;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            margin-bottom: 2px;
        }
        
        .detail-value {
            font-size: 11px;
            color: #212529;
            font-weight: 500;
        }
        
        .footer {
            margin-top: 25px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            padding-top: 15px;
        }
        
        .doctor-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .signature-box {
            text-align: center;
            width: 150px;
        }
        
        .signature-line {
            border-bottom: 1px solid #333;
            margin-bottom: 5px;
            height: 30px;
        }
        
        .signature-text {
            font-size: 10px;
            color: #666;
            font-weight: bold;
        }
        
        .date-info {
            text-align: right;
            margin-top: 15px;
            font-size: 9px;
            color: #888;
        }
        
        .prescription-number {
            background: #2c3e50;
            color: white;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .warning-box {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 3px;
            padding: 8px;
            margin: 10px 0;
            font-size: 10px;
            color: #856404;
        }
        
        .warning-title {
            font-weight: bold;
            margin-bottom: 3px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="clinic-name">KLINIK ALYA MEDIKA</div>
        <div class="clinic-subtitle">Pusat Pelayanan Kesehatan Terpercaya</div>
        <div class="clinic-address">Jl. Garuda Sakti No.km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau</div>
        <div class="clinic-contact">Telp: (021) 123-4567 | Email: info@klinikalya.com</div>
    </div>

    <div class="prescription-title">RESEP OBAT</div>
    
    <div class="prescription-number">
        No. Resep: {{ str_pad($record->id, 6, '0', STR_PAD_LEFT) }}/{{ date('Y') }}
    </div>

    <div class="patient-info">
        <div class="info-row">
            <div class="info-label">Nama Pasien:</div>
            <div class="info-value">{{ $record->patient->name }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIK:</div>
            <div class="info-value">{{ $record->patient->nik }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Resep:</div>
            <div class="info-value">{{ \Carbon\Carbon::parse($record->created_at)->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Dokter:</div>
            <div class="info-value">{{ $record->doctor->name }} - {{ $record->doctor->specialty }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">No. SIP:</div>
            <div class="info-value">SIP.{{ str_pad($record->doctor->id, 6, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <div class="medicine-list">
        @if($prescription && count($prescription) > 0)
            @php $counter = 1; @endphp
            @foreach($prescription as $index => $medicine)
                @if(!empty($medicine['name']))
                <div class="medicine-item">
                    <div class="medicine-header">
                        <div class="medicine-number">{{ $counter }}</div>
                        <div class="medicine-name">{{ $medicine['name'] }}</div>
                    </div>
                    <div class="medicine-details">
                        <div class="detail-item">
                            <div class="detail-label">Dosis</div>
                            <div class="detail-value">{{ $medicine['dosage'] ?? 'Tidak diisi' }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Jumlah</div>
                            <div class="detail-value">{{ $medicine['quantity'] ?? '0' }} tablet/kapsul</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Cara Pakai</div>
                            <div class="detail-value">{{ $medicine['instructions'] ?? 'Tidak diisi' }}</div>
                        </div>
                    </div>
                </div>
                @php $counter++; @endphp
                @endif
            @endforeach
        @else
            <div class="medicine-item">
                <div style="text-align: center; color: #6c757d; padding: 30px; font-style: italic;">
                    Tidak ada resep obat yang diisi
                </div>
            </div>
        @endif
    </div>

    <div class="warning-box">
        <div class="warning-title">⚠️ PERHATIAN:</div>
        <div>• Konsumsi obat sesuai dosis yang dianjurkan</div>
        <div>• Simpan di tempat kering dan sejuk</div>
        <div>• Jauhkan dari jangkauan anak-anak</div>
        <div>• Jika efek samping, hubungi dokter</div>
    </div>

    <div class="footer">
        <div class="doctor-signature">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-text">Dokter Pemberi Resep</div>
                <div style="font-size: 10px; color: #999; margin-top: 5px;">
                    {{ $record->doctor->name }}<br>
                    {{ $record->doctor->specialty }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-text">Apoteker</div>
                <div style="font-size: 10px; color: #999; margin-top: 5px;">
                    Nama Apoteker<br>
                    SIPA: 123456789
                </div>
            </div>
        </div>
        
        <div class="date-info">
            <strong>Dicetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB<br>
            <strong>Dokumen sah digital</strong>
        </div>
    </div>
</body>
</html>
