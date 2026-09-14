<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Job Portal</title>
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
            max-width: 480px;
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
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="url"] {
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
        .company-section {
            display: none;
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px dashed #e2e8f0;
        }
        .company-section.visible {
            display: block;
        }
        .company-section-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <h1>Daftar Akun</h1>
            <p>Buat akun baru untuk memulai di Job Portal</p>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <strong>Terjadi kesalahan input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="role-section">
                <label>Pilih Tipe Akun</label>
                <div class="role-options">
                    <label class="role-card">
                        <input type="radio" name="role" value="job_seeker" {{ old('role') === 'job_seeker' || old('role') === '' ? 'checked' : '' }} required>
                        <div class="role-card-content">
                            <div class="role-icon" style="background:#eff6ff;color:#2563eb;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                            <span class="role-name">Pencari Kerja</span>
                            <span class="role-desc">Cari lowongan pekerjaan</span>
                        </div>
                    </label>
                    <label class="role-card">
                        <input type="radio" name="role" value="employer" {{ old('role') === 'employer' ? 'checked' : '' }} required>
                        <div class="role-card-content">
                            <div class="role-icon" style="background:#ecfdf5;color:#059669;">
                                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <span class="role-name">Rekruter</span>
                            <span class="role-desc">Posting lowongan kerja</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}" 
                    class="@error('name') input-error @enderror"
                    placeholder="Contoh: John Doe" 
                    required
                >
                @error('name')
                    <div class="error-message">{{ $message }}</div>
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
                    placeholder="Minimal 8 karakter" 
                    required
                >
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="Ulangi kata sandi" 
                    required
                >
            </div>

            {{-- Company Section (only for employer) --}}
            <div class="company-section" id="company-section">
                <div class="company-section-title">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Data Perusahaan
                </div>

                <div class="form-group">
                    <label for="company_name">Nama Perusahaan <span style="color:#ef4444">*</span></label>
                    <input 
                        type="text" 
                        id="company_name" 
                        name="company_name" 
                        value="{{ old('company_name') }}" 
                        class="@error('company_name') input-error @enderror"
                        placeholder="Contoh: PT Digital Nusantara"
                    >
                    @error('company_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_location">Lokasi</label>
                    <input 
                        type="text" 
                        id="company_location" 
                        name="company_location" 
                        value="{{ old('company_location') }}" 
                        class="@error('company_location') input-error @enderror"
                        placeholder="Contoh: Jakarta Selatan"
                    >
                    @error('company_location')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_website">Website</label>
                    <input 
                        type="url" 
                        id="company_website" 
                        name="company_website" 
                        value="{{ old('company_website') }}" 
                        class="@error('company_website') input-error @enderror"
                        placeholder="https://www.example.com"
                    >
                    @error('company_website')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="company_phone">Telepon</label>
                    <input 
                        type="text" 
                        id="company_phone" 
                        name="company_phone" 
                        value="{{ old('company_phone') }}" 
                        class="@error('company_phone') input-error @enderror"
                        placeholder="021-5551234"
                    >
                    @error('company_phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <div class="footer-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var roleCards = document.querySelectorAll('input[name="role"]');
            var companySection = document.getElementById('company-section');
            var companyName = document.getElementById('company_name');

            function toggleCompany() {
                var selected = document.querySelector('input[name="role"]:checked');
                if (selected && selected.value === 'employer') {
                    companySection.classList.add('visible');
                    companyName.setAttribute('required', 'required');
                } else {
                    companySection.classList.remove('visible');
                    companyName.removeAttribute('required');
                }
            }

            roleCards.forEach(function(card) {
                card.addEventListener('change', toggleCompany);
            });

            toggleCompany();
        });
    </script>
</body>
</html>
