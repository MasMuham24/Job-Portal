<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Job Portal</title>
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
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: #2563eb;
            text-decoration: none;
        }
        .btn-logout {
            background: #ef4444;
            color: #ffffff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-logout:hover {
            background: #dc2626;
        }
        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .welcome-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            color: #0f172a;
        }
        .welcome-card p {
            color: #64748b;
            margin-bottom: 24px;
        }
        .user-info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .user-info-table td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.95rem;
        }
        .user-info-table td.label {
            color: #64748b;
            font-weight: 500;
            width: 160px;
        }
        .user-info-table td.value {
            color: #0f172a;
            font-weight: 600;
        }
        .role-badge {
            display: inline-block;
            padding: 4px 12px;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 9999px;
            font-size: 0.82rem;
            font-weight: 600;
            text-transform: capitalize;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('dashboard') }}" class="brand">Job Portal</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </nav>

    <main class="container">
        <div class="welcome-card">
            <h2>Dashboard Sementara</h2>
            <p>Selamat datang di sistem autentikasi manual Job Portal.</p>

            <table class="user-info-table">
                <tr>
                    <td class="label">Nama Pengguna</td>
                    <td class="value">{{ Auth::user()->name }}</td>
                </tr>
                <tr>
                    <td class="label">Alamat Email</td>
                    <td class="value">{{ Auth::user()->email }}</td>
                </tr>
                <tr>
                    <td class="label">Role Akun</td>
                    <td class="value">
                        <span class="role-badge">{{ Auth::user()->role }}</span>
                    </td>
                </tr>
            </table>

            @if(Auth::user()->role === 'employer')
                <div style="margin: 24px 0; padding: 20px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px;">
                    <h3 style="font-size: 1.1rem; font-weight: 700; color: #1e40af; margin-bottom: 6px;">Manajemen Perusahaan (Employer)</h3>
                    @if(Auth::user()->company)
                        <p style="font-size: 0.9rem; color: #1e3a8a; margin-bottom: 16px;">
                            Perusahaan terdaftar: <strong>{{ Auth::user()->company->name }}</strong>
                        </p>
                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <a href="{{ route('company.show') }}" style="display: inline-block; padding: 8px 16px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.88rem;">Lihat Halaman Perusahaan</a>
                            <a href="{{ route('company.edit') }}" style="display: inline-block; padding: 8px 16px; background: #ffffff; color: #2563eb; border: 1px solid #2563eb; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.88rem;">Edit Profil Perusahaan</a>
                        </div>
                    @else
                        <p style="font-size: 0.9rem; color: #1e3a8a; margin-bottom: 16px;">
                            Anda belum membuat profil perusahaan. Buat sekarang agar dapat mulai memasang lowongan kerja.
                        </p>
                        <a href="{{ route('company.create') }}" style="display: inline-block; padding: 8px 16px; background: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.88rem;">+ Buat Profil Perusahaan</a>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    </main>
</body>
</html>
