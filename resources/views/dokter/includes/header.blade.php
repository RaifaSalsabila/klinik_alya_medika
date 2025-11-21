<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dokter Panel') - Klinik Alya Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: #f8fafc;
            font-family: 'Inter', sans-serif;
        }
        .sidebar {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 15px 25px;
            border-radius: 12px;
            margin: 6px 15px;
            transition: all 0.3s ease;
            font-weight: 500;
            position: relative;
        }
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .sidebar .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 20px;
            background: white;
            border-radius: 0 4px 4px 0;
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
        .status-menunggu { background: #fef3c7; color: #92400e; }
        .status-diterima { background: #dbeafe; color: #1e40af; }
        .status-selesai { background: #dcfce7; color: #166534; }
        .status-batal { background: #fee2e2; color: #991b1b; }
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
        <div class="p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                    <i class="fas fa-user-md text-success" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h4 class="text-white mb-0" style="font-size: 1.2rem;">Klinik Alya Medika</h4>
                    <small class="text-white-50">Dokter Panel</small>
                </div>
            </div>
            
            <nav class="nav flex-column">
                <a class="nav-link {{ request()->routeIs('dokter.dashboard') ? 'active' : '' }}" href="{{ route('dokter.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-3"></i>
                    Dashboard
                </a>
                <a class="nav-link {{ request()->routeIs('dokter.appointments*') ? 'active' : '' }}" href="{{ route('dokter.appointments') }}">
                    <i class="fas fa-calendar-check me-3"></i>
                    Janji Temu
                </a>
                <a class="nav-link {{ request()->routeIs('dokter.medical-records*') ? 'active' : '' }}" href="{{ route('dokter.medical-records') }}">
                    <i class="fas fa-file-medical me-3"></i>
                    Rekam Medis
                </a>
                
                <hr class="text-white-50 my-4">
                
                <a class="nav-link" href="{{ route('dokter.logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-3"></i>
                    Logout
                </a>
            </nav>
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

