@extends('layouts.app')

@section('title', 'Kelola Pengguna - Admin - Job Portal')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Kelola Pengguna</h1>
            <p class="mt-1 text-sm text-slate-500">Kelola seluruh akun pengguna Job Portal dari sini.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-xs transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            Tambah Pengguna
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.users.index') }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ !request('role') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Semua <span class="ml-1.5 text-xs opacity-70">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('role') === 'admin' ? 'bg-red-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Admin <span class="ml-1.5 text-xs opacity-70">{{ $counts['admin'] }}</span>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'employer']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('role') === 'employer' ? 'bg-emerald-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Employer <span class="ml-1.5 text-xs opacity-70">{{ $counts['employer'] }}</span>
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'job_seeker']) }}"
           class="px-4 py-2 text-sm font-semibold rounded-xl transition-colors {{ request('role') === 'job_seeker' ? 'bg-blue-500 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
            Pencari Kerja <span class="ml-1.5 text-xs opacity-70">{{ $counts['job_seeker'] }}</span>
        </a>
    </div>

    {{-- Search Bar --}}
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-6">
        @if(request('role'))
            <input type="hidden" name="role" value="{{ request('role') }}">
        @endif
        <div class="relative max-w-md">
            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
            </span>
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari berdasarkan nama atau email..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-300 text-sm text-slate-900 focus:outline-none focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-400">
        </div>
    </form>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-100 bg-slate-50/50">
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Nama</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Email</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role</th>
                        <th class="text-left px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Terdaftar</th>
                        <th class="text-right px-6 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center uppercase shrink-0">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="font-semibold text-slate-900 truncate">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2.5 py-0.5 text-[11px] font-semibold rounded-full
                                    {{ $user->role === 'admin' ? 'bg-red-50 text-red-700' : ($user->role === 'employer' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700') }}">
                                    {{ $user->role === 'employer' ? 'Employer' : ($user->role === 'admin' ? 'Admin' : 'Pencari Kerja') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-xs whitespace-nowrap">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 rounded-lg text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-colors" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </a>
                                    @if($user->id !== Auth::id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return false">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus" data-confirm="Hapus pengguna \"{{ $user->name }}\"? Semua data terkait juga akan dihapus. Tindakan ini tidak dapat dibatalkan." data-confirm-title="Hapus Pengguna?" data-confirm-icon="error">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-500">Tidak ada pengguna ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
