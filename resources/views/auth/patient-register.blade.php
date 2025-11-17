<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pasien - Klinik Alya Medika</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }
        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
        }
        .btn-register {
            background: linear-gradient(45deg, #a8edea, #fed6e3);
            border: none;
            border-radius: 50px;
            padding: 12px 40px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #333;
        }
        .form-control {
            border-radius: 50px;
            border: 2px solid #e9ecef;
            padding: 15px 20px;
        }
        .form-control:focus {
            border-color: #a8edea;
            box-shadow: 0 0 0 0.2rem rgba(168, 237, 234, 0.25);
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
            border-color: #a8edea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="row justify-content-center w-100">
                <div class="col-md-8 col-lg-6">
                    <div class="card register-card">
                        <div class="card-body p-5">
                            <!-- Header -->
                            <div class="text-center mb-4">
                                <i class="fas fa-user-plus fa-3x text-info mb-3"></i>
                                <h3 class="fw-bold">Daftar Akun Pasien</h3>
                                <p class="text-muted">Klinik Alya Medika</p>
                            </div>

                            <!-- Success Message -->
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Register Form -->
                            <form method="POST" action="{{ route('patient.register') }}">
                                @csrf
                                
                                <div class="row">
                                    <!-- Nama Lengkap -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="Nama lengkap"
                                                   required>
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label fw-semibold">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope text-muted"></i>
                                            </span>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="email@example.com"
                                                   required>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <!-- No. HP -->
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label fw-semibold">No. HP</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-phone text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ old('phone') }}" 
                                                   placeholder="081234567890"
                                                   required>
                                        </div>
                                        @error('phone')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- NIK -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nik" class="form-label fw-semibold">NIK</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-id-card text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control @error('nik') is-invalid @enderror" 
                                                   id="nik" 
                                                   name="nik" 
                                                   value="{{ old('nik') }}" 
                                                   placeholder="1234567890123456"
                                                   maxlength="16"
                                                   required>
                                        </div>
                                        @error('nik')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Alamat -->
                                <div class="mb-3">
                                    <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-map-marker-alt text-muted"></i>
                                        </span>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" 
                                                  name="address" 
                                                  rows="3" 
                                                  placeholder="Alamat lengkap tempat tinggal"
                                                  required>{{ old('address') }}</textarea>
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label fw-semibold">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Minimal 8 karakter"
                                                   required>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock text-muted"></i>
                                            </span>
                                            <input type="password" 
                                                   class="form-control" 
                                                   id="password_confirmation" 
                                                   name="password_confirmation" 
                                                   placeholder="Ulangi password"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Register Button -->
                                <div class="d-grid mb-4">
                                    <button type="submit" class="btn btn-register">
                                        <i class="fas fa-user-plus me-2"></i>
                                        Daftar Akun Pasien
                                    </button>
                                </div>
                            </form>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="mb-0">Sudah punya akun?</p>
                                <a href="{{ route('patient.login') }}" class="text-decoration-none fw-semibold">
                                    Login di sini
                                </a>
                            </div>

                            <!-- Back to Home -->
                            <div class="text-center mt-3">
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
