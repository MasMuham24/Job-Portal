@extends('layouts.app')

@section('title', 'Review Lamaran: ' . $application->user->name . ' - Employer Panel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex items-center text-sm font-medium text-slate-500 gap-2">
            <a href="{{ route('employer.applications.index') }}" class="hover:text-blue-600 transition-colors flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                <span>Daftar Lamaran Masuk</span>
            </a>
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            <span class="text-slate-900 font-semibold truncate max-w-[240px]">{{ $application->user->name }}</span>
        </nav>
    </div>

    {{-- Main Applicant Card --}}
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden mb-6">
        <div class="p-6 sm:p-8">

            {{-- Header: Applicant Info & Current Status --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 pb-6 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 border border-blue-200 text-blue-700 font-extrabold text-2xl flex items-center justify-center shrink-0 uppercase">
                        {{ substr($application->user->name, 0, 2) }}
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">{{ $application->user->name }}</h1>
                        <p class="text-sm text-slate-500 flex items-center gap-1.5 mt-0.5">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span>{{ $application->user->email }}</span>
                        </p>
                    </div>
                </div>

                {{-- Current Status Badge --}}
                <div class="shrink-0">
                    <span class="text-xs text-slate-400 block mb-1">Status Saat Ini:</span>
                    @if($application->status === 'pending')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            Pending
                        </span>
                    @elseif($application->status === 'reviewed')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            Direview
                        </span>
                    @elseif($application->status === 'accepted')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Diterima
                        </span>
                    @elseif($application->status === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            Ditolak
                        </span>
                    @else
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700">
                            {{ ucfirst($application->status) }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- Applied Job Info Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 py-6 border-b border-slate-100 text-sm">
                <div>
                    <span class="text-slate-400 text-xs block">Lowongan yang Dilamar</span>
                    <a href="{{ route('job-postings.show', $application->jobPosting) }}" class="font-bold text-blue-600 hover:underline">
                        {{ $application->jobPosting->title }}
                    </a>
                </div>
                <div>
                    <span class="text-slate-400 text-xs block">Tanggal Masuk Lamaran</span>
                    <span class="font-semibold text-slate-800">{{ $application->created_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs block">Terakhir Diperbarui</span>
                    <span class="font-semibold text-slate-800">{{ $application->updated_at->translatedFormat('d F Y, H:i') }}</span>
                </div>
            </div>

            {{-- Cover Letter Section --}}
            <div class="py-6 border-b border-slate-100">
                <h2 class="text-base font-bold text-slate-900 mb-3 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Surat Lamaran / Pesan Kandidat
                </h2>
                @if($application->cover_letter)
                    <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200/80 text-sm text-slate-700 whitespace-pre-line leading-relaxed">
                        {{ $application->cover_letter }}
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">Pelamar tidak menyertakan surat lamaran / pesan pengantar.</p>
                @endif
            </div>

            {{-- Update Status Section --}}
            <div class="pt-6">
                <h2 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    Perbarui Status Lamaran
                </h2>

                <form action="{{ route('employer.applications.status', $application) }}" method="POST" class="bg-slate-50 rounded-2xl p-5 border border-slate-200/80">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="flex-1">
                            <label for="status" class="block text-xs font-semibold text-slate-600 mb-1.5">Pilih Status Baru:</label>
                            <select id="status" name="status" class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 font-semibold text-slate-800">
                                <option value="pending" {{ $application->status === 'pending' ? 'selected' : '' }}>Pending (Menunggu Review)</option>
                                <option value="reviewed" {{ $application->status === 'reviewed' ? 'selected' : '' }}>Reviewed (Sedang Direview)</option>
                                <option value="accepted" {{ $application->status === 'accepted' ? 'selected' : '' }}>Accepted (Lamaran Diterima)</option>
                                <option value="rejected" {{ $application->status === 'rejected' ? 'selected' : '' }}>Rejected (Lamaran Ditolak)</option>
                            </select>
                        </div>

                        <div class="sm:self-end">
                            <button type="submit" data-confirm="Apakah Anda yakin ingin memperbarui status lamaran ini?" data-confirm-title="Konfirmasi Perubahan Status" class="w-full sm:w-auto px-6 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 active:scale-98 rounded-xl transition-all shadow-sm">
                                Simpan Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer Back Link --}}
            <div class="pt-8 mt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('employer.applications.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900 flex items-center gap-1.5">
                    &larr; Kembali ke Daftar Lamaran
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
