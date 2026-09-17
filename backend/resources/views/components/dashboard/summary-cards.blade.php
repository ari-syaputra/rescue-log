@props([
    'pengajuanMasukCount' => 0, 
    'distribusiBerjalanCount' => 0, 
    'stokKritisCount' => 0, 
    'totalPoskoKecil' => 0
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
    {{-- PENGAJUAN MASUK --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-start justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">PENGAJUAN MASUK</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-extrabold text-slate-900 leading-none">{{ $pengajuanMasukCount }}</span>
                <span class="text-sm font-semibold text-slate-600">Pengajuan</span>
            </div>
            <div class="pt-1">
                @if($pengajuanMasukCount > 0)
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                        Menunggu Keputusan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                        Semua Terproses
                    </span>
                @endif
            </div>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-rose-50/80 text-rose-500 flex items-center justify-center shrink-0">
            <x-heroicon-s-document-text class="w-7 h-7" />
        </div>
    </div>

    {{-- DISTRIBUSI BERJALAN --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-start justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">DISTRIBUSI BERJALAN</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-extrabold text-slate-900 leading-none">{{ $distribusiBerjalanCount }}</span>
                <span class="text-sm font-semibold text-slate-600">Armada</span>
            </div>
            <div class="pt-1 flex items-center gap-1.5">
                @if($distribusiBerjalanCount > 0)
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                    <span class="text-xs text-slate-500 font-medium">Dalam perjalanan</span>
                @else
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span>
                    <span class="text-xs text-slate-400 font-medium">Tidak ada pengiriman</span>
                @endif
            </div>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-blue-50/80 text-blue-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-arrow-path-rounded-square class="w-7 h-7" />
        </div>
    </div>

    {{-- STOK LOGISTIK KRITIS --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-start justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">STOK LOGISTIK KRITIS</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-extrabold text-slate-900 leading-none">{{ $stokKritisCount }}</span>
                <span class="text-sm font-semibold text-slate-600">Item Stock</span>
            </div>
            <div class="pt-1">
                @if($stokKritisCount > 0)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 text-rose-600">
                        Perlu Restok BPBD
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-600">
                        Stok Aman
                    </span>
                @endif
            </div>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-amber-50/80 text-amber-600 flex items-center justify-center shrink-0">
            <x-fas-boxes-stacked class="w-7 h-7" />
        </div>
    </div>

    {{-- POSKO KECIL TERDAFTAR --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-start justify-between">
        <div class="space-y-2">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">POSKO KECIL TERDAFTAR</span>
            <div class="flex items-baseline gap-1.5">
                <span class="text-3xl font-extrabold text-slate-900 leading-none">{{ $totalPoskoKecil }}</span>
                <span class="text-sm font-semibold text-slate-600">Titik Aktif</span>
            </div>
            <div class="pt-1 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs text-slate-500 font-medium">Terintegrasi Komando</span>
            </div>
        </div>
        <div class="w-14 h-14 rounded-2xl bg-emerald-50/80 text-emerald-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-map-pin class="w-7 h-7" />
        </div>
    </div>
</div>