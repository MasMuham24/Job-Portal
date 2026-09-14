@extends('layouts.app')

@section('title', 'Edit Pengguna - Admin - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Daftar Pengguna
        </a>
    </div>

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Edit Pengguna</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui informasi akun pengguna.</p>
    </div>

    {{-- Validation Error Alert Banner --}}
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-900">Harap periksa kembali isian formulir:</h3>
                    <ul class="mt-1 list-disc list-inside text-xs text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Section 1: Account Info --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Informasi Akun</h2>
                    <p class="text-xs text-slate-500">Perbarui data dasar akun pengguna.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name', $user->name) }}"
                               required
                               placeholder="Contoh: Budi Santoso"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Alamat Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               id="email"
                               value="{{ old('email', $user->email) }}"
                               required
                               placeholder="nama@email.com"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('email') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Kata Sandi Baru
                        </label>
                        <input type="password"
                               name="password"
                               id="password"
                               placeholder="Kosongkan jika tidak diubah"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('password') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('password')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               id="password_confirmation"
                               placeholder="Ulangi kata sandi baru"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-blue-500 focus:ring-blue-500/20 text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                    </div>
                </div>
            </div>

            {{-- Section 2: Role --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Role Pengguna</h2>
                    <p class="text-xs text-slate-500">Tentukan peran pengguna dalam sistem.</p>
                </div>

                <div>
                    <label for="role" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role"
                            id="role"
                            required
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('role') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all bg-white">
                        <option value="job_seeker" {{ old('role', $user->role) === 'job_seeker' ? 'selected' : '' }}>Pencari Kerja (Job Seeker)</option>
                        <option value="employer" {{ old('role', $user->role) === 'employer' ? 'selected' : '' }}>Rekruter (Employer)</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrator</option>
                    </select>
                    @error('role')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm text-center transition-colors">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm shadow-sm shadow-blue-500/25 hover:shadow transition-all focus:outline-none focus:ring-4 focus:ring-blue-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
