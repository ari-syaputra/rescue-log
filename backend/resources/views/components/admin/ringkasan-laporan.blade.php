@props(['laporan' => null])

@php
    // Safe route lookup untuk Laporan
    $routeLaporan = '#';
    if (Route::has('admin.laporan.index')) {
        $routeLaporan = route('admin.laporan.index');
    } elseif (Route::has('admin.laporan')) {
        $routeLaporan = route('admin.laporan');
    }

    // Ambil nilai ringkasan dari controller dengan nilai bawaan 0
    $bencanaDitangani   = $laporan['bencana_ditangani'] ?? ($laporan->bencana_ditangani ?? 0);
    $paketTersalurkan  = $laporan['paket_tersalurkan'] ?? ($laporan->paket_tersalurkan ?? 0);
    $pengungsiTerlayani = $laporan['pengungsi_terlayani'] ?? ($laporan->pengungsi_terlayani ?? 0);
@endphp

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <x-heroicon-s-document-chart-bar class="w-4 h-4 text-blue-600 shrink-0" />
            <span>Ringkasan Laporan</span>
        </h2>
        
        <a href="{{ $routeLaporan }}" 
           class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="grid grid-cols-3 gap-2 my-auto">
        <!-- Bencana Ditangani -->
        <div class="bg-blue-50/60 border border-blue-100 p-3 rounded-xl text-center">
            <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-blue-600 mx-auto mb-1" />
            <span class="text-base font-extrabold text-slate-900 block">
                {{ number_format($bencanaDitangani, 0, ',', '.') }}
            </span>
            <span class="text-[9px] font-semibold text-slate-500 leading-tight block">
                Bencana Ditangani
            </span>
        </div>

        <!-- Paket Tersalurkan -->
        <div class="bg-emerald-50/60 border border-emerald-100 p-3 rounded-xl text-center">
            <x-heroicon-s-archive-box class="w-5 h-5 text-emerald-600 mx-auto mb-1" />
            <span class="text-base font-extrabold text-slate-900 block">
                {{ number_format($paketTersalurkan, 0, ',', '.') }}
            </span>
            <span class="text-[9px] font-semibold text-slate-500 leading-tight block">
                Paket Tersalurkan
            </span>
        </div>

        <!-- Pengungsi Terlayani -->
        <div class="bg-purple-50/60 border border-purple-100 p-3 rounded-xl text-center">
            <x-heroicon-s-users class="w-5 h-5 text-purple-600 mx-auto mb-1" />
            <span class="text-base font-extrabold text-slate-900 block">
                {{ number_format($pengungsiTerlayani, 0, ',', '.') }}
            </span>
            <span class="text-[9px] font-semibold text-slate-500 leading-tight block">
                Pengungsi Terlayani
            </span>
        </div>
    </div>
</div>