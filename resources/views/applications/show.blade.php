@extends('layouts.app')

@section('title', 'Detail Lamaran - ' . $application->jobPosting->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumbs --}}
    <div class="mb-6">
        <nav class="flex items-center text-sm font-medium text-slate-500 gap-2">
            <a href="{{ route('applications.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Daftar Lamaran</span>
            </a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-900 font-semibold truncate max-w-[260px]">{{ $application->jobPosting->title }}</span>
        </nav>
    </div>

    {{-- Main Application Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden font-bold text-slate-400 text-2xl">
                        @if($application->jobPosting->company && $application->jobPosting->company->logo_url)
                            <img src="{{ $application->jobPosting->company->logo_url }}" alt="{{ $application->jobPosting->company->name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($application->jobPosting->company->name ?? '?', 0, 1) }}
                        @endif
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $application->jobPosting->title }}</h1>
                        <p class="text-sm font-medium text-slate-600 mt-0.5">{{ $application->jobPosting->company->name ?? 'Perusahaan' }}</p>
                    </div>
                </div>

                {{-- Status Badge (with real-time ID) --}}
                <div class="shrink-0">
                    <span id="application-status-badge" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold
                        @if($application->status === 'pending') bg-amber-50 text-amber-700 border border-amber-200
                        @elseif($application->status === 'reviewed') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($application->status === 'accepted') bg-emerald-50 text-emerald-700 border border-emerald-200
                        @elseif($application->status === 'rejected') bg-rose-50 text-rose-700 border border-rose-200
                        @else bg-slate-50 text-slate-700 border border-slate-200
                        @endif">
                        <span id="application-status-dot" class="w-2 h-2 rounded-full
                            @if($application->status === 'pending') bg-amber-500
                            @elseif($application->status === 'reviewed') bg-blue-500
                            @elseif($application->status === 'accepted') bg-emerald-500
                            @elseif($application->status === 'rejected') bg-rose-500
                            @else bg-slate-500
                            @endif"></span>
                        <span id="application-status-text">
                            @if($application->status === 'pending') Menunggu Review (Pending)
                            @elseif($application->status === 'reviewed') Sedang Direview
                            @elseif($application->status === 'accepted') Lamaran Diterima
                            @elseif($application->status === 'rejected') Lamaran Belum Sesuai (Ditolak)
                            @else {{ ucfirst($application->status) }}
                            @endif
                        </span>
                    </span>
                </div>
            </div>

            {{-- Key Information Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 py-6 border-b border-slate-100 text-sm">
                <div>
                    <span class="text-slate-400 text-xs block">Tanggal Melamar</span>
                    <span class="font-semibold text-slate-800">{{ $application->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs block">Terakhir Diperbarui</span>
                    <span id="application-updated-at" class="font-semibold text-slate-800">{{ $application->updated_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs block">Lokasi Kerja</span>
                    <span class="font-semibold text-slate-800">{{ $application->jobPosting->location }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs block">Tipe Pekerjaan</span>
                    <span class="font-semibold text-slate-800">{{ $application->jobPosting->employment_type }}</span>
                </div>
            </div>

            {{-- Cover Letter Section --}}
            <div class="pt-6">
                <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Surat Lamaran / Pesan yang Dikirim
                </h2>
                @if($application->cover_letter)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $application->cover_letter }}
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">Tidak ada surat lamaran yang disertakan saat melamar.</p>
                @endif
            </div>

            {{-- Action Link to Job Posting --}}
            <div class="pt-8 mt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('applications.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    &larr; Kembali ke Lamaran Saya
                </a>
                <a href="{{ route('jobs.show', $application->jobPosting) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                    <span>Lihat Halaman Lowongan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 10000;
    const STATUS_URL = '{{ route("api.applications.status", $application) }}';
    let polling = true;
    let currentRequest = null;
    let currentStatus = '{{ $application->status }}';

    function getBadgeConfig(status) {
        switch (status) {
            case 'pending':
                return {
                    badgeClass: 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-amber-50 text-amber-700 border border-amber-200',
                    dotClass: 'w-2 h-2 rounded-full bg-amber-500',
                    text: 'Menunggu Review (Pending)'
                };
            case 'reviewed':
                return {
                    badgeClass: 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-blue-50 text-blue-700 border border-blue-200',
                    dotClass: 'w-2 h-2 rounded-full bg-blue-500',
                    text: 'Sedang Direview'
                };
            case 'accepted':
                return {
                    badgeClass: 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-700 border border-emerald-200',
                    dotClass: 'w-2 h-2 rounded-full bg-emerald-500',
                    text: 'Lamaran Diterima'
                };
            case 'rejected':
                return {
                    badgeClass: 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-rose-50 text-rose-700 border border-rose-200',
                    dotClass: 'w-2 h-2 rounded-full bg-rose-500',
                    text: 'Lamaran Belum Sesuai (Ditolak)'
                };
            default:
                return {
                    badgeClass: 'inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold bg-slate-50 text-slate-700 border border-slate-200',
                    dotClass: 'w-2 h-2 rounded-full bg-slate-500',
                    text: status
                };
        }
    }

    function pollStatus() {
        if (!polling) return;

        currentRequest = fetch(STATUS_URL, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(res) {
            if (!res.ok) throw new Error('Network error');
            return res.json();
        })
        .then(function(data) {
            if (data.status !== currentStatus) {
                currentStatus = data.status;
                var config = getBadgeConfig(data.status);
                var badge = document.getElementById('application-status-badge');
                var dot = document.getElementById('application-status-dot');
                var text = document.getElementById('application-status-text');

                if (badge) badge.className = config.badgeClass;
                if (dot) dot.className = config.dotClass;
                if (text) text.textContent = config.text;
            }
        })
        .catch(function() {
            // Silently retry
        })
        .finally(function() {
            currentRequest = null;
            if (polling) {
                setTimeout(pollStatus, POLL_INTERVAL);
            }
        });
    }

    setTimeout(pollStatus, POLL_INTERVAL);

    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            polling = false;
        } else {
            polling = true;
            if (!currentRequest) {
                pollStatus();
            }
        }
    });

    window.addEventListener('beforeunload', function() {
        polling = false;
    });
})();
</script>
@endpush
@endsection
