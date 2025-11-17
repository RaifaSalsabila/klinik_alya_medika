@extends('admin.includes.header')

@section('title', 'Laporan Klinik')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chart-line text-primary me-2"></i>
                    Laporan Klinik
                </h1>
                <p class="page-subtitle">Generate dan download laporan klinik secara otomatis</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="showQuickStats()">
                    <i class="fas fa-chart-bar me-1"></i>
                    Quick Stats
                </button>
            
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Quick Stats -->
    <div class="row mb-4" id="quickStats" style="display: none;">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Doctor::where('is_active', true)->count() }}</div>
                    <div class="stat-label">Dokter Aktif</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</div>
                    <div class="stat-label">Appointment Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-file-medical"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ \App\Models\MedicalRecord::whereDate('created_at', today())->count() }}</div>
                    <div class="stat-label">Rekam Medis Hari Ini</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number stat-revenue">{{ \App\Models\Invoice::where('status', 'Lunas')->sum('total_amount') }}</div>
                    <div class="stat-label">Total Pendapatan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Types -->
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="report-card h-100" onclick="showReportForm('doctors')">
                <div class="report-header">
                    <div class="report-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <div class="report-badge">
                        <span class="badge bg-primary">{{ \App\Models\Doctor::count() }}</span>
                    </div>
                </div>
                <div class="report-body">
                    <h5 class="report-title">Laporan Dokter</h5>
                    <p class="report-description">
                        Data lengkap dokter, jadwal praktik, spesialisasi, dan statistik pelayanan
                    </p>
                    <div class="report-features">
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Data Dokter
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Jadwal Praktik
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Statistik Pelayanan
                        </span>
                    </div>
                </div>
                <div class="report-footer">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Generate
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="report-card h-100" onclick="showReportForm('appointments')">
                <div class="report-header">
                    <div class="report-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="report-badge">
                        <span class="badge bg-success">{{ \App\Models\Appointment::count() }}</span>
                    </div>
                </div>
                <div class="report-body">
                    <h5 class="report-title">Laporan Appointment</h5>
                    <p class="report-description">
                        Data kunjungan pasien, status appointment, antrian, dan analisis pola kunjungan
                    </p>
                    <div class="report-features">
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Data Kunjungan
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Status Appointment
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Analisis Antrian
                        </span>
                    </div>
                </div>
                <div class="report-footer">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Generate
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="report-card h-100" onclick="showReportForm('revenue')">
                <div class="report-header">
                    <div class="report-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="report-badge">
                        <span class="badge bg-warning">{{ \App\Models\Invoice::count() }}</span>
                    </div>
                </div>
                <div class="report-body">
                    <h5 class="report-title">Laporan Pendapatan</h5>
                    <p class="report-description">
                        Data pendapatan, invoice, analisis keuangan, dan tren pendapatan bulanan
                    </p>
                    <div class="report-features">
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Data Pendapatan
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Analisis Invoice
                        </span>
                        <span class="feature-item">
                            <i class="fas fa-check text-success me-1"></i>
                            Tren Keuangan
                        </span>
                    </div>
                </div>
                <div class="report-footer">
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i>
                        Generate
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Form -->
    <div class="card shadow-sm" id="reportForm" style="display: none;">
        <div class="card-header bg-primary text-white">
            <h5 class="card-title mb-0">
                <i class="fas fa-file-alt me-2"></i>
                Generate Laporan
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reports.generate') }}" method="POST" id="reportFormElement">
                @csrf
                <input type="hidden" name="report_type" id="reportType">
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-start me-1"></i>
                            Tanggal Mulai
                        </label>
                        <input type="date" class="form-control" name="start_date" id="startDate" required>
                        <small class="text-muted">Pilih tanggal mulai periode laporan</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-calendar-end me-1"></i>
                            Tanggal Selesai
                        </label>
                        <input type="date" class="form-control" name="end_date" id="endDate" required>
                        <small class="text-muted">Pilih tanggal akhir periode laporan</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-file me-1"></i>
                            Format Laporan
                        </label>
                        <select class="form-select" name="format" id="reportFormat">
                            <option value="pdf">PDF Document</option>
                        </select>
                        <small class="text-muted">Pilih format file yang diinginkan</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-filter me-1"></i>
                            Filter Tambahan
                        </label>
                        <select class="form-select" name="filter" id="reportFilter">
                            <option value="all">Semua Data</option>
                            <option value="active">Data Aktif Saja</option>
                            <option value="completed">Data Selesai Saja</option>
                        </select>
                        <small class="text-muted">Pilih filter data yang diinginkan</small>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Informasi:</strong> Laporan akan di-generate berdasarkan periode dan filter yang dipilih. 
                    Proses ini mungkin memakan waktu beberapa detik untuk data yang besar.
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-secondary" onclick="hideReportForm()">
                        <i class="fas fa-times me-1"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="generateBtn">
                        <i class="fas fa-download me-1"></i>
                        Generate Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <h5 class="card-title mb-0">
                <i class="fas fa-history me-2"></i>
                Laporan Terbaru
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Jenis Laporan</th>
                            <th width="25%">Periode</th>
                            <th width="10%">Format</th>
                            <th width="20%">Tanggal Dibuat</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($recentReports->count() > 0)
                            @foreach($recentReports as $index => $report)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $report->report_type_name }}</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($report->start_date)->format('d M Y') }} - 
                                        {{ \Carbon\Carbon::parse($report->end_date)->format('d M Y') }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ strtoupper($report->format) }}</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($report->created_at)->format('d M Y H:i') }} WIB
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="downloadReport({{ $report->id }})" title="Download">
                                                <i class="fas fa-download"></i>
                                            </button>
                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    <i class="fas fa-file-alt fa-3x mb-3 d-block text-muted"></i>
                                    <h6 class="text-muted">Belum ada laporan yang dibuat</h6>
                                    <p class="text-muted small">Generate laporan pertama Anda untuk melihat riwayat di sini</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function showReportForm(reportType) {
        // Reset submission flag when showing form
        if (typeof isSubmitting !== 'undefined') {
            isSubmitting = false;
        }

        document.getElementById('reportType').value = reportType;
        document.getElementById('reportForm').style.display = 'block';

        // Set default dates to current month (full month range)
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        document.getElementById('startDate').value = firstDayOfMonth.toISOString().split('T')[0];
        document.getElementById('endDate').value = lastDayOfMonth.toISOString().split('T')[0];
        
        // Enable all form inputs and refresh CSRF token
        const form = document.getElementById('reportFormElement');
        if (form) {
            // Refresh CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const tokenInput = form.querySelector('input[name="_token"]');
                if (tokenInput) {
                    tokenInput.value = csrfToken.getAttribute('content');
                }
            }
            
            const formInputs = form.querySelectorAll('input, select, button');
            formInputs.forEach(input => {
                input.disabled = false;
            });
            const generateBtn = document.getElementById('generateBtn');
            if (generateBtn) {
                generateBtn.innerHTML = '<i class="fas fa-download me-1"></i>Generate Laporan';
            }
        }
        
        // Scroll to form
        document.getElementById('reportForm').scrollIntoView({ behavior: 'smooth' });
    }

    function hideReportForm() {
        document.getElementById('reportForm').style.display = 'none';
    }

    function showQuickStats() {
        const quickStats = document.getElementById('quickStats');
        if (quickStats.style.display === 'none') {
            quickStats.style.display = 'block';
            quickStats.scrollIntoView({ behavior: 'smooth' });
        } else {
            quickStats.style.display = 'none';
        }
    }

    function showAllReports() {
        // Generate all reports at once
        if (confirm('Apakah Anda yakin ingin generate semua laporan? Ini akan memakan waktu lebih lama.')) {
            // You can implement bulk generation here
            alert('Fitur generate semua laporan akan segera tersedia!');
        }
    }

    // Form submission with loading state - prevent double submission
    let isSubmitting = false;
    
    // Initialize form submission handler
    document.addEventListener('DOMContentLoaded', function() {
        const reportFormElement = document.getElementById('reportFormElement');
        
        if (reportFormElement) {
            reportFormElement.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Prevent double submission
                if (isSubmitting) {
                    return false;
                }
                
                const form = this;
                isSubmitting = true;
                const generateBtn = document.getElementById('generateBtn');
                const originalText = generateBtn.innerHTML;
                
                // Get CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    alert('CSRF token tidak ditemukan. Silakan refresh halaman.');
                    isSubmitting = false;
                    return false;
                }
                
                // Collect form data
                const formData = new FormData(form);
                
                // Ensure CSRF token is in form data
                if (!formData.has('_token')) {
                    formData.append('_token', csrfToken.getAttribute('content'));
                } else {
                    formData.set('_token', csrfToken.getAttribute('content'));
                }
                
                // Disable button and show loading
                generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Generating...';
                generateBtn.disabled = true;
                
                // Disable all form inputs
                const formInputs = form.querySelectorAll('input, select, button');
                formInputs.forEach(input => {
                    if (input !== generateBtn) {
                        input.disabled = true;
                    }
                });
                
                // Submit form using fetch
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content')
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 419) {
                            throw new Error('Session expired. Silakan refresh halaman dan coba lagi.');
                        }
                        throw new Error('Terjadi kesalahan saat generate laporan.');
                    }
                    return response.blob();
                })
                .then(blob => {
                    // Create download link
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    
                    // Get filename from form data
                    const reportType = formData.get('report_type');
                    const startDate = formData.get('start_date');
                    const endDate = formData.get('end_date');
                    a.download = `laporan-${reportType}-${startDate}-to-${endDate}.pdf`;
                    
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Laporan berhasil di-generate dan di-download.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    
                    // Reset form
                    isSubmitting = false;
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                    formInputs.forEach(input => {
                        input.disabled = false;
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Terjadi kesalahan saat generate laporan.'
                    });
                    
                    // Re-enable form
                    isSubmitting = false;
                    generateBtn.innerHTML = originalText;
                    generateBtn.disabled = false;
                    formInputs.forEach(input => {
                        input.disabled = false;
                    });
                });
            });
        }
    });

    // Auto-format date inputs
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        const lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        // Set default values to current month (full month range)
        document.getElementById('startDate').value = firstDayOfMonth.toISOString().split('T')[0];
        document.getElementById('endDate').value = lastDayOfMonth.toISOString().split('T')[0];
    });

    function downloadReport(reportId) {
        // Redirect to the download route
        window.location.href = '{{ url("/admin/reports/download") }}/' + reportId;
    }

    function viewReportDetails(reportId) {
        // Show report details in a modal or alert
        alert('Detail laporan ID: ' + reportId + '\nFitur detail laporan akan segera tersedia!');
    }
</script>
@endsection
