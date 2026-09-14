@extends('layouts.app')

@section('title', $jobPosting->title . ' - Job Portal')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex items-center text-sm font-medium text-slate-500 gap-2">
            <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                <span>Lowongan Kerja</span>
            </a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-900 font-semibold truncate max-w-[300px]">{{ $jobPosting->title }}</span>
        </nav>
    </div>

    {{-- Job Header Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-8 sm:p-10">
            <div class="flex flex-col sm:flex-row gap-6">
                {{-- Company Logo --}}
                <div class="w-24 h-24 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center shrink-0 overflow-hidden">
                    @if($jobPosting->company && $jobPosting->company->logo)
                        <img src="{{ asset('storage/' . $jobPosting->company->logo) }}" alt="{{ $jobPosting->company->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-4xl font-extrabold text-slate-300">{{ substr($jobPosting->company->name ?? '?', 0, 1) }}</span>
                    @endif
                </div>

                {{-- Job Info --}}
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-2">{{ $jobPosting->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mb-6">
                        <div class="flex items-center gap-2 text-slate-700 font-medium">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            {{ $jobPosting->company->name ?? 'Perusahaan Tidak Diketahui' }}
                        </div>
                        <div class="flex items-center gap-2 text-slate-600">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ $jobPosting->location }}
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-blue-50 text-blue-700">
                            {{ $jobPosting->employment_type }}
                        </span>
                        <span id="job-status-badge" class="status-badge inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-green-50 text-green-700">
                            Aktif
                        </span>
                        @if($jobPosting->salary_min || $jobPosting->salary_max)
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-emerald-50 text-emerald-700">
                                @if($jobPosting->salary_min && $jobPosting->salary_max)
                                    Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }} - {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                                @elseif($jobPosting->salary_min)
                                    Mulai Rp {{ number_format($jobPosting->salary_min, 0, ',', '.') }}
                                @else
                                    Hingga Rp {{ number_format($jobPosting->salary_max, 0, ',', '.') }}
                                @endif
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold bg-slate-100 text-slate-700">
                            Diposting {{ $jobPosting->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                {{-- CTA Area --}}
                <div class="sm:shrink-0 flex flex-col gap-3 justify-center">
                    @guest
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition-colors shadow-sm shadow-blue-500/20">
                            Masuk untuk Melamar
                        </a>
                    @else
                        @if(Auth::user()->role === 'job_seeker')
                            @if($hasApplied)
                                <div class="flex flex-col items-center sm:items-end gap-1.5">
                                    <button type="button" disabled class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-slate-500 bg-slate-100 border border-slate-200 cursor-not-allowed rounded-xl">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        <span>Sudah Melamar</span>
                                    </button>
                                    @if($userApplication)
                                        <a href="{{ route('applications.show', $userApplication) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                            Lihat Detail Lamaran &rarr;
                                        </a>
                                    @endif
                                </div>
                            @elseif(!$isProfileComplete)
                                <div class="flex flex-col items-center sm:items-end gap-2">
                                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-left max-w-xs">
                                        <p class="text-xs font-bold text-amber-800 flex items-center gap-1 mb-1">
                                            <span>⚠️</span>
                                            <span>Profil Belum Lengkap</span>
                                        </p>
                                        <p class="text-[11px] text-amber-700 leading-snug">Lengkapi profil terlebih dahulu untuk dapat melamar lowongan ini.</p>
                                    </div>
                                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 text-xs font-bold text-white bg-amber-600 hover:bg-amber-700 rounded-xl transition-all shadow-xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        <span>Lengkapi Profil</span>
                                    </a>
                                </div>
                            @else
                                <button type="button" onclick="openApplyModal()" id="btn-apply" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-98 rounded-xl transition-all shadow-sm shadow-blue-500/25 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>Lamar Sekarang</span>
                                </button>
                            @endif
                        @else
                            <div class="text-xs text-slate-500 bg-slate-50 border border-slate-200 px-4 py-2.5 rounded-xl text-center">
                                Login sebagai <strong class="capitalize">{{ Auth::user()->role }}</strong>
                            </div>
                        @endif
                    @endguest

                    @if($jobPosting->deadline)
                        <p class="text-xs text-center font-medium text-amber-600 bg-amber-50 py-1.5 rounded-lg border border-amber-200">
                            Tutup: {{ \Carbon\Carbon::parse($jobPosting->deadline)->translatedFormat('d M Y') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Details Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Main Content --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-8 sm:p-10">
                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>
                    Deskripsi Pekerjaan
                </h2>
                <div class="prose prose-slate prose-blue max-w-none prose-p:leading-relaxed prose-a:font-semibold prose-a:text-blue-600 hover:prose-a:text-blue-500 whitespace-pre-line text-slate-600">
                    {{ $jobPosting->description }}
                </div>
            </div>
        </div>

        {{-- Sidebar (Company Info) --}}
        <div class="space-y-6">
            @if($jobPosting->company)
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-5">Tentang Perusahaan</h2>
                    
                    <div class="space-y-4">
                        @if($jobPosting->company->website)
                            <div class="flex items-start gap-3 text-sm">
                                <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" /></svg>
                                <div>
                                    <p class="font-medium text-slate-900">Website</p>
                                    <a href="{{ Str::startsWith($jobPosting->company->website, 'http') ? $jobPosting->company->website : 'https://' . $jobPosting->company->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ $jobPosting->company->website }}</a>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start gap-3 text-sm">
                            <svg class="w-5 h-5 text-slate-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <div>
                                <p class="font-medium text-slate-900">Lokasi</p>
                                <p class="text-slate-600">{{ $jobPosting->company->location }}</p>
                            </div>
                        </div>

                        @if($jobPosting->company->description)
                            <div class="pt-4 border-t border-slate-100">
                                <p class="text-sm text-slate-600 line-clamp-4">{{ $jobPosting->company->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Share --}}
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm p-6 text-center">
                <p class="text-sm font-bold text-slate-900 mb-3">Bagikan Lowongan Ini</p>
                <div class="flex justify-center gap-2">
                    <button onclick="navigator.clipboard.writeText(window.location.href); Swal.fire({toast:true, position:'top-end', icon:'success', title:'Link disalin!', showConfirmButton:false, timer:2000})" class="p-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition-colors" title="Salin Link">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::check() && Auth::user()->role === 'job_seeker' && !$hasApplied)
        {{-- Apply Modal --}}
        <div id="apply-modal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl border border-slate-200 shadow-xl max-w-lg w-full p-6 sm:p-8 animate-in fade-in duration-200">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Lamar Pekerjaan</h3>
                        <p class="text-xs text-slate-500 truncate max-w-[280px]">{{ $jobPosting->title }} - {{ $jobPosting->company->name ?? '' }}</p>
                    </div>
                    <button type="button" onclick="closeApplyModal()" class="text-slate-400 hover:text-slate-600 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('jobs.apply', $jobPosting) }}" method="POST">
                    @csrf
                    <div class="mb-5">
                        <label for="cover_letter" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Surat Lamaran / Pesan Singkat <span class="text-xs font-normal text-slate-400">(Opsional)</span>
                        </label>
                        <textarea id="cover_letter" name="cover_letter" rows="5" maxlength="2000" placeholder="Tuliskan perkenalan singkat, keahlian utama, atau pesan pengantar untuk pihak perusahaan..." class="w-full px-4 py-3 text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all placeholder:text-slate-400 text-slate-800"></textarea>
                        <p class="text-xs text-slate-400 mt-1">Maksimal 2.000 karakter.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <button type="button" onclick="closeApplyModal()" class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 rounded-xl hover:bg-slate-100 transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-sm transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            <span>Kirim Lamaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function openApplyModal() {
    var modal = document.getElementById('apply-modal');
    if (modal) modal.classList.remove('hidden');
}

function closeApplyModal() {
    var modal = document.getElementById('apply-modal');
    if (modal) modal.classList.add('hidden');
}
(function() {
    const POLL_INTERVAL = 10000;
    const STATUS_URL = '{{ route("api.job-postings.status", $jobPosting) }}';
    let polling = true;
    let currentRequest = null;
    let currentStatus = '{{ $jobPosting->status }}';

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

    function pollStatus() {
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
            if (data.status !== currentStatus) {
                currentStatus = data.status;

                var badge = document.getElementById('job-status-badge');
                if (badge) {
                    badge.className = 'status-badge inline-flex items-center px-3 py-1 rounded-lg text-sm font-semibold ' + getStatusBadgeClass(data.status);
                    badge.textContent = getStatusLabel(data.status);
                }

                if (data.status !== 'active') {
                    var ctaButton = document.querySelector('button[disabled]');
                    if (ctaButton) {
                        ctaButton.classList.remove('bg-blue-600/50');
                        ctaButton.classList.add('bg-slate-300');
                        ctaButton.textContent = 'Lowongan Tidak Tersedia';
                    }
                }
            }
        })
        .catch(function() {
            // Silently retry on next interval
        })
        .finally(function() {
            currentRequest = null;
            if (polling) {
                setTimeout(pollStatus, POLL_INTERVAL);
            }
        });
    }

    // Start polling
    setTimeout(pollStatus, POLL_INTERVAL);

    // Stop polling when page is not visible
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

    // Stop polling when user leaves the page
    window.addEventListener('beforeunload', function() {
        polling = false;
    });
})();
</script>
@endpush