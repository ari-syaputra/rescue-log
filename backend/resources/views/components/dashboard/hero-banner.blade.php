@props([
    'posko' => null, 
    'armadaSiap' => 12, 
    'personelSiaga' => 48, 
    'lokasiTerdampak' => 4, 
    'logistikTerkirim' => 234
])

<div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-blue-600 to-indigo-800 text-white p-6 md:p-8 rounded-3xl shadow-xl border border-blue-500/20">
    <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-blue-400/20 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="relative z-10 flex flex-col xl:flex-row xl:items-center justify-between gap-6">
        <div class="space-y-2 max-w-2xl">
            <span class="text-[11px] font-black uppercase tracking-widest text-blue-200 bg-white/10 px-3 py-1 rounded-full border border-white/20 inline-block">
                {{ $posko->nama_posko ?? 'POSKO KOMANDO SIAGA' }}
            </span>
            
            {{-- Mengambil data nama user dari Auth/Session --}}
            <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight">
                Selamat Datang, {{ auth()->check() ? auth()->user()->name : 'Petugas' }}!
            </h1>
            
            <p class="text-xs md:text-sm text-blue-100/90 font-medium">
                Pantau situasi, kelola sumber daya, dan pastikan distribusi logistik berjalan tepat sasaran di seluruh wilayah komando.
            </p>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-200 border border-emerald-400/30 backdrop-blur-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Sistem Aktif
                </span>
                <span class="text-xs text-blue-200/90 font-semibold flex items-center gap-1.5">
                    <x-heroicon-o-clock class="w-4 h-4" />
                    {{ now()->translatedFormat('d F Y, H:i') }} WIB
                </span>
            </div>
        </div>

<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
    {{-- ARMADA SIAP --}}
    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <x-fas-truck class="w-5 h-5 text-white" />
        </div>
        <div>
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">ARMADA SIAP</span>
            <p class="text-lg font-black text-white leading-none mt-0.5">
                {{ $armadaSiap ?? 0 }} <span class="text-xs font-normal text-blue-200">Unit</span>
            </p>
        </div>
    </div>

    {{-- PERSONEL SIAGA --}}
    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <x-heroicon-s-user-group class="w-5 h-5 text-white" />
        </div>
        <div>
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">PERSONEL SIAGA</span>
            <p class="text-lg font-black text-white leading-none mt-0.5">
                {{ $personelSiaga ?? 0 }} <span class="text-xs font-normal text-blue-200">Orang</span>
            </p>
        </div>
    </div>

    {{-- LOKASI TERDAMPAK --}}
    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <x-heroicon-s-map-pin class="w-5 h-5 text-white" />
        </div>
        <div>
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">LOKASI TERDAMPAK</span>
            <p class="text-lg font-black text-white leading-none mt-0.5">
                {{ $lokasiTerdampak ?? 0 }} <span class="text-xs font-normal text-blue-200">Desa</span>
            </p>
        </div>
    </div>

    {{-- LOGISTIK TERKIRIM --}}
    <div class="bg-white/10 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
            <x-fas-boxes-stacked class="w-5 h-5 text-white" />
        </div>
        <div>
            <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">LOGISTIK TERKIRIM</span>
            <p class="text-lg font-black text-white leading-none mt-0.5">
                {{ number_format($logistikTerkirim ?? 0) }} <span class="text-xs font-normal text-blue-200">Paket</span>
            </p>
        </div>
    </div>
</div>
    </div>
</div>