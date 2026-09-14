@extends('layouts.app')

@section('title', 'Profil Saya - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb & Title --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Profil Pencari Kerja</h1>
        <p class="text-sm text-slate-500 mt-1">Lengkapi informasi pribadi, pendidikan, dan keahlian Anda untuk mulai melamar pekerjaan.</p>
    </div>

    {{-- Profile Completion Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Kelengkapan Profil</span>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-2xl font-black text-slate-900">{{ $completionPercentage }}%</span>
                    @if($isComplete)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Profil Lengkap & Siap Melamar</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Belum Lengkap</span>
                        </span>
                    @endif
                </div>
            </div>

            @if(!$isComplete)
                <div class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-xl px-4 py-2.5 sm:max-w-xs">
                    ⚠️ <strong>Lengkapi profil Anda</strong> sebelum melamar pekerjaan. Semua field bertanda bintang (*) wajib diisi.
                </div>
            @endif
        </div>

        {{-- Visual Progress Bar --}}
        <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
            <div class="h-3 rounded-full transition-all duration-500 {{ $isComplete ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}" style="width: {{ $completionPercentage }}%"></div>
        </div>
    </div>

    {{-- Form Edit Profile --}}
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">

            {{-- 1. Data Pribadi --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    <span>Informasi Pribadi</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                        @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Email (Read-only for safety) --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed">
                        <span class="text-[11px] text-slate-400 mt-1 block">Email akun Anda.</span>
                    </div>

                    {{-- Nomor Telepon --}}
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp / Telepon <span class="text-rose-500">*</span></label>
                        <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                        @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label for="gender" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Kelamin <span class="text-rose-500">*</span></label>
                        <select id="gender" name="gender" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800 bg-white">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('gender', $user->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $user->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('gender') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div>
                        <label for="birth_date" class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir <span class="text-rose-500">*</span></label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                        @error('birth_date') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kota Domisili --}}
                    <div>
                        <label for="city" class="block text-xs font-bold text-slate-700 mb-1.5">Kota Domisili <span class="text-rose-500">*</span></label>
                        <input type="text" id="city" name="city" value="{{ old('city', $user->city) }}" placeholder="Contoh: Jakarta Selatan, Surabaya, Bandung" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                        @error('city') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Tempat Tinggal <span class="text-rose-500">*</span></label>
                        <textarea id="address" name="address" rows="2" placeholder="Tuliskan alamat lengkap tempat tinggal Anda..." required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- 2. Data Pendidikan & Keahlian --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2 pb-3 border-b border-slate-100">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                    <span>Pendidikan & Kualifikasi</span>
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Pendidikan Terakhir --}}
                    <div>
                        <label for="education" class="block text-xs font-bold text-slate-700 mb-1.5">Pendidikan Terakhir <span class="text-rose-500">*</span></label>
                        <select id="education" name="education" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800 bg-white">
                            <option value="">Pilih Pendidikan</option>
                            <option value="SMA/SMK" {{ old('education', $user->education) === 'SMA/SMK' ? 'selected' : '' }}>SMA / SMK / Sederajat</option>
                            <option value="Diploma (D3/D4)" {{ old('education', $user->education) === 'Diploma (D3/D4)' ? 'selected' : '' }}>Diploma (D3 / D4)</option>
                            <option value="Sarjana (S1)" {{ old('education', $user->education) === 'Sarjana (S1)' ? 'selected' : '' }}>Sarjana (S1)</option>
                            <option value="Magister (S2)" {{ old('education', $user->education) === 'Magister (S2)' ? 'selected' : '' }}>Magister (S2)</option>
                            <option value="Doktor (S3)" {{ old('education', $user->education) === 'Doktor (S3)' ? 'selected' : '' }}>Doktor (S3)</option>
                            <option value="Lainnya" {{ old('education', $user->education) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('education') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Nama Sekolah / Kampus --}}
                    <div>
                        <label for="school" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Sekolah / Universitas <span class="text-rose-500">*</span></label>
                        <input type="text" id="school" name="school" value="{{ old('school', $user->school) }}" placeholder="Contoh: Universitas Indonesia, SMKN 1 Jakarta" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                        @error('school') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Keahlian (Skills) --}}
                    <div class="sm:col-span-2">
                        <label for="skills" class="block text-xs font-bold text-slate-700 mb-1.5">Keahlian Utama (Skills) <span class="text-rose-500">*</span></label>
                        <textarea id="skills" name="skills" rows="2" placeholder="Contoh: PHP, Laravel, JavaScript, MySQL, Komunikasi, Manajemen Waktu (pisahkan dengan koma)" required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">{{ old('skills', $user->skills) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Tuliskan keahlian teknis dan non-teknis yang Anda miliki.</p>
                        @error('skills') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pengalaman (Opsional - Fresh Graduate friendly) --}}
                    <div class="sm:col-span-2">
                        <label for="experience" class="block text-xs font-bold text-slate-700 mb-1.5">Pengalaman Kerja / Magang / Organisasi <span class="text-xs font-normal text-slate-400">(Opsional untuk Fresh Graduate)</span></label>
                        <textarea id="experience" name="experience" rows="3" placeholder="Jelaskan riwayat pekerjaan, magang, proyek, atau organisasi yang pernah Anda ikuti..." class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">{{ old('experience', $user->experience) }}</textarea>
                        @error('experience') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Bio / Tentang Saya --}}
                    <div class="sm:col-span-2">
                        <label for="bio" class="block text-xs font-bold text-slate-700 mb-1.5">Tentang Diri / Bio Singkat <span class="text-rose-500">*</span></label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Ceritakan gambaran singkat mengenai diri Anda, minat karir, dan nilai yang dapat Anda berikan kepada perusahaan..." required class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors">
                    Kembali ke Dashboard
                </a>
                <button type="submit" class="px-7 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-98 rounded-xl shadow-sm transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
