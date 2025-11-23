<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Klinik Alya Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
        }

        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            z-index: 1000;
            box-shadow: 0 0 40px rgba(102, 126, 234, 0.3);
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
            pointer-events: none;
        }

        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .sidebar-brand {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: relative;
        }

        .sidebar-brand-icon {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-brand-icon:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .sidebar-brand-text {
            text-align: center;
        }

        .sidebar-brand-text h4 {
            color: white;
            margin: 0;
            font-size: 1.25rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .sidebar-brand-text small {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-user {
            padding: 20px;
            margin: 15px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .sidebar-user:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .sidebar-user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.1));
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
            font-weight: 800;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar-user-details h6 {
            color: white;
            margin: 0 0 3px 0;
            font-size: 0.95rem;
            font-weight: 700;
        }

        .sidebar-user-details small {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.75rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .sidebar-menu {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar-menu::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-menu::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-menu::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .sidebar-menu::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-section-title {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0 25px;
            margin-bottom: 12px;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.9);
            padding: 14px 25px;
            margin: 3px 15px;
            border-radius: 12px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            font-size: 0.9rem;
            position: relative;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            overflow: hidden;
        }

        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: white;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar .nav-link i {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link span {
            flex: 1;
        }

        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .sidebar .nav-link:hover::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link.active::before {
            transform: scaleY(1);
        }

        .sidebar .nav-link.active i {
            transform: scale(1.1);
        }

        .menu-badge {
            min-width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            color: white;
            font-size: 0.7rem;
            padding: 0 8px;
            border-radius: 12px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .nav-link.active .menu-badge {
            background: white;
            color: #667eea;
        }

        .main-content {
            margin-left: 280px;
            padding: 35px;
            min-height: 100vh;
        }

        .page-header {
            background: white;
            border-radius: 24px;
            padding: 35px;
            margin-bottom: 35px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(102, 126, 234, 0.1);
            position: relative;
            overflow: hidden;
        }

        .page-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
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
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid rgba(102, 126, 234, 0.08);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .card-header {
            background: linear-gradient(135deg, #faf5ff, #f3e8ff);
            border: none;
            padding: 25px 30px;
            border-bottom: 1px solid rgba(102, 126, 234, 0.1);
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-outline-primary {
            border: 2px solid #667eea;
            color: #667eea;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-outline-primary:hover {
            background: #667eea;
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

        .specialty-badge {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 6px 16px;
            border-radius: 25px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            min-width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
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

        .form-check {
            margin-bottom: 8px;
        }

        .form-check-input {
            border-radius: 4px;
            border: 2px solid #d1d5db;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: #374151;
        }

        .schedule-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #64748b;
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
            border-color: #3b82f6;
            background: #f8fafc;
        }

        .service-type-card.selected {
            border-color: #3b82f6;
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

        .table td {
            vertical-align: middle !important;
        }

        .text-break {
            word-break: break-word;
            overflow-wrap: break-word;
        }

        .flex-shrink-0 {
            flex-shrink: 0;
        }

        .card {
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
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

        .stat-revenue {
            font-size: 1.4rem;
            font-weight: 600;
        }

        .stat-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
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

        /* Appointments specific styles */
        .queue-number {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .filter-tabs {
            background: white;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-tab {
            padding: 8px 20px;
            border-radius: 20px;
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .filter-tab.active,
        .filter-tab:hover {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        /* Medical Records specific styles */
        .service-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .service-rawat-jalan {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #86efac;
        }

        .service-rawat-inap {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border: 1px solid #f59e0b;
        }

        /* Invoices specific styles */
        .status-belum-lunas {
            background: #fef3c7;
            color: #92400e;
        }

        .status-lunas {
            background: #dcfce7;
            color: #166534;
        }

        .amount {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .invoice-number {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        /* Reports specific styles */
        .report-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
        }

        .report-header {
            position: relative;
            padding: 1.5rem 1.5rem 1rem 1.5rem;
        }

        .report-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .report-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

        .report-body {
            padding: 0 1.5rem 1rem 1.5rem;
        }

        .report-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .report-description {
            color: #64748b;
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 1rem;
        }

        .report-features {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .feature-item {
            background: #f1f5f9;
            color: #475569;
            padding: 0.25rem 0.5rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .report-footer {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            margin-bottom: 15px;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
        }

        .stats-label {
            color: #64748b;
            font-weight: 500;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                transform: translateX(-100%);
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 1050;
                box-shadow: 10px 0 50px rgba(0, 0, 0, 0.3);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                backdrop-filter: blur(5px);
            }

            .sidebar-backdrop.show {
                display: block;
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 2rem;
            }

            .stat-card,
            .stats-card {
                padding: 20px;
                margin-bottom: 15px;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 20px;
            }

            .table-responsive {
                font-size: 0.9rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table th,
            .table td {
                white-space: nowrap;
                padding: 12px 8px;
            }

            .btn-group-sm .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
                min-width: 35px;
            }

            .filter-tabs {
                padding: 8px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .filter-tabs .d-flex {
                flex-wrap: nowrap;
                gap: 0.5rem;
            }

            .filter-tab {
                padding: 6px 12px;
                font-size: 0.85rem;
                white-space: nowrap;
            }

            .card {
                margin-bottom: 20px;
            }

            .page-header {
                padding: 20px;
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

            .modal-dialog {
                margin: 0.5rem;
            }

            .queue-number {
                font-size: 0.9rem;
                padding: 6px 12px;
            }

            .status-badge {
                font-size: 0.75rem;
                padding: 4px 8px;
            }

            .empty-state i {
                font-size: 3rem;
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
                <i class="fas fa-hospital-alt"></i>
            </div>
            <div class="sidebar-brand-text">
                <h4>Klinik Alya Medika</h4>
                <small>Administrator</small>
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
                    <small>
                        <span class="status-dot"></span>
                        Online
                    </small>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="menu-section">
                <div class="menu-section-title">Dashboard</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Overview</span>
                    </a>
                </nav>
            </div>

            <!-- Management Section -->
            <div class="menu-section">
                <div class="menu-section-title">Manajemen</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"
                        href="{{ route('admin.users') }}">
                        <i class="fas fa-users"></i>
                        <span>Kelola Akun</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}"
                        href="{{ route('admin.doctors') }}">
                        <i class="fas fa-user-md"></i>
                        <span>Jadwal Dokter</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}"
                        href="{{ route('admin.appointments') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Janji Temu</span>
                        @php
                            $pendingAppointments = \App\Models\Appointment::where('status', 'menunggu')->count();
                        @endphp
                        @if ($pendingAppointments > 0)
                            <span class="menu-badge">{{ $pendingAppointments }}</span>
                        @endif
                    </a>
                </nav>
            </div>

            <!-- Medical Section -->
            <div class="menu-section">
                <div class="menu-section-title">Medis & Keuangan</div>
                <nav class="nav flex-column">
                    <a class="nav-link {{ request()->routeIs('admin.medical-records*') ? 'active' : '' }}"
                        href="{{ route('admin.medical-records') }}">
                        <i class="fas fa-file-medical"></i>
                        <span>Rekam Medis</span>
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}"
                        href="{{ route('admin.invoices') }}">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Invoice</span>
                        @php
                            $unpaidInvoices = \App\Models\Invoice::where('status', 'belum_bayar')->count();
                        @endphp
                        @if ($unpaidInvoices > 0)
                            <span class="menu-badge">{{ $unpaidInvoices }}</span>
                        @endif
                    </a>
                    <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"
                        href="{{ route('admin.reports') }}">
                        <i class="fas fa-chart-line"></i>
                        <span>Laporan</span>
                    </a>
                </nav>
            </div>

            <!-- System Section -->
            <div class="menu-section">
                <div class="menu-section-title">Sistem</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="{{ route('admin.logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Mobile Header -->
        <div class="d-md-none mobile-header">
            <button class="btn btn-outline-primary me-3" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h5 class="mb-0">Admin Panel</h5>
        </div>
        @yield('content')
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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





