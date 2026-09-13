@extends('layouts.app')

@section('title', 'Admin Dashboard - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Admin Dashboard</h1>
        <p class="mt-1 text-sm text-slate-500">Selamat datang, {{ Auth::user()->name }}! Kelola seluruh aktivitas Job Portal dari sini.</p>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-10">

        {{-- Total Users --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Total Pengguna</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalUsers }}</p>
        </div>

        {{-- Employers --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Employer</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalEmployers }}</p>
        </div>

        {{-- Job Seekers --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-violet-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Pencari Kerja</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalJobSeekers }}</p>
        </div>

        {{-- Total Companies --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Perusahaan</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalCompanies }}</p>
        </div>

        {{-- Total Jobs --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Total Lowongan</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $totalJobs }}</p>
        </div>

        {{-- Active Jobs --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Lowongan Aktif</span>
            </div>
            <p class="text-2xl font-extrabold text-slate-900">{{ $activeJobs }}</p>
            @if($pendingJobs > 0)
                <p class="mt-1 text-[11px] text-amber-600 font-medium">{{ $pendingJobs }} menunggu</p>
            @endif
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Pengguna Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Terdaftar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentUsers as $user)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center uppercase shrink-0">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        <span class="font-semibold text-slate-900 truncate">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-slate-500">{{ $user->email }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                                        {{ $user->role === 'admin' ? 'bg-red-50 text-red-700' : ($user->role === 'employer' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700') }}">
                                        {{ $user->role === 'employer' ? 'Employer' : ($user->role === 'admin' ? 'Admin' : 'Pencari Kerja') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-slate-400 text-xs whitespace-nowrap">{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Jobs --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Lowongan Terbaru</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/50">
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Perusahaan</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-2.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentJobs as $job)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-3 font-semibold text-slate-900 truncate max-w-[200px]">{{ $job->title }}</td>
                                <td class="px-6 py-3 text-slate-500">{{ $job->company->name ?? 'N/A' }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                                        {{ $job->status === 'active' ? 'bg-green-50 text-green-700' : ($job->status === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-600') }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-slate-400 text-xs whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-400">Belum ada lowongan yang diposting.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div>
        <h2 class="text-base font-bold text-slate-900 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-3">
            @if(route('company.show'))
                <a href="{{ route('company.show') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    Kelola Perusahaan
                </a>
            @endif
            <a href="{{ route('admin.job-postings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                Kelola Lowongan
            </a>
            @if($pendingJobs > 0)
                <a href="{{ route('admin.job-postings.index', ['status' => 'pending']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Tinjau Pending ({{ $pendingJobs }})
                </a>
            @endif
        </div>
    </div>

</div>
@endsection
