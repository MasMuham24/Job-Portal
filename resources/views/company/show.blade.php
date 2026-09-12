@extends('layouts.app')

@section('title', $company->name . ' - Profil Perusahaan | Job Portal')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Breadcrumb / Quick Back --}}
    <div class="mb-6 flex items-center justify-between">
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
            <span class="text-slate-900 font-semibold truncate max-w-xs">{{ $company->name }}</span>
        </nav>

        <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-900 px-3 py-1.5 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 shadow-2xs transition-colors">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Dashboard</span>
        </a>
    </div>

    {{-- 1. MAIN COMPANY HEADER CARD --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        {{-- Cover Banner Image with modern geometric / gradient style --}}
        <div class="h-36 sm:h-48 w-full bg-gradient-to-r from-slate-900 via-indigo-950 to-blue-900 relative overflow-hidden">
            {{-- Subtle overlay pattern --}}
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
            <div class="absolute top-4 right-4 bg-black/40 backdrop-blur-md px-3 py-1 rounded-full text-xs font-medium text-slate-200 flex items-center gap-1.5 border border-white/10">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Employer Active Profile</span>
            </div>
        </div>

        {{-- Company Info & Logo Bar --}}
        <div class="px-6 sm:px-8 pb-6 sm:pb-8 pt-0 relative">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 -mt-14 sm:-mt-16 mb-5">
                {{-- Logo or Initial Fallback --}}
                <div class="flex items-end gap-5">
                    @php
                        $words = preg_split('/\s+/', trim($company->name));
                        $initials = '';
                        if (count($words) >= 2) {
                            $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                        } else {
                            $initials = strtoupper(substr($company->name, 0, min(2, strlen($company->name))));
                        }
                    @endphp

                    <div class="relative group shrink-0">
                        @if(!empty($company->logo))
                            <img src="{{ $company->logo }}" 
                                 alt="{{ $company->name }} Logo" 
                                 class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl object-cover bg-white p-1 border-4 border-white shadow-md shadow-slate-200 ring-1 ring-slate-200/60"
                                 onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl border-4 border-white shadow-md\'>{{ $initials }}</div>';">
                        @else
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl border-4 border-white shadow-md shadow-blue-500/10 ring-1 ring-slate-200/60 tracking-wider select-none">
                                {{ $initials }}
                            </div>
                        @endif
                    </div>

                    <div class="pt-2 sm:pt-0 hidden sm:block">
                        <div class="flex items-center gap-2">
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                {{ $company->name }}
                            </h1>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60" title="Profil Perusahaan Resmi">
                                <svg class="w-3 h-3 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Terverifikasi
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                            Profil Perusahaan Anda (Mode Employer)
                        </p>
                    </div>
                </div>

                {{-- Action CTA: Edit Company Button --}}
                <div class="flex items-center gap-3 self-start sm:self-end pt-3 sm:pt-0">
                    <a href="{{ route('company.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl shadow-sm shadow-blue-500/20 hover:shadow transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Edit Perusahaan</span>
                    </a>
                </div>
            </div>

            {{-- Title for Mobile Screens --}}
            <div class="sm:hidden mb-4">
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">
                        {{ $company->name }}
                    </h1>
                    <span class="inline-flex items-center p-1 rounded-full text-blue-600 bg-blue-50 border border-blue-200/60" title="Terverifikasi">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-medium mt-1">Profil Perusahaan Anda (Mode Employer)</p>
            </div>

            {{-- Key Meta Quick Badges --}}
            <div class="flex flex-wrap items-center gap-y-2 gap-x-5 text-xs sm:text-sm text-slate-600 pt-2 border-t border-slate-100">
                @if(!empty($company->location))
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>{{ $company->location }}</span>
                    </div>
                @endif

                @if(!empty($company->website))
                    <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-1.5 text-blue-600 hover:text-blue-700 hover:underline font-medium transition-colors">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <span class="truncate max-w-xs">{{ preg_replace('#^https?://#', '', rtrim($company->website, '/')) }}</span>
                        <svg class="w-3 h-3 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                @endif

                @if(!empty($company->phone))
                    <div class="flex items-center gap-1.5 text-slate-600">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $company->phone }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. LINKEDIN-INSPIRED COMPANY NAVIGATION TABS --}}
        <div class="border-t border-slate-200/80 bg-slate-50/70 px-6 sm:px-8 flex items-center gap-8 overflow-x-auto">
            <button type="button" id="tab-btn-overview" onclick="switchCompanyTab('overview')" class="company-tab-btn py-3.5 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Overview</span>
            </button>

            <button type="button" id="tab-btn-jobs" onclick="switchCompanyTab('jobs')" class="company-tab-btn py-3.5 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span>Lowongan Kerja</span>
                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-200/80 text-slate-700">0</span>
            </button>
        </div>
    </div>

    {{-- 3. MAIN CONTENT GRID (2 Columns on desktop, 1 column on mobile) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- LEFT COLUMN: PRIMARY CONTENT --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- TAB PANE: OVERVIEW --}}
            <div id="pane-overview" class="space-y-6">
                {{-- About Company Section Card --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-slate-900">Tentang Perusahaan</h2>
                        </div>
                        <a href="{{ route('company.edit') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                            <span>Perbarui</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    @if(!empty(trim($company->description ?? '')))
                        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4 whitespace-pre-line">
{{ $company->description }}
                        </div>
                    @else
                        {{-- Professional Empty State for Description --}}
                        <div class="rounded-xl bg-slate-50 border border-dashed border-slate-300 p-8 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-500 mx-auto flex items-center justify-center mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 mb-1">Deskripsi Perusahaan Belum Ditambahkan</h3>
                            <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mb-4">
                                Berikan deskripsi yang menarik tentang visi, budaya, dan keunggulan perusahaan Anda agar kandidat tertarik untuk melamar.
                            </p>
                            <a href="{{ route('company.edit') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 bg-white border border-slate-200 hover:bg-blue-50 hover:border-blue-200 px-3.5 py-2 rounded-lg shadow-2xs transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                <span>Tambah Deskripsi Perusahaan</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Jobs Preview / Card on Overview --}}
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Lowongan Terbuka</h2>
                                <p class="text-xs text-slate-500">Lowongan yang sedang aktif dari {{ $company->name }}</p>
                            </div>
                        </div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-600">
                            0 Lowongan
                        </span>
                    </div>

                    {{-- Empty State for Jobs --}}
                    <div class="rounded-xl bg-slate-50/80 border border-dashed border-slate-200 p-8 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Lowongan yang Dipublikasikan</h3>
                        <p class="text-xs sm:text-sm text-slate-500 max-w-sm mx-auto mb-4">
                            Publikasikan lowongan pekerjaan pertama Anda untuk mulai menerima lamaran dari talenta terbaik.
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3.5 py-2 rounded-lg border border-emerald-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Kelola Lowongan di Dashboard</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- TAB PANE: JOBS (Standalone tab view) --}}
            <div id="pane-jobs" class="hidden space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Daftar Lowongan Pekerjaan</h2>
                            <p class="text-sm text-slate-500">Kelola dan lihat lowongan kerja yang dibuka oleh {{ $company->name }}</p>
                        </div>
                    </div>

                    <div class="py-12 text-center">
                        <div class="w-16 h-16 rounded-3xl bg-blue-50 text-blue-600 mx-auto flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900 mb-1">Tidak Ada Lowongan Aktif</h3>
                        <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">
                            Anda belum menerbitkan lowongan pekerjaan. Anda dapat membuat lowongan baru melalui menu manajemen rekrutmen.
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Buka Lowongan Baru</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT COLUMN: SIDEBAR DETAILS & EMPLOYER ACTIONS --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- Card 1: Company Information Quick Specs --}}
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Informasi Perusahaan</h3>
                    <a href="{{ route('company.edit') }}" class="text-xs font-semibold text-blue-600 hover:underline">Edit</a>
                </div>

                <ul class="space-y-4 text-sm">
                    {{-- Location --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="block text-xs font-medium text-slate-400">Lokasi / Kantor Pusat</span>
                            <span class="font-medium text-slate-800">
                                {{ !empty($company->location) ? $company->location : 'Belum ditentukan' }}
                            </span>
                        </div>
                    </li>

                    {{-- Website --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <span class="block text-xs font-medium text-slate-400">Situs Web</span>
                            @if(!empty($company->website))
                                <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="font-medium text-blue-600 hover:underline truncate block">
                                    {{ $company->website }}
                                </a>
                            @else
                                <span class="font-medium text-slate-400 italic">Belum ditentukan</span>
                            @endif
                        </div>
                    </li>

                    {{-- Phone --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="block text-xs font-medium text-slate-400">Nomor Telepon</span>
                            <span class="font-medium text-slate-800">
                                {{ !empty($company->phone) ? $company->phone : 'Belum ditentukan' }}
                            </span>
                        </div>
                    </li>

                    {{-- Created At --}}
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <span class="block text-xs font-medium text-slate-400">Terdaftar Sejak</span>
                            <span class="font-medium text-slate-800">
                                {{ $company->created_at ? $company->created_at->translatedFormat('F Y') : 'Baru saja' }}
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            {{-- Card 2: Employer Action Hub --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-850 text-white rounded-2xl p-6 shadow-md">
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-300">Employer Hub</h3>
                </div>
                <h4 class="text-base font-bold text-white mb-2">Kelola Profil Perusahaan</h4>
                <p class="text-xs text-slate-300 leading-relaxed mb-5">
                    Halaman ini hanya dapat diakses oleh Anda sebagai pemilik resmi akun perusahaan ini.
                </p>

                <div class="space-y-2.5">
                    <a href="{{ route('company.edit') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-900 bg-white hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>Edit Profil Perusahaan</span>
                    </a>

                    <a href="{{ route('dashboard') }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-semibold text-slate-200 bg-white/10 hover:bg-white/15 rounded-xl border border-white/10 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Kembali ke Dashboard</span>
                    </a>
                </div>

                <div class="mt-5 pt-4 border-t border-white/10 flex items-center gap-2.5 text-xs text-slate-400">
                    <div class="w-6 h-6 rounded-full bg-white/10 text-slate-300 flex items-center justify-center font-bold text-[10px]">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="truncate">
                        <span class="block text-slate-200 font-medium truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[11px] text-slate-400 truncate">{{ Auth::user()->email }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@push('scripts')
<script>
    function switchCompanyTab(tab) {
        const paneOverview = document.getElementById('pane-overview');
        const paneJobs = document.getElementById('pane-jobs');
        const btnOverview = document.getElementById('tab-btn-overview');
        const btnJobs = document.getElementById('tab-btn-jobs');

        if (tab === 'overview') {
            paneOverview.classList.remove('hidden');
            paneJobs.classList.add('hidden');

            btnOverview.className = 'company-tab-btn py-3.5 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors flex items-center gap-2';
            btnJobs.className = 'company-tab-btn py-3.5 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-2';
        } else if (tab === 'jobs') {
            paneOverview.classList.add('hidden');
            paneJobs.classList.remove('hidden');

            btnJobs.className = 'company-tab-btn py-3.5 text-sm font-semibold border-b-2 border-blue-600 text-blue-600 transition-colors flex items-center gap-2';
            btnOverview.className = 'company-tab-btn py-3.5 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-900 transition-colors flex items-center gap-2';
        }
    }
</script>
@endpush
@endsection
