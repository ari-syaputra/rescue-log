@props([
    'siapKirimCount' => 0,
    'dalamPerjalananCount' => 0,
    'armadaCount' => 0,
    'hambatanCount' => 0
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Siap Dikirim -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-blue-300 transition-colors">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Siap Dikirim</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $siapKirimCount }} <span class="text-xs font-normal text-slate-400">Paket</span></h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/80">
            <x-heroicon-o-check-badge class="w-6 h-6" />
        </div>
    </div>

    <!-- Dalam Perjalanan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-amber-300 transition-colors">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Dalam Perjalanan</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $dalamPerjalananCount }} <span class="text-xs font-normal text-slate-400">Armada</span></h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100/80">
            <x-heroicon-o-truck class="w-6 h-6" />
        </div>
    </div>

    <!-- Armada Siaga (+ Tombol Tambah Clean di Header Card) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-emerald-300 transition-colors">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Armada Siaga</p>
                <button onclick="openArmadaModal()" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/60 px-2 py-0.5 rounded-md transition-all cursor-pointer flex items-center gap-0.5" title="Tambah Unit Armada Baru">
                    <span>+ Tambah</span>
                </button>
            </div>
            <h3 class="text-2xl font-black text-slate-900">{{ $armadaCount }} <span class="text-xs font-normal text-slate-400">Unit</span></h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/80 shrink-0">
            <x-heroicon-o-shield-check class="w-6 h-6" />
        </div>
    </div>

    <!-- Hambatan Rute -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between hover:border-rose-300 transition-colors">
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Hambatan Rute</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $hambatanCount }} <span class="text-xs font-normal text-slate-400">Titik</span></h3>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100/80">
            <x-heroicon-o-exclamation-triangle class="w-6 h-6" />
        </div>
    </div>
</div>