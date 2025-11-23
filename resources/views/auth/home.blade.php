<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Alya Medika - Sistem Informasi Manajemen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(25px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding: 1rem 0;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            min-height: 80px;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: #1e293b !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .navbar-brand:hover {
            color: #3b82f6 !important;
            transform: scale(1.02);
        }

        .navbar-brand i {
            font-size: 1.6rem;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-main {
            font-size: 1.2rem;
            font-weight: 800;
        }

        .brand-sub {
            font-size: 0.7rem;
            font-weight: 500;
            color: #64748b;
            margin-top: -2px;
        }

        .navbar-nav {
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
            flex: 1;
            margin: 0 2rem;
        }

        .nav-link {
            font-weight: 600;
            color: #475569 !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            font-size: 0.95rem;
            white-space: nowrap;
            min-height: 44px;
            display: flex;
            align-items: center;
        }

        .nav-link:hover {
            color: #3b82f6 !important;
            background: rgba(59, 130, 246, 0.1);
            transform: translateY(-1px);
        }

        .nav-link.active {
            color: #3b82f6 !important;
            background: rgba(59, 130, 246, 0.15);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-nav {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            min-height: 44px;
        }

        .btn-login {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
            color: #334155;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-register {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 2px 10px rgba(59, 130, 246, 0.3);
        }

        .btn-register:hover {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
            border-radius: 8px;
            background: rgba(59, 130, 246, 0.1);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%2859, 130, 246, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .navbar-nav {
                margin: 0 1rem;
                gap: 0.5rem;
            }

            .nav-link {
                padding: 0.6rem 1rem !important;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 991.98px) {
            .navbar {
                padding: 0.75rem 0;
                min-height: 70px;
            }

            .navbar-nav {
                margin: 0;
                justify-content: flex-start;
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(25px);
                border-radius: 15px;
                margin-top: 1rem;
                padding: 1.5rem;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            }

            .navbar-nav {
                gap: 0.25rem;
            }

            .nav-link {
                margin: 0.25rem 0;
                text-align: center;
                padding: 0.75rem 1rem !important;
                justify-content: center;
            }

            .navbar-actions {
                flex-direction: column;
                width: 100%;
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .btn-nav {
                width: 100%;
                justify-content: center;
                padding: 0.75rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .navbar {
                padding: 0.5rem 0;
                min-height: 60px;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .brand-main {
                font-size: 0.9rem;
            }

            .brand-sub {
                font-size: 0.6rem;
            }

            .navbar-brand i {
                font-size: 1.3rem;
            }
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            padding-top: 80px;
            /* Memberikan ruang di atas untuk header */
            padding-left: 20px;
            /* Memberikan ruang di kiri */
            padding-right: 20px;
            /* Memberikan ruang di kanan */
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;
            opacity: 0.2;
            z-index: 1;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
        }

        .hero-section .hero-image img {
            object-fit: cover;
            /* Menjaga gambar tetap terpotong proporsional */
            width: 100%;
            height: 100%;
            max-height: 500px;
            /* Atur sesuai dengan tinggi yang diinginkan */
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .floating-elements {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .floating-element {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 60%;
            right: 15%;
            animation-delay: 2s;
        }

        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 40%;
            right: 30%;
            animation-delay: 4s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            /* Mengatur jarak antara teks dan gambar */
            align-items: center;
            padding: 2rem;
            /* Menambahkan ruang di dalam hero section */
        }

        .hero-text {
            z-index: 3;
        }

        .hero-image {
            position: relative;
            z-index: 3;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(-5deg);
            transition: all 0.3s ease;
        }

        .hero-image img:hover {
            transform: perspective(1000px) rotateY(0deg) scale(1.02);
        }

        .main-title {
            font-size: 4rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .gradient-text {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1.375rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 3rem;
            max-width: 600px;
            font-weight: 400;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.6s both;
        }

        .btn-primary-hero {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            border-radius: 50px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-primary-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-secondary-hero {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            padding: 1rem 2rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        .btn-secondary-hero:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        /* Section Styles */
        .section {
            padding: 5rem 0;
        }

        .section-light {
            background: #ffffff;
        }

        .section-gray {
            background: #f8fafc;
        }

        .section-dark {
            background: linear-gradient(135deg, #1e293b, #334155);
            color: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .section-dark .section-title {
            color: white;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .section-dark .section-subtitle {
            color: #cbd5e1;
        }


        /* Features Section */
        .features-section {
            padding: 6rem 0;
            background: #ffffff;
        }

        .features-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8fafc, #ffffff);
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .feature-content h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .feature-content p {
            color: #64748b;
            line-height: 1.6;
            font-size: 0.95rem;
            margin: 0;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            padding: 6rem 0;
            color: #1e293b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            margin-top: 3rem;
        }

        .stat-item {
            background: white;
            border-radius: 24px;
            padding: 3rem 2rem;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .stat-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1.5rem;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: block;
        }

        .stat-label {
            color: #64748b;
            font-size: 1.1rem;
            font-weight: 600;
        }

        /* About Section */
        .about-section {
            padding: 5rem 0;
            background: #f8fafc;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-text h2 {
            font-size: 2.25rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .about-text p {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }

        .about-image {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 16px;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        /* Doctor Schedule Section */
        .schedule-section {
            text-align: center;
            padding: 6rem 0;
            background: white;
        }

        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 3rem;
        }

        .doctor-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .doctor-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .doctor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .doctor-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .doctor-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #10b981, #059669);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .doctor-info h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .doctor-specialty {
            color: #10b981;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .schedule-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .schedule-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .schedule-item:last-child {
            border-bottom: none;
        }

        .day-name {
            font-weight: 600;
            color: #475569;
            font-size: 0.95rem;
        }

        .schedule-time {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e40af;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .schedule-status {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
        }

        .status-available {
            background: #dcfce7;
            color: #166534;
        }

        .status-busy {
            background: #fef3c7;
            color: #92400e;
        }

        .status-off {
            background: #fee2e2;
            color: #991b1b;
        }

        .schedule-note {
            margin-top: 1rem;
            padding: 1rem;
            background: #f8fafc;
            border-radius: 12px;
            border-left: 4px solid #10b981;
        }

        .schedule-note p {
            margin: 0;
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            background: #1e293b;
            color: white;
            padding: 3rem 0 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }

        .footer-text {
            color: #cbd5e1;
            line-height: 1.6;
        }

        .footer-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-link {
            color: #cbd5e1;
            text-decoration: none;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }

        .footer-link:hover {
            color: #3b82f6;
        }

        .footer-bottom {
            border-top: 1px solid #334155;
            padding-top: 2rem;
            text-align: center;
            color: #94a3b8;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-title {
                font-size: 2.5rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: flex-start;
            }

            .hero-content {
                grid-template-columns: 1fr;
                gap: 2rem;
                text-align: center;
            }

            .hero-image img {
                transform: none;
                max-width: 80%;
            }

            .about-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-hospital-alt"></i>
                <div class="navbar-brand-text">
                    <div class="brand-main">Klinik Alya Medika</div>
                    <div class="brand-sub">Sistem Informasi Klinik</div>
                </div>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#schedule">Jadwal Dokter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                </ul>

                <div class="navbar-actions">
                    <a href="{{ route('patient.login') }}" class="btn-nav btn-login">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </a>
                    <a href="{{ route('patient.register') }}" class="btn-nav btn-register">
                        <i class="fas fa-user-plus"></i>
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="main-title">
                        Welcome to<br>
                        <span class="gradient-text">Klinik Alya Medika</span>
                    </h1>
                    <p class="subtitle">
                        Sistem Informasi Manajemen Klinik Terintegrasi - CI/CD Test Deployment Version 1.0
                    </p>

                    <div class="cta-buttons">
                        <a href="{{ route('patient.login') }}" class="btn-primary-hero">
                            <i class="fas fa-sign-in-alt"></i>
                            Masuk ke Akun
                        </a>
                        <a href="{{ route('patient.register') }}" class="btn-secondary-hero">
                            <i class="fas fa-user-plus"></i>
                            Daftar Sekarang
                        </a>
                    </div>
                </div>

                <div class="hero-image">
                    <img src="{{ asset('images/hero.png') }}" alt="Klinik Alya Medika - Layanan Kesehatan Modern">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Mengapa Memilih Kami?</h2>
                <p class="section-subtitle">
                    Pelayanan Terbaik untuk Kesehatan Anda dan Keluarga
                </p>
            </div>

            <div class="features-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Appointment Online</h3>
                        <p>Daftar janji temu dengan dokter pilihan Anda secara online, kapan saja dan di mana saja.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-list-ol"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Sistem Antrian Digital</h3>
                        <p>Pantau nomor antrian Anda secara real-time tanpa perlu menunggu di klinik.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-medical"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Rekam Medis Digital</h3>
                        <p>Akses riwayat kesehatan dan hasil pemeriksaan Anda dengan mudah dan aman.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-invoice"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Invoice Digital</h3>
                        <p>Terima tagihan dan bukti pembayaran secara digital untuk kemudahan administrasi.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-pills"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Resep Digital</h3>
                        <p>Dapatkan resep obat digital yang dapat diakses kapan saja dan di mana saja.</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Laporan Kesehatan</h3>
                        <p>Pantau perkembangan kesehatan Anda dengan grafik dan laporan yang mudah dipahami.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Doctor Schedule Section -->
    <section class="schedule-section" id="schedule">
        <div class="container">
            <h2 class="section-title">Jadwal Praktik Dokter</h2>
            <p class="section-subtitle">
                Lihat jadwal praktik dokter spesialis kami untuk memudahkan Anda merencanakan kunjungan
            </p>

            <div class="schedule-grid">
                <!-- Dr. Sarah Wijaya - Dokter Umum -->
                <div class="doctor-card">
                    <div class="doctor-header">
                        <div class="doctor-avatar">BS</div>
                        <div class="doctor-info">
                            <h4>dr. Budi Santoso</h4>
                            <div class="doctor-specialty">Dokter Umum</div>
                        </div>
                    </div>

                    <!-- Jadwal Konsultasi -->
                    <ul class="schedule-list">
                        <li class="schedule-item">
                            <span class="day-name">Senin - Rabu</span>
                            <div class="schedule-details">
                                <span class="schedule-time">08:00 - 17:00</span>
                            </div>
                        </li>
                    </ul>

                    <!-- Keterangan Konsultasi -->
                    <div class="schedule-note">
                        <p><i class="fas fa-info-circle me-1"></i>Melayani konsultasi untuk masalah kesehatan
                            sehari-hari, mulai dari penyakit ringan hingga pengelolaan kondisi kronis.</p>
                    </div>
                </div>

                <!-- Dr. Ahmad Rahman - Dokter Anak -->
                <div class="doctor-card">
                    <div class="doctor-header">
                        <div class="doctor-avatar">SW</div>
                        <div class="doctor-info">
                            <h4>dr. Maria Magdalena</h4>
                            <div class="doctor-specialty">Dokter Umum</div>
                        </div>
                    </div>

                    <!-- Jadwal Konsultasi -->
                    <ul class="schedule-list">
                        <li class="schedule-item">
                            <span class="day-name">Kamis - Jumat</span>
                            <div class="schedule-details">
                                <span class="schedule-time">08:00 - 17:00</span>
                            </div>
                        </li>
                    </ul>

                    <!-- Keterangan Konsultasi -->
                    <div class="schedule-note">
                        <p><i class="fas fa-info-circle me-1"></i>Melayani konsultasi untuk
                            masalah kesehatan sehari-hari,
                            mulai dari penyakit ringan
                            hingga pengelolaan kondisi
                            kronis.</p>
                    </div>
                </div>

                <!-- Dr. Maria Sari - Dokter Kandungan -->
                <div class="doctor-card">
                    <div class="doctor-header">
                        <div class="doctor-avatar">MS</div>
                        <div class="doctor-info">
                            <h4>dr. Siti Nurhaliza, Sp.OG</h4>
                            <div class="doctor-specialty">Dokter Spesialis Kandungan</div>
                        </div>
                    </div>

                    <ul class="schedule-list">
                        <li class="schedule-item">
                            <span class="day-name">Senin</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">09:00 - 16:00</span>
                            </div>
                        </li>
                        <li class="schedule-item">
                            <span class="day-name">Rabu</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">09:00 - 16:00</span>
                            </div>
                        </li>
                        <li class="schedule-item">
                            <span class="day-name">Kamis</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">09:00 - 16:00</span>
                            </div>
                        </li>
                    </ul>

                    <div class="schedule-note">
                        <p><i class="fas fa-info-circle me-1"></i> Konsultasi kehamilan, USG, dan kesehatan reproduksi
                            wanita</p>
                    </div>
                </div>

                <!-- Dr. Budi Santoso - Dokter Mata -->
                <div class="doctor-card">
                    <div class="doctor-header">
                        <div class="doctor-avatar">AW</div>
                        <div class="doctor-info">
                            <h4>drg. Ahmad Wijaya</h4>
                            <div class="doctor-specialty">Dokter Gigi</div>
                        </div>
                    </div>

                    <ul class="schedule-list">
                        <li class="schedule-item">
                            <span class="day-name">Senin</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">08:00 - 16:00</span>
                            </div>
                        </li>
                        <li class="schedule-item">
                            <span class="day-name">Selasa</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">08:00 - 16:00</span>
                            </div>
                        </li>
                        <li class="schedule-item">
                            <span class="day-name">Jumat</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="schedule-time">08:00 - 16:00</span>
                            </div>
                        </li>
                    </ul>

                    <div class="schedule-note">
                        <p><i class="fas fa-info-circle me-1"></i>Konsultasi kesehatan gigi dan mulut, perawatan
                            pencegahan, dan penanganan masalah gigi seperti karies atau gigi berlubang.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <div class="alert alert-info d-inline-block"
                    style="background: linear-gradient(135deg, #dbeafe, #bfdbfe); border: none; border-radius: 15px; padding: 1.5rem 2rem;">
                    <h5 class="mb-2"><i class="fas fa-calendar-alt me-2"></i>Informasi Penting</h5>
                    <p class="mb-0">Jadwal dapat berubah sewaktu-waktu. Silakan hubungi klinik untuk konfirmasi atau
                        daftar appointment online untuk memastikan slot tersedia.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Tentang Klinik Alya Medika</h2>
                    <p>
                        Klinik Alya Medika adalah klinik kesehatan yang didirikan dengan semangat untuk memberikan
                        perawatan kesehatan yang berkualitas, dengan perhatian penuh pada kenyamanan dan kebutuhan
                        pasien.
                    </p>
                    <p>
                        Kami menyediakan layanan medis dengan dokter yang berpengalaman dan fasilitas yang memadai untuk
                        melayani Anda dan keluarga dalam menjaga kesehatan.
                    </p>
                </div>
                <div class="about-image">
                    <i class="fas fa-hospital"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Pencapaian Kami</h2>
                <p class="section-subtitle">
                    Kepercayaan ribuan pasien yang telah merasakan pelayanan terbaik kami
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <span class="stat-number">500+</span>
                    <div class="stat-label">Pasien Terdaftar</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-user-md"></i>
                    </div>
                    <span class="stat-number">4</span>
                    <div class="stat-label">Dokter Berpengalaman</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="stat-number">24/7</span>
                    <div class="stat-label">Layanan Online</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <span class="stat-number">99%</span>
                    <div class="stat-label">Kepuasan Pasien</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer-content">
                <div>
                    <div class="footer-brand">
                        <i class="fas fa-hospital-alt me-2"></i>
                        Klinik Alya Medika
                    </div>
                    <p class="footer-text">
                        Sistem Informasi Manajemen Klinik Terintegrasi yang memudahkan akses layanan kesehatan
                        dengan teknologi modern dan pelayanan terbaik.
                    </p>
                </div>

                <div>
                    <h4 class="footer-title">Layanan</h4>
                    <a href="#" class="footer-link">Appointment Online</a>
                    <a href="#" class="footer-link">Rekam Medis</a>
                    <a href="#" class="footer-link">Resep Digital</a>
                    <a href="#" class="footer-link">Invoice Digital</a>
                </div>

                <div>
                    <h4 class="footer-title">Informasi</h4>
                    <a href="#" class="footer-link">Tentang Kami</a>
                    <a href="#" class="footer-link">Dokter</a>
                    <a href="#" class="footer-link">Fasilitas</a>
                    <a href="#" class="footer-link">Kontak</a>
                </div>

                <div>
                    <h4 class="footer-title">Kontak</h4>
                    <p class="footer-text">
                        <i class="fas fa-phone me-2"></i>
                        (021) 1234-5678
                    </p>
                    <p class="footer-text">
                        <i class="fas fa-envelope me-2"></i>
                        info@klinikalya.com
                    </p>
                    <p class="footer-text">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Jl. Garuda Sakti No.km 2, Simpang Baru, Kec. Tampan, Kota Pekanbaru, Riau
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2024 Klinik Alya Medika. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active nav link highlighting
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link[href^="#"]');

            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.scrollY >= (sectionTop - 200)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Mobile menu close on link click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function() {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                    bsCollapse.hide();
                }
            });
        });
    </script>
</body>

</html>
