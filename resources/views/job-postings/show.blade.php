@extends('layouts.app')

@section('title', $jobPosting->title . ' - Job Portal')

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
            <span class="text-slate-900 font-semibold truncate max-w-[200px]">{{ $jobPosting->title }}</span>
        </nav>
    </div>

    {{-- Job Detail Card --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">

        {{-- Header --}}
        <div class="p-6 sm:p-10 border-b border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-3 mb-2">
                        @if($jobPosting->status === 'active')
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                        @elseif($jobPosting->status === 'pending')
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-amber-50 text-amber-700">Menunggu</span>
                        @elseif($jobPosting->status === 'closed')
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">Ditutup</span>
                        @else
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">{{ ucfirst($jobPosting->status) }}</span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $jobPosting->title }}</h1>
                    <p class="mt-1 text-sm text-slate-500">{{ $jobPosting->company->name ?? 'N/A' }}</p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('job-postings.edit', $jobPosting) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        Edit
                    </a>
                    <form action="{{ route('job-postings.destroy', $jobPosting) }}" method="POST" class="inline" onsubmit="return false">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl border border-red-200 hover:bg-red-50 text-red-700 text-sm font-semibold transition-colors" data-confirm="Apakah Anda yakin ingin menghapus lowongan ini?" data-confirm-title="Hapus Lowongan?" data-confirm-icon="error">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Job Info Grid --}}
        <div class="p-6 sm:p-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Lokasi</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->location }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tipe Pekerjaan</p>
                        <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->employment_type }}</p>
                    </div>
                </div>

                @if($jobPosting->salary_min || $jobPosting->salary_max)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Gaji</p>
                            <p class="text-sm font-semibold text-slate-900">
                                @if($jobPosting->salary_min && $jobPosting->salary_max)
                                    Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }} - {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                                @elseif($jobPosting->salary_min)
                                    Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }}+
                                @else
                                    Hingga Rp {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                @if($jobPosting->deadline)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Deadline</p>
                            <p class="text-sm font-semibold text-slate-900">{{ \Carbon\Carbon::parse($jobPosting->deadline)->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div>
                <h2 class="text-base font-bold text-slate-900 mb-3">Deskripsi Pekerjaan</h2>
                <div class="prose prose-sm prose-slate max-w-none text-slate-700 leading-relaxed whitespace-pre-line">{{ $jobPosting->description }}</div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 sm:px-10 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
            <p>Dibuat {{ $jobPosting->created_at->diffForHumans() }} &middot; Diperbarui {{ $jobPosting->updated_at->diffForHumans() }}</p>
            <a href="{{ route('job-postings.index') }}" class="inline-flex items-center gap-1.5 hover:text-blue-600 transition-colors font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali ke Daftar Lowongan
            </a>
        </div>
    </div>

</div>
@endsection
