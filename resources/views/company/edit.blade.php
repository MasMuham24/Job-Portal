@extends('layouts.app')

@section('title', 'Edit Profil ' . $company->name . ' - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb Navigation --}}
    <div class="mb-6">
        <nav class="flex items-center text-sm font-medium text-slate-500 gap-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <a href="{{ route('company.show') }}" class="hover:text-blue-600 transition-colors truncate max-w-xs">
                {{ $company->name }}
            </a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-semibold">Edit Profil</span>
        </nav>
    </div>

    {{-- Page Title Section --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Edit Profil Perusahaan
            </h1>
            <p class="mt-2 text-sm sm:text-base text-slate-600">
                Perbarui informasi perusahaan Anda.
            </p>
        </div>
        <a href="{{ route('company.show') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 px-3.5 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors shadow-2xs self-start">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <span>Lihat Halaman Publik</span>
        </a>
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
        <form action="{{ route('company.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            {{-- Section 1: Basic Company Info --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Informasi Utama Perusahaan</h2>
                    <p class="text-xs text-slate-500">Nama resmi dan identitas yang akan dilihat para pencari kerja.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Company Name --}}
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nama Perusahaan <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $company->name) }}" 
                                   required
                                   placeholder="Contoh: PT Digital Nusantara Teknologi" 
                                   class="w-full px-4 py-3 rounded-xl border {{ $errors->has('name') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        </div>
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Location --}}
                    <div>
                        <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Lokasi / Kantor Pusat
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </span>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   value="{{ old('location', $company->location) }}" 
                                   placeholder="Contoh: Jakarta Selatan, Indonesia" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('location') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        </div>
                        @error('location')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Logo URL --}}
                    <div>
                        <label for="logo" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            URL Logo Perusahaan
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </span>
                            <input type="text" 
                                   name="logo" 
                                   id="logo" 
                                   value="{{ old('logo', $company->logo) }}" 
                                   placeholder="https://example.com/logo.png" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('logo') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        </div>
                        <span class="block text-[11px] text-slate-400 mt-1">Masukkan link gambar logo perusahaan Anda.</span>
                        @error('logo')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Contact & Online Presence --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Kontak & Kehadiran Online</h2>
                    <p class="text-xs text-slate-500">Membantu kandidat mencari tahu lebih dalam tentang perusahaan Anda.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    {{-- Website --}}
                    <div>
                        <label for="website" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Website Perusahaan
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                            </span>
                            <input type="url" 
                                   name="website" 
                                   id="website" 
                                   value="{{ old('website', $company->website) }}" 
                                   placeholder="https://www.digitalnusantara.id" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('website') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        </div>
                        <span class="block text-[11px] text-slate-400 mt-1">Harus diawali dengan http:// atau https://</span>
                        @error('website')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Nomor Telepon
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </span>
                            <input type="text" 
                                   name="phone" 
                                   id="phone" 
                                   value="{{ old('phone', $company->phone) }}" 
                                   placeholder="Contoh: 021-5551234 atau +6281234567890" 
                                   class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('phone') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        </div>
                        @error('phone')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Company Description / About --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Tentang Perusahaan</h2>
                    <p class="text-xs text-slate-500">Ceritakan visi misi, budaya kerja, dan profil usaha Anda.</p>
                </div>

                <div>
                    <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deskripsi Lengkap
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="6" 
                              placeholder="Tuliskan tentang sejarah, bidang industri, spesialisasi produk/jasa, serta keunggulan bekerja di perusahaan Anda..." 
                              class="w-full px-4 py-3 rounded-xl border {{ $errors->has('description') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400 leading-relaxed">{{ old('description', $company->description) }}</textarea>
                    @error('description')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('company.show') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm text-center transition-colors">
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
