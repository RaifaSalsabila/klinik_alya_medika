<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pasien - Klinik Alya Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #4ecdc4 0%, #44a08d 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .btn-login {
            background: linear-gradient(45deg, #4ecdc4, #44a08d);
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .form-control {
            border-radius: 50px;
            border: 2px solid #e9ecef;
            padding: 15px 20px;
        }
        .form-control:focus {
            border-color: #4ecdc4;
            box-shadow: 0 0 0 0.2rem rgba(78, 205, 196, 0.25);
        }
        .input-group-text {
            border-radius: 50px 0 0 50px;
            border: 2px solid #e9ecef;
            border-right: none;
            background: transparent;
        }
        .input-group .form-control {
            border-left: none;
        }
        .input-group:focus-within .input-group-text {
            border-color: #4ecdc4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <div class="row justify-content-center w-100">
                <div class="col-md-6 col-lg-4">
                    <div class="card login-card">
                        <div class="card-body p-5">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <i class="fas fa-user-injured fa-3x text-success mb-3"></i>
                                <h3 class="fw-bold">Login Pasien</h3>
                                <p class="text-muted">Klinik Alya Medika</p>
                            </div>

                            <!-- Login Form -->
                            <form method="POST" action="{{ route('patient.login') }}">
                                @csrf
                                
                                <!-- Login Field (Email atau Phone) -->
                                <div class="mb-4">
                                    <label for="login" class="form-label fw-semibold">Email atau No. HP</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('login') is-invalid @enderror" 
                                               id="login" 
                                               name="login" 
                                               value="{{ old('login') }}" 
                                               placeholder="email@example.com atau 081234567890"
                                               required>
                                    </div>
                                    @error('login')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password Field -->
                                <div class="mb-4">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" 
                                               class="form-control @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password" 
                                               placeholder="Masukkan password"
                                               required>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Login Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-login text-white">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login Pasien
                                    </button>
                                </div>
                            </form>

                            <!-- Register Link -->
                            <div class="text-center mb-3">
                                <p class="mb-0">Belum punya akun?</p>
                                <a href="{{ route('patient.register') }}" class="text-decoration-none fw-semibold">
                                    Daftar Akun Pasien
                                </a>
                            </div>

                            <!-- Back to Home -->
                            <div class="text-center">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Kembali ke Halaman Utama
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
