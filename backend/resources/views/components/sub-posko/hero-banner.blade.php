@props([
    'bencana' => null,
    'totalPengungsi' => 0,
    'subPosko' => null
])

@php
    $heroImage = asset('img/hero-posko-kecil.png');
@endphp

<div class="relative w-full rounded-2xl md:rounded-3xl overflow-hidden shadow-md border border-blue-200/80 bg-blue-950 mb-6 flex flex-col sm:flex-row items-stretch justify-between min-h-52 md:min-h-64 p-5 sm:p-7 md:p-8">

    <div class="absolute inset-0 z-0 pointer-events-none">
        <img src="{{ $heroImage }}" 
             alt="Hero Posko" 
             class="w-full h-full object-cover object-bottom md:object-right-bottom scale-105 transform">
    </div>

    <div class="absolute inset-0 z-0 bg-gradient-to-t sm:bg-gradient-to-r from-blue-950/90 via-blue-900/70 sm:via-blue-900/60 to-blue-900/20"></div>

    <div class="relative z-10 max-w-xl flex flex-col justify-between space-y-4 sm:space-y-0">
        
        <div class="flex flex-wrap items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-400/40 text-emerald-300 text-xs font-bold tracking-wide backdrop-blur-md">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ $subPosko->status ?? 'Aktif' }}
            </span>
            <span class="inline-flex items-center gap-1 text-xs text-blue-100 font-medium bg-blue-900/60 px-3 py-1 rounded-full border border-blue-700/50 backdrop-blur-md">
                <x-heroicon-s-shield-exclamation class="w-4 h-4 text-amber-400 shrink-0" />
                {{ $bencana->nama_bencana ?? 'Tanggap Darurat Kebencanaan' }}
            </span>
        </div>

        <div class="space-y-1.5 my-auto py-2">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white tracking-tight drop-shadow-md leading-tight">
                {{ $subPosko->nama_posko ?? 'Posko Lapangan' }}
            </h1>
            
            <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs sm:text-sm text-blue-100/90">
                <div class="flex items-center gap-1.5">
                    <x-heroicon-s-user-circle class="w-4 h-4 text-cyan-400 shrink-0" />
                    <span>PJ: <strong class="text-white font-semibold">{{ Auth::user()->name ?? 'Petugas Lapangan' }}</strong></span>
                </div>
                
                <div class="flex items-center gap-1.5">
                    <x-heroicon-s-map-pin class="w-4 h-4 text-rose-500 shrink-0" />
                    <span>{{ $subPosko->lokasi ?? ($subPosko->alamat_posko ?? 'Lokasi Posko Lapangan') }}</span>
                </div>
            </div>
        </div>

        <!-- Mobile View -->
        <div class="sm:hidden pt-3 border-t border-blue-400/20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-heroicon-s-users class="w-5 h-5 text-blue-100 shrink-0" />
                <span class="text-xs font-semibold text-blue-100">Total Pengungsi</span>
            </div>
            <div class="text-lg font-black text-white font-mono">
                {{ number_format($totalPengungsi) }} <span class="text-xs font-sans text-blue-200 font-normal">Jiwa</span>
            </div>
        </div>

    </div>

    <!-- Desktop View Card Pengungsi -->
    <div class="relative z-10 hidden sm:flex flex-col justify-between items-end self-center bg-blue-700/50 backdrop-blur-md p-4 md:p-5 rounded-2xl border border-blue-400/20 shadow-xl min-w-[180px]">
        <div class="flex items-center gap-2 mb-2 w-full justify-end border-b border-blue-200/20 pb-2">
            <x-heroicon-s-users class="w-5 h-5 text-blue-100 shrink-0" />
            <span class="text-[11px] font-bold uppercase tracking-wider text-blue-100">Pengungsi Terdata</span>
        </div>
        
        <div class="text-3xl md:text-4xl font-black text-white font-mono flex items-baseline gap-1.5 my-1">
            {{ number_format($totalPengungsi) }}
            <span class="text-xs font-sans text-blue-200 font-medium">Jiwa</span>
        </div>

        <div class="flex items-center gap-1 text-[11px] text-emerald-300 font-medium mt-1">
            <x-heroicon-s-check-circle class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
            <span>Terverifikasi Realtime</span>
        </div>
    </div>

</div>