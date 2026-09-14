@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan - Job Portal')

@section('content')
<div class="min-h-[65vh] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-16">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto mb-6 rounded-3xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-xs border border-blue-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 tracking-wider uppercase mb-3">
            Error 404
        </span>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-3">
            Halaman Tidak Ditemukan
        </h1>
        <p class="text-sm text-slate-600 mb-8 leading-relaxed">
            Maaf, halaman atau lowongan yang Anda cari tidak ditemukan atau telah dipindahkan ke tautan lain.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Halaman Utama</span>
            </a>
            <a href="javascript:history.back()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl border border-slate-300 hover:bg-slate-50 text-slate-700 font-semibold text-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali</span>
            </a>
        </div>
    </div>
</div>
@endsection
