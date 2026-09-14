<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Job Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        body {
            background: #f1f5f9;
            color: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 16px;
            padding: 36px;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.08), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }
        .header {
            text-align: center;
            margin-bottom: 28px;
        }
        .header h1 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
        }
        .header p {
            font-size: 0.92rem;
            color: #64748b;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            font-size: 0.95rem;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .input-error {
            border-color: #ef4444 !important;
        }
        .error-message {
            color: #ef4444;
            font-size: 0.82rem;
            margin-top: 5px;
            font-weight: 500;
        }
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 8px;
        }
        .btn-submit:hover {
            background: #1d4ed8;
        }
        .btn-submit:active {
            transform: scale(0.99);
        }
        .footer-link {
            text-align: center;
            margin-top: 24px;
            font-size: 0.88rem;
            color: #64748b;
        }
        .footer-link a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
        }
        .alert-error ul {
            padding-left: 18px;
        }
        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .role-section {
            margin-bottom: 20px;
        }
        .role-section > label {
            margin-bottom: 10px;
        }
        .role-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .role-card {
            position: relative;
            cursor: pointer;
        }
        .role-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }
        .role-card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 14px 10px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.2s;
            text-align: center;
            user-select: none;
        }
        .role-card-content:hover {
            border-color: #93c5fd;
            background: #f8fafc;
        }
        .role-card input[type="radio"]:checked + .role-card-content {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .role-card-content .role-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .role-card-content .role-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
        }
        .role-card input[type="radio"]:checked + .role-card-content .role-name {
            color: #1d4ed8;
        }
        .role-card-content .role-desc {
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.3;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Masuk Akun</h1>
            <p>Silakan masuk ke akun Job Portal Anda</p>
        </div>

        @if (session('success'))
            <div class="alert-success" style="display:none" id="login-success-alert">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="role-section">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                    <label style="margin-bottom:0;">Pilih Peran Masuk</label>
                    <button type="button" id="clear-role" style="background:none;border:none;color:#64748b;font-size:0.75rem;cursor:pointer;display:none;text-decoration:underline;">Batalkan pilihan</button>
                </div>
                <div class="role-options">
                    <label class="role-card">
                        <input type="radio" name="role" value="job_seeker" id="role-job-seeker" {{ old('role') === 'job_seeker' ? 'checked' : '' }}>
                        <div class="role-card-content">
                            <div class="role-icon" style="background:#eff6ff;color:#2563eb;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <span class="role-name">Pencari Kerja</span>
                            <span class="role-desc">Cari & lamar lowongan</span>
                        </div>
                    </label>
                    <label class="role-card">
                        <input type="radio" name="role" value="employer" id="role-employer" {{ old('role') === 'employer' ? 'checked' : '' }}>
                        <div class="role-card-content">
                            <div class="role-icon" style="background:#ecfdf5;color:#059669;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <span class="role-name">Rekruter</span>
                            <span class="role-desc">Pasang lowongan kerja</span>
                        </div>
                    </label>
                </div>
                @error('role')
                    <div class="error-message" style="margin-top:8px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="@error('email') input-error @enderror"
                    placeholder="nama@email.com"
                    required
                    autofocus
                >
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="@error('password') input-error @enderror"
                    placeholder="Masukkan kata sandi"
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>

        <div class="footer-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var radios = document.querySelectorAll('input[name="role"]');
            var clearBtn = document.getElementById('clear-role');

            function updateClearBtn() {
                var anyChecked = Array.from(radios).some(function(r) { return r.checked; });
                if (clearBtn) {
                    clearBtn.style.display = anyChecked ? 'inline' : 'none';
                }
            }

            radios.forEach(function(radio) {
                radio.addEventListener('change', updateClearBtn);
                radio.addEventListener('click', function() {
                    if (this.dataset.wasChecked === 'true') {
                        this.checked = false;
                        this.dataset.wasChecked = 'false';
                        updateClearBtn();
                    } else {
                        radios.forEach(function(r) { r.dataset.wasChecked = 'false'; });
                        this.dataset.wasChecked = 'true';
                    }
                });
                if (radio.checked) {
                    radio.dataset.wasChecked = 'true';
                }
            });

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    radios.forEach(function(r) {
                        r.checked = false;
                        r.dataset.wasChecked = 'false';
                    });
                    updateClearBtn();
                });
            }

            updateClearBtn();

            var el = document.getElementById('login-success-alert');
            if (el && el.textContent.trim()) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: el.textContent.trim(),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }
        });
    </script>
</body>
</html>
