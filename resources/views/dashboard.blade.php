@extends('layouts.app')

@section('title', 'Dashboard Pencari Kerja - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Welcome & Greeting Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Halo, {{ $user->name }}! 👋</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 capitalize">
                    {{ $user->role }}
                </span>
            </div>
            <p class="text-sm text-slate-500">Selamat datang di dashboard pencari kerja. Pantau lamaran dan temukan peluang karir terbaik.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:text-slate-900 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-all shadow-xs">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span>Edit Profil</span>
            </a>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <span>Cari Lowongan</span>
            </a>
        </div>
    </div>

    {{-- Profile Completion Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8 mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kelengkapan Profil Anda</span>
                    @if($isProfileComplete)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            <span>Lengkap (100%)</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            <span>Belum Lengkap ({{ $completionPercentage }}%)</span>
                        </span>
                    @endif
                </div>

                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden mb-3">
                    <div class="h-3 rounded-full transition-all duration-500 {{ $isProfileComplete ? 'bg-gradient-to-r from-emerald-500 to-teal-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500' }}" style="width: {{ $completionPercentage }}%"></div>
                </div>

                @if($isProfileComplete)
                    <p class="text-xs text-slate-500">Profil Anda sudah lengkap. Anda memenuhi syarat untuk langsung melamar ke seluruh lowongan aktif.</p>
                @else
                    <p class="text-xs text-amber-700 font-medium">⚠️ Anda belum dapat melamar pekerjaan. Lengkapi informasi profil (kontak, pendidikan, keahlian, dan bio) untuk membuka fitur lamaran.</p>
                @endif
            </div>

            @if(!$isProfileComplete)
                <div class="shrink-0">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-all shadow-xs">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                        <span>Lengkapi Profil Sekarang</span>
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Application Statistics Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-8">
        {{-- Total Lamaran --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Total Lamaran</span>
            </div>
            <p id="stat-total" class="text-2xl font-black text-slate-900">{{ $totalApplications }}</p>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Menunggu (Pending)</span>
            </div>
            <p id="stat-pending" class="text-2xl font-black text-amber-600">{{ $pendingApplications }}</p>
        </div>

        {{-- Reviewed --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Direview</span>
            </div>
            <p id="stat-reviewed" class="text-2xl font-black text-blue-600">{{ $reviewedApplications }}</p>
        </div>

        {{-- Accepted --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Diterima</span>
            </div>
            <p id="stat-accepted" class="text-2xl font-black text-emerald-600">{{ $acceptedApplications }}</p>
        </div>

        {{-- Rejected --}}
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <span class="text-xs font-medium text-slate-500">Ditolak</span>
            </div>
            <p id="stat-rejected" class="text-2xl font-black text-rose-600">{{ $rejectedApplications }}</p>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 mb-8">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                <span>Lengkapi Profil</span>
            </a>
            <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                <span>Lihat Lowongan</span>
            </a>
            <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-50 hover:bg-slate-100 border border-slate-200 rounded-xl transition-colors">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span>Lamaran Saya</span>
            </a>
        </div>
    </div>

    {{-- Content Grid: Recent Jobs & Recent Applications --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Latest Active Jobs --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Lowongan Kerja Terbaru</h2>
                <a href="{{ route('jobs.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            @if($recentJobs->isEmpty())
                <p class="text-sm text-slate-400 italic py-6 text-center">Belum ada lowongan aktif saat ini.</p>
            @else
                <div class="space-y-3">
                    @foreach($recentJobs as $job)
                        <div class="p-3.5 rounded-2xl hover:bg-slate-50 transition-colors flex items-center justify-between gap-3 border border-transparent hover:border-slate-100">
                            <div class="min-w-0">
                                <a href="{{ route('jobs.show', $job) }}" class="text-sm font-bold text-slate-900 hover:text-blue-600 truncate block">
                                    {{ $job->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $job->company->name ?? 'Perusahaan' }} • {{ $job->location }}</p>
                            </div>
                            <a href="{{ route('jobs.show', $job) }}" class="shrink-0 text-xs font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg">
                                Detail
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Recent Applications --}}
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900">Lamaran Terakhir Anda</h2>
                <a href="{{ route('applications.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            @if($recentApplications->isEmpty())
                <div class="text-center py-8">
                    <p class="text-sm text-slate-500 mb-3">Anda belum melamar lowongan kerja apapun.</p>
                    <a href="{{ route('jobs.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-all shadow-xs">
                        Cari Lowongan Sekarang
                    </a>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($recentApplications as $app)
                        <div class="p-3.5 rounded-2xl hover:bg-slate-50 transition-colors flex items-center justify-between gap-3 border border-transparent hover:border-slate-100">
                            <div class="min-w-0">
                                <a href="{{ route('applications.show', $app) }}" class="text-sm font-bold text-slate-900 hover:text-blue-600 truncate block">
                                    {{ $app->jobPosting->title }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">{{ $app->jobPosting->company->name ?? 'Perusahaan' }}</p>
                            </div>
                            <span class="shrink-0 text-[11px] font-bold px-2.5 py-1 rounded-lg
                                @if($app->status === 'pending') bg-amber-50 text-amber-700
                                @elseif($app->status === 'reviewed') bg-blue-50 text-blue-700
                                @elseif($app->status === 'accepted') bg-emerald-50 text-emerald-700
                                @elseif($app->status === 'rejected') bg-rose-50 text-rose-700
                                @else bg-slate-50 text-slate-700
                                @endif">
                                {{ ucfirst($app->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

</div>

@push('scripts')
<script>
(function() {
    const POLL_INTERVAL = 10000;
    const STATUSES_URL = '{{ route("api.applications.my-statuses") }}';
    let polling = true;
    let currentRequest = null;

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
                let pending = 0, reviewed = 0, accepted = 0, rejected = 0;
                data.forEach(function(item) {
                    if (item.status === 'pending') pending++;
                    else if (item.status === 'reviewed') reviewed++;
                    else if (item.status === 'accepted') accepted++;
                    else if (item.status === 'rejected') rejected++;
                });

                const totalEl = document.getElementById('stat-total');
                const pendingEl = document.getElementById('stat-pending');
                const reviewedEl = document.getElementById('stat-reviewed');
                const acceptedEl = document.getElementById('stat-accepted');
                const rejectedEl = document.getElementById('stat-rejected');

                if (totalEl) totalEl.textContent = data.length;
                if (pendingEl) pendingEl.textContent = pending;
                if (reviewedEl) reviewedEl.textContent = reviewed;
                if (acceptedEl) acceptedEl.textContent = accepted;
                if (rejectedEl) rejectedEl.textContent = rejected;
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
