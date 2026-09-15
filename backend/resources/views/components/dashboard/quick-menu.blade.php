<div class="space-y-3">
    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Pintasan Menu Utama</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ Route::has('komando.logistik.index') ? route('komando.logistik.index') : '#' }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition group flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                    <x-heroicon-s-document-text class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition">Data Logistik</h4>
                    <p class="text-[10px] text-slate-400">Tinjau & putuskan pengajuan</p>
                </div>
            </div>
            <x-heroicon-m-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition" />
        </a>

        <a href="{{ Route::has('komando.distribusi.index') ? route('komando.distribusi.index') : '#' }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition group flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-map-pin class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition">Distribusi Logistik</h4>
                    <p class="text-[10px] text-slate-400">Atur armada & rute pengiriman</p>
                </div>
            </div>
            <x-heroicon-m-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition" />
        </a>

        <a href="{{ Route::has('komando.pengajuan.index') ? route('komando.pengajuan.index') : '#' }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition group flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-bolt class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition">Pengajuan Kebutuhan</h4>
                    <p class="text-[10px] text-slate-400">Ajukan tambahan stok ke BPBD</p>
                </div>
            </div>
            <x-heroicon-m-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition" />
        </a>

        <a href="{{ Route::has('komando.posko-kecil.index') ? route('komando.posko-kecil.index') : '#' }}" class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition group flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-heroicon-s-user-group class="w-5 h-5" />
                </div>
                <div>
                    <h4 class="text-xs font-bold text-slate-800 group-hover:text-blue-600 transition">Pendataan Pos Kecil</h4>
                    <p class="text-[10px] text-slate-400">Daftarkan & atur kode undangan</p>
                </div>
            </div>
            <x-heroicon-m-arrow-right class="w-4 h-4 text-slate-300 group-hover:text-blue-600 group-hover:translate-x-1 transition" />
        </a>
    </div>
</div>