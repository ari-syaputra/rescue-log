@props([
    'siapKirimCount' => 0,
    'dalamPerjalananCount' => 0,
    'armadaCount' => 0,
    'hambatanCount' => 0
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Siap Dikirim (Biru) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-archive-box class="w-7 h-7" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Siap Dikirim</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $siapKirimCount }} <span class="text-xs font-normal text-slate-400">Paket</span></h3>
        </div>
    </div>

    <!-- Dalam Perjalanan (Amber) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-truck class="w-7 h-7" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Perjalanan</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $dalamPerjalananCount }} <span class="text-xs font-normal text-slate-400">Armada</span></h3>
        </div>
    </div>

    <!-- Armada Siaga (Emerald) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-wrench-screwdriver class="w-7 h-7" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Armada Siaga</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $armadaCount }} <span class="text-xs font-normal text-slate-400">Unit</span></h3>
        </div>
    </div>

    <!-- Hambatan Rute (Rose) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-exclamation-triangle class="w-7 h-7" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hambatan Rute</p>
            <h3 class="text-2xl font-black text-slate-900 mt-0.5">{{ $hambatanCount }} <span class="text-xs font-normal text-slate-400">Titik</span></h3>
        </div>
    </div>
</div>