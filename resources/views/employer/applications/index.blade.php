@extends('layouts.app')

@section('title', 'Lamaran Masuk - Employer Panel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lamaran Masuk</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola dan review seluruh kandidat yang melamar pada lowongan perusahaan Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('job-postings.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-all shadow-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span>Kelola Lowongan</span>
            </a>
        </div>
    </div>

    {{-- Filter & Search Form --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-6">
        <form method="GET" action="{{ route('employer.applications.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Search input --}}
            <div>
                <label for="search" class="block text-xs font-semibold text-slate-600 mb-1">Cari Pelamar</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" placeholder="Nama atau email pelamar..." class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800 placeholder:text-slate-400">
            </div>

            {{-- Job Filter --}}
            <div>
                <label for="job_id" class="block text-xs font-semibold text-slate-600 mb-1">Lowongan Pekerjaan</label>
                <select id="job_id" name="job_id" class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                    <option value="">Semua Lowongan</option>
                    @foreach($companyJobs as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-600 mb-1">Status Lamaran</label>
                <select id="status" name="status" class="w-full px-3.5 py-2 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 text-slate-800">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Direview</option>
                    <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Diterima</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-xs">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'job_id', 'status']))
                    <a href="{{ route('employer.applications.index') }}" class="px-3.5 py-2 text-sm font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors" title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Applications List / Table --}}
    @if($applications->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Lamaran Ditemukan</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto">
                @if(request()->hasAny(['search', 'job_id', 'status']))
                    Tidak ada lamaran yang cocok dengan kriteria filter yang Anda pilih.
                @else
                    Belum ada kandidat yang mengajukan lamaran ke lowongan pekerjaan perusahaan Anda.
                @endif
            </p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-700">
                    <thead class="bg-slate-50 border-b border-slate-200/80 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Pelamar</th>
                            <th class="px-6 py-4">Lowongan Kerja</th>
                            <th class="px-6 py-4">Tanggal Melamar</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($applications as $application)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ $application->user->name ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $application->user->email ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $application->jobPosting->title ?? '-' }}</div>
                                    <div class="text-xs text-slate-500">{{ $application->jobPosting->location ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-600">
                                    {{ $application->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($application->status === 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Pending
                                        </span>
                                    @elseif($application->status === 'reviewed')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                            Direview
                                        </span>
                                    @elseif($application->status === 'accepted')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Diterima
                                        </span>
                                    @elseif($application->status === 'rejected')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('employer.applications.show', $application) }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                        <span>Review Pelamar</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $applications->links() }}
            </div>
        </div>
    @endif

</div>
@endsection
