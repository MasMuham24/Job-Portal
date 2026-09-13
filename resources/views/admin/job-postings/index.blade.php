@extends('layouts.app')

@section('title', 'Kelola Lowongan - Admin - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-slate-900">Kelola Lowongan Kerja</h1>
        <p class="mt-1 text-sm text-slate-500">Tinjau dan moderasi seluruh lowongan kerja yang diposting oleh employer.</p>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.job-postings.index') }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ !request('status') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua <span class="ml-1.5 text-xs opacity-70">{{ $totalCount }}</span>
        </a>
        <a href="{{ route('admin.job-postings.index', ['status' => 'pending']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('status') === 'pending' ? 'bg-amber-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Pending <span class="ml-1.5 text-xs opacity-70">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.job-postings.index', ['status' => 'active']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('status') === 'active' ? 'bg-green-600 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Aktif <span class="ml-1.5 text-xs opacity-70">{{ $activeCount }}</span>
        </a>
    </div>

    {{-- Job Listings Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul Lowongan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Employer</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Lokasi</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Dibuat</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jobs as $job)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $job->status === 'pending' ? 'bg-amber-50/30' : '' }}">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.job-postings.show', $job) }}" class="font-semibold text-slate-900 hover:text-blue-600 transition-colors">
                                    {{ $job->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $job->company->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $job->company->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-slate-600">{{ $job->location }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                                    {{ $job->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $job->status === 'active' ? 'Aktif' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap">{{ $job->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.job-postings.show', $job) }}" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </a>
                                    @if($job->status === 'pending')
                                        <form action="{{ route('admin.job-postings.status', $job) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="active">
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-green-600 hover:bg-green-50 transition-colors" title="Setujui" data-confirm="Setujui lowongan ini? Lowongan akan menjadi aktif." data-confirm-title="Setujui Lowongan?" data-confirm-icon="question">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.job-postings.destroy', $job) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus" data-confirm="Hapus lowongan ini? Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus Lowongan?" data-confirm-icon="error">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Tidak ada lowongan ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($jobs->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $jobs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
