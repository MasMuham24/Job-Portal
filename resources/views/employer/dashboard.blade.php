@extends('layouts.app')

@section('title', 'Employer Dashboard - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Employer Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Selamat datang, {{ Auth::user()->name }}! Kelola lowongan kerja perusahaan Anda dari sini.</p>
    </div>

    @if(!$hasCompany)
        {{-- No Company Profile --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-8 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-slate-900 mb-2">Buat Profil Perusahaan</h2>
            <p class="text-sm text-slate-500 mb-6">Anda belum memiliki profil perusahaan. Buat profil perusahaan terlebih dahulu untuk dapat memposting lowongan kerja.</p>
            <a href="{{ route('company.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                Buat Profil Perusahaan
            </a>
        </div>
    @else
        {{-- Statistics Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">

            {{-- Total Lowongan --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Total Lowongan</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $totalJobs }}</p>
            </div>

            {{-- Lowongan Aktif --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Aktif</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $activeJobs }}</p>
            </div>

            {{-- Lowongan Pending --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Menunggu</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $pendingJobs }}</p>
            </div>

            {{-- Lowongan Ditutup --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Ditutup</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $closedJobs }}</p>
            </div>

            {{-- Total Lamaran --}}
            <a href="{{ route('employer.applications.index') }}" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-indigo-200 hover:shadow-sm transition-all group block">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Total Lamaran</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $totalApplications }}</p>
            </a>

            {{-- Lamaran Menunggu Review --}}
            <a href="{{ route('employer.applications.index', ['status' => 'pending']) }}" class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-violet-200 hover:shadow-sm transition-all group block">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-50 group-hover:bg-violet-100 flex items-center justify-center transition-colors">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <span class="text-xs font-medium text-slate-500">Menunggu Review</span>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">{{ $pendingApplications }}</p>
            </a>
        </div>

        {{-- Recent Job Postings --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden mb-10">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">Lowongan Terbaru</h2>
                @if($totalJobs > 0)
                    <a href="{{ route('job-postings.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lihat Semua</a>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat</th>
                            <th class="text-right px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentJobs as $job)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <span class="font-semibold text-slate-900 truncate max-w-[200px] block">{{ $job->title }}</span>
                                </td>
                                <td class="px-6 py-3 text-slate-500">{{ $job->location }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                                        {{ $job->status === 'active' ? 'bg-green-50 text-green-700' : ($job->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-slate-400 text-xs whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('job-postings.show', $job) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('job-postings.edit', $job) }}" class="p-1.5 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-slate-500 mb-1">Belum ada lowongan</p>
                                        <p class="text-xs text-slate-400 mb-4">Mulai posting lowongan kerja untuk menarik kandidat terbaik.</p>
                                        <a href="{{ route('job-postings.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                            Tambah Lowongan
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <h2 class="text-base font-bold text-slate-900 mb-4">Aksi Cepat</h2>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('job-postings.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Tambah Lowongan
                </a>
                <a href="{{ route('job-postings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    Kelola Lowongan
                </a>
                <a href="{{ route('employer.applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    Review Lamaran Masuk
                </a>
                <a href="{{ route('company.show') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Kelola Perusahaan
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
