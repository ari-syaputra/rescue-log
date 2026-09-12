@extends('layouts.app')

@section('title', 'Daftar Posko Kecil - SiGap BPBD')

@section('content')
<div class="w-full space-y-6 pb-12">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pendataan Posko Kecil</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola titik posko lapangan & distribusi kode akses registrasi petugas.</p>
        </div>
        <div>
            <a href="{{ route('komando.posko-kecil.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Sub Posko
            </a>
        </div>
    </div>

    {{-- Alert --}}
    <x-sub-posko.alert />

    {{-- Stats Bar Component --}}
    <x-sub-posko.stats-bar 
        :totalPosko="$totalPosko ?? $subPoskos->total()" 
        :poskoAktif="$poskoAktif ?? 0" 
        :totalPetugas="$totalPetugas ?? 0" 
    />

    {{-- Filter Bar --}}
    <form method="GET" action="{{ route('komando.posko-kecil.index') }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari posko..." class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>

        <select name="status" onchange="this.form.submit()" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-700 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 transition-all">
            <option value="">Semua Status</option>
            <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="siaga" {{ request('status') == 'siaga' ? 'selected' : '' }}>Siaga</option>
            <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <a href="{{ route('komando.posko-kecil.index') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Reset Filter
        </a>
    </form>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- LEFT COLUMN: List Cards --}}
        <div class="lg:col-span-8 space-y-4">
            <div class="flex items-center justify-between px-1">
                <h2 class="text-base font-bold text-slate-900">Daftar Posko Kecil</h2>
                <span class="text-xs text-slate-500 font-medium">{{ $subPoskos->total() }} Posko</span>
            </div>

            @forelse($subPoskos as $posko)
                <x-sub-posko.card :posko="$posko" />
            @empty
                <div class="bg-white rounded-2xl border border-slate-200/80 p-8 text-center space-y-3">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4"/>
                        </svg>
                    </div>
                    <p class="text-xs text-slate-500 font-medium">Belum ada Sub-Posko yang terdaftar.</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if($subPoskos->hasPages())
                <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-xs text-slate-500">
                        Menampilkan <span class="font-semibold text-slate-800">{{ $subPoskos->firstItem() }}</span> - <span class="font-semibold text-slate-800">{{ $subPoskos->lastItem() }}</span> dari <span class="font-semibold text-slate-800">{{ $subPoskos->total() }}</span> Posko
                    </div>
                    {{ $subPoskos->links() }}
                </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Sidebar Widgets --}}
        <div class="lg:col-span-4 space-y-6">
            
            {{-- Map Component --}}
            <x-sub-posko.map-widget 
                :poskos="$subPoskos->items()" 
                :poskoAktif="$poskoAktif ?? 0"
                :poskoSiaga="$poskoSiaga ?? 0"
                :poskoNonaktif="$poskoNonaktif ?? 0"
            />

            {{-- Ringkasan Widget --}}
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900">Ringkasan Posko</h3>
                
                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aktif
                        </span>
                        <span class="font-bold text-slate-800">{{ $poskoAktif ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span> Siaga
                        </span>
                        <span class="font-bold text-slate-800">{{ $poskoSiaga ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="flex items-center gap-2 text-slate-600">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Nonaktif
                        </span>
                        <span class="font-bold text-slate-800">{{ $poskoNonaktif ?? 0 }}</span>
                    </div>
                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between font-bold text-slate-900">
                        <span>Total</span>
                        <span>{{ $totalPosko ?? 0 }} Posko</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection