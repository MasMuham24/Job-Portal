<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Job Portal - Platform Karir Profesional')</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (Play CDN with Fallback & Vite support) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', '-apple-system', 'BlinkMacSystemFont', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-full flex flex-col text-slate-900 bg-slate-50 antialiased selection:bg-blue-100 selection:text-blue-700">

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Left: Logo & Nav Links -->
                <div class="flex items-center gap-8">
                    <!-- Brand Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-blue-700 to-blue-500 flex items-center justify-center text-white shadow-md shadow-blue-500/20 group-hover:scale-105 transition-transform duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">
                            Job<span class="text-blue-600">Portal</span>
                        </span>
                    </a>

                    <!-- Navigation Links (Desktop) -->
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Dashboard
                        </a>

                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('admin.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                Admin Panel
                            </a>
                        @endif

                        @if(Auth::check() && Auth::user()->role === 'employer')
                            @if(Auth::user()->company)
                                <a href="{{ route('company.show') }}" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('company.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                    Perusahaan Saya
                                </a>
                                <a href="{{ route('job-postings.index') }}" class="px-3.5 py-2 text-sm font-medium rounded-lg transition-colors {{ request()->routeIs('job-postings.*') ? 'text-blue-600 bg-blue-50 font-semibold' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                                    Kelola Lowongan
                                </a>
                            @else
                                <a href="{{ route('company.create') }}" class="px-3 py-1.5 text-xs font-semibold rounded-lg text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-xs flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    <span>Buat Profil Perusahaan</span>
                                </a>
                            @endif
                        @endif

                        <a href="#jobs" class="px-3.5 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 rounded-lg transition-colors">
                            Lowongan Kerja
                        </a>
                    </div>
                </div>

                <!-- Right: User Profile & Actions -->
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="flex items-center gap-3 pl-4 border-l border-slate-200">
                            <!-- User Initial Avatar -->
                            <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs flex items-center justify-center uppercase shadow-inner">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="text-sm font-semibold text-slate-900 leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-[11px] font-medium text-slate-500 capitalize flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full {{ Auth::user()->role === 'admin' ? 'bg-red-500' : (Auth::user()->role === 'employer' ? 'bg-emerald-500' : 'bg-blue-500') }}"></span>
                                    @if(Auth::user()->role === 'admin')
                                        Administrator
                                    @elseif(Auth::user()->role === 'employer')
                                        Perusahaan / Employer
                                    @else
                                        Pencari Kerja
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Logout Form -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" title="Keluar dari akun" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:text-red-600 hover:bg-red-50 rounded-lg border border-slate-200 hover:border-red-200 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Masuk</a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm">Daftar</a>
                    @endauth
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex md:hidden items-center gap-2">
                    <button id="mobile-menu-button" type="button" class="p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu Dropdown -->
        <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 pt-3 pb-4 space-y-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                Dashboard
            </a>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('admin.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                    Admin Panel
                </a>
            @endif
            @if(Auth::check() && Auth::user()->role === 'employer')
                @if(Auth::user()->company)
                    <a href="{{ route('company.show') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('company.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                        Perusahaan Saya
                    </a>
                    <a href="{{ route('job-postings.index') }}" class="block px-3 py-2 text-base font-medium rounded-lg {{ request()->routeIs('job-postings.*') ? 'bg-blue-50 text-blue-600 font-semibold' : 'text-slate-700 hover:bg-slate-100' }}">
                        Kelola Lowongan
                    </a>
                @else
                    <a href="{{ route('company.create') }}" class="block px-3 py-2 text-base font-semibold text-blue-600 hover:bg-blue-50 rounded-lg">
                        + Buat Profil Perusahaan
                    </a>
                @endif
            @endif
            <a href="#jobs" class="block px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-100 rounded-lg">
                Lowongan Kerja
            </a>

            @auth
                <div class="pt-3 mt-3 border-t border-slate-200">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs flex items-center justify-center uppercase">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-slate-500 capitalize">{{ Auth::user()->role }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-2 px-3">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-sm font-medium text-red-600 hover:text-red-700">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main Content Area -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-slate-800">Job<span class="text-blue-600">Portal</span></span>
                <span class="text-xs text-slate-400">© {{ date('Y') }} Hak Cipta Dilindungi.</span>
            </div>
            <div class="flex items-center gap-6 text-xs text-slate-500">
                <a href="#" class="hover:text-slate-800 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-slate-800 transition-colors">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-slate-800 transition-colors">Bantuan Employer</a>
            </div>
        </div>
    </footer>

    <!-- Mobile Menu Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    menu.classList.toggle('hidden');
                });
            }

            // SweetAlert2: Session Flash Messages
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: {!! json_encode(session('success')) !!},
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: {!! json_encode(session('error')) !!},
                    timer: 4000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            @endif

            // SweetAlert2: Confirm Dialogs
            document.querySelectorAll('[data-confirm]').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    var form = this.closest('form');
                    var title = this.getAttribute('data-confirm-title') || 'Konfirmasi';
                    var text = this.getAttribute('data-confirm');
                    var icon = this.getAttribute('data-confirm-icon') || 'warning';
                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#2563eb',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal'
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
