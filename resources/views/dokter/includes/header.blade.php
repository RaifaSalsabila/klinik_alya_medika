<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dokter Panel') - Klinik Alya Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .sidebar {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .sidebar-brand {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-brand-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .sidebar-brand-text h4 {
            color: white;
            margin: 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .sidebar-brand-text small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
            font-weight: 500;
        }

        .sidebar-user {
            padding: 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .sidebar-user-details h6 {
            color: white;
            margin: 0;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .sidebar-user-details small {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.75rem;
        }

        .sidebar-menu {
            padding: 15px 0;
            height: 100%;
        }

        .menu-section {
            margin-bottom: 25px;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 20px;
            margin-bottom: 10px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 20px;
            border-radius: 0;
            margin: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 3px solid transparent;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left-color: white;
            padding-left: 25px;
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            border-left-color: white;
            font-weight: 600;
        }

        .menu-badge {
            margin-left: auto;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: 700;
        }

        .nav-link.active .menu-badge {
            background: white;
            color: #10b981;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.1rem;
            margin: 8px 0 0 0;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            border: none;
            padding: 25px 30px;
            border-bottom: 1px solid #e2e8f0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #10b981;
            color: #10b981;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-danger {
            border: 2px solid #ef4444;
            color: #ef4444;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #ef4444;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-success {
            border: 2px solid #10b981;
            color: #10b981;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-success:hover {
            background: #10b981;
            color: white;
            transform: translateY(-2px);
        }

        .btn-outline-warning {
            border: 2px solid #f59e0b;
            color: #f59e0b;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-warning:hover {
            background: #f59e0b;
            color: white;
            transform: translateY(-2px);
        }

        .table {
            margin: 0;
        }

        .table th {
            border: none;
            background: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 20px 15px;
        }

        .table td {
            border: none;
            vertical-align: middle;
            padding: 20px 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-menunggu {
            background: #fef3c7;
            color: #92400e;
        }

        .status-diterima {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-selesai {
            background: #dcfce7;
            color: #166534;
        }

        .status-batal {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h5 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .empty-state p {
            font-size: 0.9rem;
            margin: 0;
        }

        /* Dashboard specific styles */
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e293b;
            margin: 5px 0 3px 0;
            line-height: 1.2;
            word-break: break-all;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Medical Record Form Styles */
        .info-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
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

        .service-type-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .service-type-card:hover {
            border-color: #10b981;
            background: #f8fafc;
        }

        .service-type-card.selected {
            border-color: #10b981;
            background: #eff6ff;
        }

        .service-type-card input[type="radio"] {
            transform: scale(1.2);
        }

        .medicine-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0 !important;
        }

        .medicine-item:hover {
            background: #f1f5f9;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                z-index: 1050;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 2rem;
            }

            .mobile-header {
                display: flex;
                align-items: center;
                padding: 10px 20px;
                background: white;
                border-radius: 10px;
                margin-bottom: 20px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
                position: sticky;
                top: 0;
                z-index: 100;
            }
        }
    </style>
    @yield('styles')
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-stethoscope"></i>
            </div>
            <div class="sidebar-brand-text">
                <h4>Klinik Alya Medika</h4>
                <small>Portal Dokter</small>
            </div>
        </div>

        <!-- User Profile -->
        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-details">
                    <h6>{{ Auth::user()->name }}</h6>
                    <small><i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i> Aktif</small>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="menu-section">
                <div class="menu-section-title">Dashboard</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}"
                        href="{{ route('dokter.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </nav>
            </div>

            <!-- Medical Section -->
            <div class="menu-section">
                <div class="menu-section-title">Layanan Medis</div>
                <nav class="nav flex-column">
                    <a href="{{ route('dokter.appointments') }}"
                        class="nav-link {{ request()->routeIs('dokter.appointments*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Janji Temu</span>
                        @php
                            $doctor = \App\Models\Doctor::where('user_id', Auth::id())->first();
                            $todayAppointments = $doctor
                                ? \App\Models\Appointment::where('doctor_id', $doctor->id)
                                    ->whereDate('appointment_date', today())
                                    ->whereIn('status', ['menunggu', 'diterima'])
                                    ->count()
                                : 0;
                        @endphp
                        @if ($todayAppointments > 0)
                            <span class="menu-badge">{{ $todayAppointments }}</span>
                        @endif
                    </a>
                    <a class="nav-link {{ request()->routeIs('dokter.medical-records*') ? 'active' : '' }}"
                        href="{{ route('dokter.medical-records') }}">
                        <i class="fas fa-file-medical-alt"></i>
                        <span>Rekam Medis</span>
                    </a>
                </nav>
            </div>

            <!-- System Section -->
            <div class="menu-section">
                <div class="menu-section-title">Sistem</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('dokter.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Backdrop -->
    <div class="sidebar-backdrop d-md-none" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile Header -->
        <div class="d-md-none mobile-header">
            <button class="btn btn-outline-primary me-3" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0">Dokter Panel</h5>
        </div>
        @yield('content')
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('dokter.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile sidebar toggle
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
    @yield('scripts')
</body>

</html>
