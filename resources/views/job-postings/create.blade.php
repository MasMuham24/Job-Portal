@extends('layouts.app')

@section('title', 'Tambah Lowongan - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
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
            <a href="{{ route('job-postings.index') }}" class="hover:text-blue-600 transition-colors">Lowongan</a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span class="text-slate-900 font-semibold">Tambah Baru</span>
        </nav>
    </div>

    {{-- Page Title --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Buat Lowongan Baru</h1>
        <p class="mt-2 text-sm text-slate-600">Isi detail lowongan kerja yang ingin Anda posting.</p>
    </div>

    {{-- Validation Errors --}}
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

    {{-- Form Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-10">
        <form action="{{ route('job-postings.store') }}" method="POST" class="space-y-8">
            @csrf

            {{-- Section 1: Job Details --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Detail Pekerjaan</h2>
                    <p class="text-xs text-slate-500">Informasi utama tentang posisi yang ditawarkan.</p>
                </div>

                <div class="space-y-6">
                    {{-- Job Title --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Judul Lowongan <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="Contoh: Senior Frontend Developer"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('title') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('title')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Deskripsi Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description"
                                  id="description"
                                  rows="6"
                                  required
                                  placeholder="Jelaskan tanggung jawab, kualifikasi, dan benefit dari posisi ini..."
                                  class="w-full px-4 py-3 rounded-xl border {{ $errors->has('description') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400 leading-relaxed">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Location & Employment Type --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="location" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="location"
                                   id="location"
                                   value="{{ old('location') }}"
                                   required
                                   placeholder="Contoh: Jakarta Selatan / Remote"
                                   class="w-full px-4 py-3 rounded-xl border {{ $errors->has('location') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                            @error('location')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="employment_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                                Tipe Pekerjaan <span class="text-red-500">*</span>
                            </label>
                            <select name="employment_type"
                                    id="employment_type"
                                    required
                                    class="w-full px-4 py-3 rounded-xl border {{ $errors->has('employment_type') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all bg-white">
                                <option value="">Pilih tipe pekerjaan</option>
                                <option value="Full-time" {{ old('employment_type') === 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                <option value="Part-time" {{ old('employment_type') === 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                <option value="Contract" {{ old('employment_type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                                <option value="Freelance" {{ old('employment_type') === 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                <option value="Internship" {{ old('employment_type') === 'Internship' ? 'selected' : '' }}>Internship</option>
                            </select>
                            @error('employment_type')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ $message }}</span>
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section 2: Salary --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Gaji</h2>
                    <p class="text-xs text-slate-500">Rentang gaji untuk posisi ini (opsional).</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label for="salary_min" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Gaji Minimum (IDR)
                        </label>
                        <input type="number"
                               name="salary_min"
                               id="salary_min"
                               value="{{ old('salary_min') }}"
                               min="0"
                               placeholder="Contoh: 5000000"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('salary_min') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('salary_min')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="salary_max" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Gaji Maksimum (IDR)
                        </label>
                        <input type="number"
                               name="salary_max"
                               id="salary_max"
                               value="{{ old('salary_max') }}"
                               min="0"
                               placeholder="Contoh: 10000000"
                               class="w-full px-4 py-3 rounded-xl border {{ $errors->has('salary_max') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all placeholder:text-slate-400">
                        @error('salary_max')
                            <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Deadline --}}
            <div>
                <div class="border-b border-slate-100 pb-3 mb-6">
                    <h2 class="text-base font-bold text-slate-900">Batas Waktu</h2>
                    <p class="text-xs text-slate-500">Kapan lowongan ini ditutup (opsional).</p>
                </div>

                <div class="max-w-sm">
                    <label for="deadline" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Deadline Lamaran
                    </label>
                    <input type="date"
                           name="deadline"
                           id="deadline"
                           value="{{ old('deadline') }}"
                           min="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('deadline') ? 'border-red-400 focus:ring-red-400' : 'border-slate-300 focus:border-blue-500 focus:ring-blue-500/20' }} text-slate-900 text-sm focus:outline-none focus:ring-4 transition-all">
                    @error('deadline')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ $message }}</span>
                        </p>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('job-postings.index') }}" class="w-full sm:w-auto px-6 py-3 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm text-center transition-colors">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-sm shadow-sm shadow-blue-500/25 hover:shadow transition-all focus:outline-none focus:ring-4 focus:ring-blue-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Publikasikan Lowongan</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
