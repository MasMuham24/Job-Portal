@extends('layouts.app')

@section('title', 'Lamaran Saya - Job Portal')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Lamaran Saya</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan status lamaran kerja yang telah Anda ajukan.</p>
        </div>
        <div>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <span>Cari Lowongan Baru</span>
            </a>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-12 text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Belum Ada Lamaran</h3>
            <p class="text-sm text-slate-500 max-w-md mx-auto mb-6">Anda belum mengajukan lamaran ke lowongan pekerjaan manapun. Temukan posisi impian Anda sekarang.</p>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm">
                Jelajahi Lowongan Kerja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $application)
                <div id="application-card-{{ $application->id }}" class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:border-slate-300 transition-all p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        {{-- Left: Job Info --}}
                        <div class="flex items-start gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden font-bold text-slate-400 text-lg">
                                @if($application->jobPosting->company && $application->jobPosting->company->logo_url)
                                    <img src="{{ $application->jobPosting->company->logo_url }}" alt="{{ $application->jobPosting->company->name }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($application->jobPosting->company->name ?? '?', 0, 1) }}
                                @endif
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('jobs.show', $application->jobPosting) }}" class="text-base sm:text-lg font-bold text-slate-900 hover:text-blue-600 transition-colors truncate block">
                                    {{ $application->jobPosting->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500 mt-1">
                                    <span class="font-medium text-slate-700">{{ $application->jobPosting->company->name ?? 'Perusahaan' }}</span>
                                    <span>•</span>
                                    <span>{{ $application->jobPosting->location }}</span>
                                    <span>•</span>
                                    <span>Dilamar: {{ $application->created_at->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Right: Status & Action --}}
                        <div class="flex items-center justify-between sm:justify-end gap-3 pt-3 sm:pt-0 border-t sm:border-t-0 border-slate-100 shrink-0">
                            <div>
                                @if($application->status === 'pending')
                                    <span id="status-badge-{{ $application->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Pending
                                    </span>
                                @elseif($application->status === 'reviewed')
                                    <span id="status-badge-{{ $application->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                        Direview
                                    </span>
                                @elseif($application->status === 'accepted')
                                    <span id="status-badge-{{ $application->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Diterima
                                    </span>
                                @elseif($application->status === 'rejected')
                                    <span id="status-badge-{{ $application->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @else
                                    <span id="status-badge-{{ $application->id }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('applications.show', $application) }}" class="inline-flex items-center gap-1 px-3.5 py-1.5 text-xs font-semibold text-slate-700 hover:text-blue-600 bg-slate-50 hover:bg-blue-50 border border-slate-200 hover:border-blue-200 rounded-lg transition-colors">
                                <span>Detail</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $applications->links() }}
        </div>
    @endif

</div>

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 10000;
    const STATUSES_URL = '{{ route("api.applications.my-statuses") }}';
    let polling = true;
    let currentRequest = null;

    function renderBadge(status) {
        switch (status) {
            case 'pending':
                return '<span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending';
            case 'reviewed':
                return '<span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> Direview';
            case 'accepted':
                return '<span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Diterima';
            case 'rejected':
                return '<span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak';
            default:
                return status;
        }
    }

    function getBadgeClasses(status) {
        switch (status) {
            case 'pending':
                return 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200';
            case 'reviewed':
                return 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200';
            case 'accepted':
                return 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
            case 'rejected':
                return 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200';
            default:
                return 'inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200';
        }
    }

    function pollStatuses() {
        if (!polling) return;

        currentRequest = fetch(STATUSES_URL, {
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
            if (Array.isArray(data)) {
                data.forEach(function(item) {
                    var badge = document.getElementById('status-badge-' + item.id);
                    if (badge) {
                        badge.className = getBadgeClasses(item.status);
                        badge.innerHTML = renderBadge(item.status);
                    }
                });
            }
        })
        .catch(function() {
            // Silently retry
        })
        .finally(function() {
            currentRequest = null;
            if (polling) {
                setTimeout(pollStatuses, POLL_INTERVAL);
            }
        });
    }

    setTimeout(pollStatuses, POLL_INTERVAL);

    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            polling = false;
        } else {
            polling = true;
            if (!currentRequest) {
                pollStatuses();
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
