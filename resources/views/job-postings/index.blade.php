@extends('layouts.app')

@section('title', 'Kelola Lowongan - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

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
            <span class="text-slate-900 font-semibold">Kelola Lowongan</span>
        </nav>
    </div>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lowongan Kerja</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola semua lowongan yang telah Anda posting.</p>
        </div>
        <a href="{{ route('job-postings.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Lowongan
        </a>
    </div>

    {{-- Job Listings --}}
    @if($jobs->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-5 rounded-2xl bg-slate-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Belum ada lowongan</h3>
            <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">Anda belum memposting lowongan kerja apapun. Mulai buat lowongan pertama untuk menarik kandidat terbaik.</p>
            <a href="{{ route('job-postings.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Lowongan Pertama
            </a>
        </div>
    @else
        {{-- Desktop Table --}}
        <div class="hidden md:block bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/60">
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Lowongan</th>
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe Kerja</th>
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Gaji</th>
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="text-left px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Dibuat</th>
                            <th class="text-right px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($jobs as $job)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('job-postings.show', $job) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">{{ $job->title }}</a>
                                </td>
                                <td class="px-6 py-4 text-slate-600">{{ $job->location }}</td>
                                <td class="px-6 py-4 text-slate-600">{{ $job->employment_type }}</td>
                                <td class="px-6 py-4 text-slate-600">
                                    @if($job->salary_min || $job->salary_max)
                                        @if($job->salary_min && $job->salary_max)
                                            Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                                        @elseif($job->salary_min)
                                            Rp {{ number_format($job->salary_min, 0, ',', '.') }}+
                                        @else
                                            Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                                        @endif
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($job->status === 'active')
                                        <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                                    @elseif($job->status === 'pending')
                                        <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-amber-50 text-amber-700">Menunggu</span>
                                    @elseif($job->status === 'closed')
                                        <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">Ditutup</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">{{ ucfirst($job->status) }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('job-postings.show', $job) }}" class="p-2 rounded-lg text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Lihat">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <a href="{{ route('job-postings.edit', $job) }}" class="p-2 rounded-lg text-slate-500 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        </a>
                                        <form action="{{ route('job-postings.destroy', $job) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-500 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus" data-confirm="Apakah Anda yakin ingin menghapus lowongan ini?" data-confirm-title="Hapus Lowongan?" data-confirm-icon="error">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile Cards --}}
        <div class="md:hidden space-y-4">
            @foreach($jobs as $job)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <a href="{{ route('job-postings.show', $job) }}" class="font-bold text-slate-900 hover:text-blue-600 transition-colors">{{ $job->title }}</a>
                        @if($job->status === 'active')
                            <span class="shrink-0 inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-green-50 text-green-700">Aktif</span>
                        @elseif($job->status === 'pending')
                            <span class="shrink-0 inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-amber-50 text-amber-700">Menunggu</span>
                        @else
                            <span class="shrink-0 inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full bg-slate-100 text-slate-600">{{ ucfirst($job->status) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1.5 text-sm text-slate-600 mb-4">
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $job->location }}
                        </p>
                        <p class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            {{ $job->employment_type }}
                        </p>
                        @if($job->salary_min || $job->salary_max)
                            <p class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                @if($job->salary_min && $job->salary_max)
                                    Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                                @elseif($job->salary_min)
                                    Rp {{ number_format($job->salary_min, 0, ',', '.') }}+
                                @else
                                    Hingga Rp {{ number_format($job->salary_max, 0, ',', '.') }}
                                @endif
                            </p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 pt-3 border-t border-slate-100">
                        <a href="{{ route('job-postings.show', $job) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            Lihat
                        </a>
                        <a href="{{ route('job-postings.edit', $job) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                            Edit
                        </a>
                        <form action="{{ route('job-postings.destroy', $job) }}" method="POST" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors" data-confirm="Apakah Anda yakin ingin menghapus lowongan ini?" data-confirm-title="Hapus Lowongan?" data-confirm-icon="error">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($jobs->hasPages())
            <div class="mt-8">
                {{ $jobs->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
