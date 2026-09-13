@extends('layouts.app')

@section('title', 'Detail Lowongan - Admin - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.job-postings.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Kembali ke Daftar Lowongan
        </a>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h1 class="text-2xl font-extrabold text-slate-900">{{ $jobPosting->title }}</h1>
                <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                    {{ $jobPosting->status === 'active' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                    {{ $jobPosting->status === 'active' ? 'Aktif' : 'Pending' }}
                </span>
            </div>
            <p class="text-sm text-slate-500">Dibuat {{ $jobPosting->created_at->diffForHumans() }}</p>
        </div>

        {{-- Moderation Actions --}}
        <div class="flex items-center gap-2 shrink-0">
            @if($jobPosting->status === 'pending')
                <form action="{{ route('admin.job-postings.status', $jobPosting) }}" method="POST" onsubmit="return false" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="active">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-green-600 hover:bg-green-700 rounded-xl shadow-xs transition-colors" data-confirm="Setujui lowongan ini? Lowongan akan menjadi aktif dan terlihat oleh pencari kerja." data-confirm-title="Setujui Lowongan?" data-confirm-icon="question">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Setujui
                    </button>
                </form>
            @endif
            @if($jobPosting->status === 'active')
                <form action="{{ route('admin.job-postings.status', $jobPosting) }}" method="POST" onsubmit="return false" class="inline">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="pending">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl shadow-xs transition-colors" data-confirm="Tandai lowongan ini sebagai pending?" data-confirm-title="Ubah Status?" data-confirm-icon="question">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Tandai Pending
                    </button>
                </form>
            @endif
            <form action="{{ route('admin.job-postings.destroy', $jobPosting) }}" method="POST" class="inline" onsubmit="return false">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-red-600 bg-red-50 border border-red-200 hover:bg-red-100 rounded-xl shadow-xs transition-colors" data-confirm="Hapus lowongan ini? Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus Lowongan?" data-confirm-icon="error">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    Hapus
                </button>
            </form>
        </div>
    </div>

    {{-- Job Detail Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">

        {{-- Job Info Grid --}}
        <div class="p-6 border-b border-slate-100">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Perusahaan</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->company->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Employer</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->company->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lokasi</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->location }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tipe Pekerjaan</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $jobPosting->employment_type }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Gaji</p>
                    <p class="text-sm font-semibold text-slate-900">
                        @if($jobPosting->salary_min && $jobPosting->salary_max)
                            Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }} - Rp {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                        @elseif($jobPosting->salary_min)
                            Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }}+
                        @elseif($jobPosting->salary_max)
                            Hingga Rp {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                        @else
                            <span class="text-slate-400 font-normal">Tidak ditampilkan</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deadline</p>
                    <p class="text-sm font-semibold text-slate-900">
                        @if($jobPosting->deadline)
                            {{ $jobPosting->deadline->format('d M Y') }}
                        @else
                            <span class="text-slate-400 font-normal">Tidak ada batas waktu</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        {{-- Description --}}
        <div class="p-6">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Deskripsi Lowongan</h2>
            <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $jobPosting->description }}</div>
        </div>

    </div>

</div>
@endsection
