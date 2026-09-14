@extends('layouts.app')

@section('title', 'Lowongan Kerja - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Page Header & Search --}}
    <div class="mb-10 text-center max-w-3xl mx-auto">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Temukan Pekerjaan Impianmu</h1>
        <p class="text-slate-500 mb-8">Cari dan temukan lowongan kerja terbaru yang sesuai dengan keahlian dan minat Anda.</p>

        <form action="{{ route('jobs.index') }}" method="GET" class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posisi, perusahaan..." class="block w-full pl-10 pr-3 py-3 border-0 bg-transparent text-slate-900 placeholder:text-slate-400 focus:ring-0 sm:text-sm">
            </div>
            <div class="hidden sm:block w-px bg-slate-200 my-2"></div>
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <select name="location" class="block w-full pl-10 pr-10 py-3 border-0 bg-transparent text-slate-900 focus:ring-0 sm:text-sm">
                    <option value="">Semua Lokasi</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-colors">
                Cari
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Sidebar Filters --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm sticky top-24">
                <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
                    Filter
                </h3>
                
                <form action="{{ route('jobs.index') }}" method="GET" id="filterForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('location')) <input type="hidden" name="location" value="{{ request('location') }}"> @endif

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Tipe Pekerjaan</label>
                        <div class="space-y-2">
                            @foreach($types as $type)
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="type" value="{{ $type }}" onchange="document.getElementById('filterForm').submit()" {{ request('type') == $type ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500 rounded-full border-slate-300">
                                    <span class="text-sm text-slate-600">{{ $type }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Minimal Gaji</label>
                        <select name="salary_min" onchange="document.getElementById('filterForm').submit()" class="block w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Gaji</option>
                            <option value="3000000" {{ request('salary_min') == '3000000' ? 'selected' : '' }}>≥ Rp 3.000.000</option>
                            <option value="5000000" {{ request('salary_min') == '5000000' ? 'selected' : '' }}>≥ Rp 5.000.000</option>
                            <option value="10000000" {{ request('salary_min') == '10000000' ? 'selected' : '' }}>≥ Rp 10.000.000</option>
                        </select>
                    </div>

                    @if(request()->anyFilled(['search', 'location', 'type', 'salary_min']))
                        <a href="{{ route('jobs.index') }}" class="block w-full text-center px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Hapus Semua Filter
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Job List --}}
        <div class="lg:col-span-3 space-y-4">
            @forelse($jobs as $job)
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 hover:shadow-md transition-shadow group job-card" data-job-id="{{ $job->id }}" data-status="{{ $job->status }}">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                        <div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden">
                            @if($job->company && $job->company->logo_url)
                                <img src="{{ $job->company->logo_url }}" alt="{{ $job->company->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-xl font-bold text-slate-400">{{ substr($job->company->name ?? '?', 0, 1) }}</span>
                            @endif
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2 mb-1">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors">
                                        <a href="{{ route('jobs.show', $job) }}">{{ $job->title }}</a>
                                    </h2>
                                    <p class="text-sm font-medium text-slate-600">{{ $job->company->name ?? 'Perusahaan Tidak Diketahui' }}</p>
                                </div>
                                <span class="inline-flex shrink-0 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $job->employment_type }}
                                </span>
                                <span class="status-badge inline-flex shrink-0 items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">
                                    Aktif
                                </span>
                            </div>

                            <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-slate-500">
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    {{ $job->location }}
                                </span>
                                @if($job->salary_min || $job->salary_max)
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        @if($job->salary_min && $job->salary_max)
                                            Rp {{ number_format($job->salary_min, 0, ',', '.') }} - {{ number_format($job->salary_max, 0, ',', '.') }}
                                        @else
                                            Nego
                                        @endif
                                    </span>
                                @endif
                                <span class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    {{ $job->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="sm:self-center shrink-0 mt-4 sm:mt-0">
                            <a href="{{ route('jobs.show', $job) }}" class="inline-flex items-center justify-center w-full sm:w-auto px-5 py-2 text-sm font-semibold text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition-colors">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/80 p-12 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-50 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 mb-2">No lowongan ditemukan</h3>
                    <p class="text-slate-500 mb-6">Maaf, kami tidak menemukan lowongan yang sesuai dengan kriteria pencarian Anda.</p>
                    @if(request()->anyFilled(['search', 'location', 'type', 'salary_min']))
                        <a href="{{ route('jobs.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-colors">
                            Hapus Filter Pencarian
                        </a>
                    @endif
                </div>
            @endforelse

            @if($jobs->hasPages())
                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 10000;
    const STATUS_URL = '{{ route("api.job-postings.statuses") }}';
    let polling = true;
    let currentRequest = null;

    function getStatusBadgeClass(status) {
        switch (status) {
            case 'active':
                return 'bg-green-50 text-green-700';
            case 'pending':
                return 'bg-amber-50 text-amber-700';
            case 'closed':
                return 'bg-slate-100 text-slate-600';
            default:
                return 'bg-slate-100 text-slate-600';
        }
    }

    function getStatusLabel(status) {
        switch (status) {
            case 'active':
                return 'Aktif';
            case 'pending':
                return 'Pending';
            case 'closed':
                return 'Ditutup';
            default:
                return status;
        }
    }

    function pollStatuses() {
        if (!polling) return;

        currentRequest = fetch(STATUS_URL, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(function(response) {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(function(data) {
            const statusMap = {};
            data.forEach(function(job) {
                statusMap[job.id] = job.status;
            });

            document.querySelectorAll('.job-card').forEach(function(card) {
                const jobId = parseInt(card.dataset.jobId);
                const currentStatus = card.dataset.status;
                const newStatus = statusMap[jobId];

                if (newStatus && newStatus !== currentStatus) {
                    card.dataset.status = newStatus;

                    const badge = card.querySelector('.status-badge');
                    if (badge) {
                        badge.className = 'inline-flex shrink-0 items-center px-2.5 py-0.5 rounded-full text-xs font-medium status-badge ' + getStatusBadgeClass(newStatus);
                        badge.textContent = getStatusLabel(newStatus);
                    }

                    if (newStatus !== 'active') {
                        card.style.transition = 'opacity 0.3s ease';
                        card.style.opacity = '0.5';
                        setTimeout(function() {
                            card.style.opacity = '1';
                        }, 2000);
                    }
                }
            });
        })
        .catch(function() {
            // Silently retry on next interval
        })
        .finally(function() {
            currentRequest = null;
            if (polling) {
                setTimeout(pollStatuses, POLL_INTERVAL);
            }
        });
    }

    // Start polling
    setTimeout(pollStatuses, POLL_INTERVAL);

    // Stop polling when page is not visible
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

    // Stop polling when user leaves the page
    window.addEventListener('beforeunload', function() {
        polling = false;
    });
})();
</script>
@endpush