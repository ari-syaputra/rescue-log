@props([
    'armadaSiap' => 0, 
    'personelSiaga' => 0, 
    'lokasiTerdampak' => 0, 
    'logistikTerkirim' => 0
])

@php
    // Mengambil data user yang sedang login beserta relasi posko
    $user = auth()->user();
    $namaPosko = $user && $user->posko ? $user->posko->nama_posko : 'POSKO KOMANDO SIAGA';
    $namaUser = $user ? $user->name : 'Petugas';
@endphp

<div class="relative overflow-hidden bg-gradient-to-br from-blue-700 via-blue-800 to-indigo-900 text-white p-6 sm:p-8 rounded-3xl shadow-xl flex flex-col justify-between min-h-[200px] gap-6">
    
    {{-- BACKGROUND IMAGE & OVERLAY DARI public/img/ds.png --}}
    <div class="absolute inset-0 bg-cover bg-center opacity-20 mix-blend-overlay pointer-events-none" style="background-image: url('{{ asset('img/ds.png') }}');"></div>
    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/60 via-transparent to-transparent pointer-events-none"></div>

    {{-- KONTEN BAGIAN ATAS: TEKS HEADLINE DINAMIS --}}
    <div class="space-y-1.5 z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-600/50 border border-blue-400/30 backdrop-blur-sm">
            <span class="w-2 h-2 rounded-full bg-blue-300 animate-ping"></span>
            <span class="text-[11px] font-black uppercase tracking-widest text-blue-100">
                {{ $namaPosko }}
            </span>
        </div>
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
            Selamat Datang, {{ $namaUser }}!
        </h1>
        <p class="text-xs sm:text-sm text-blue-100/90 max-w-2xl font-medium leading-relaxed">
            Pantau situasi, kelola sumber daya, dan pastikan distribusi logistik berjalan tepat sasaran secara real-time.
        </p>
    </div>

    {{-- KONTEN BAGIAN BAWAH: BADGE STATUS & STATISTIK REAL-TIME --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 z-10 pt-4 border-t border-blue-500/30">
        
        {{-- BADGE STATUS & WAKTU SINKRONISASI --}}
        <div class="flex items-center gap-3 text-xs">
            <span class="px-3 py-1.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full font-bold text-[11px] flex items-center gap-2 shadow-sm backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                Sistem Live
            </span>
            <span class="text-blue-200 font-medium flex items-center gap-1.5">
                <x-heroicon-o-clock class="w-4 h-4 text-blue-300" />
                {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </span>
        </div>

        {{-- GRID KARTU STATISTIK RINGKAS (DISESUAIKAN DENGAN KONDISI DATA REAL) --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 shrink-0">
            
            {{-- 1. ARMADA SIAP --}}
            <div class="bg-white/10 hover:bg-white/15 transition-all duration-200 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                    <x-heroicon-s-truck class="w-5 h-5 text-blue-200" />
                </div>
                <div>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">ARMADA SIAP</span>
                    <p class="text-base sm:text-lg font-black text-white leading-none mt-1">
                        @if($armadaSiap > 0)
                            {{ $armadaSiap }} <span class="text-xs font-normal text-blue-200">Unit</span>
                        @else
                            <span class="text-xs font-semibold text-blue-200/80">Belum Ada</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- 2. PERSONEL SIAGA --}}
            <div class="bg-white/10 hover:bg-white/15 transition-all duration-200 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                    <x-heroicon-s-user-group class="w-5 h-5 text-blue-200" />
                </div>
                <div>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">PERSONEL SIAGA</span>
                    <p class="text-base sm:text-lg font-black text-white leading-none mt-1">
                        @if($personelSiaga > 0)
                            {{ $personelSiaga }} <span class="text-xs font-normal text-blue-200">Orang</span>
                        @else
                            <span class="text-xs font-semibold text-blue-200/80">0 Orang</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- 3. LOKASI TERDAMPAK / SUB-POSKO --}}
            <div class="bg-white/10 hover:bg-white/15 transition-all duration-200 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                    <x-heroicon-s-map-pin class="w-5 h-5 text-blue-200" />
                </div>
                <div>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">SUB-POSKO</span>
                    <p class="text-base sm:text-lg font-black text-white leading-none mt-1">
                        @if($lokasiTerdampak > 0)
                            {{ $lokasiTerdampak }} <span class="text-xs font-normal text-blue-200">Titik</span>
                        @else
                            <span class="text-xs font-semibold text-blue-200/80">0 Titik</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- 4. LOGISTIK TERKIRIM --}}
            <div class="bg-white/10 hover:bg-white/15 transition-all duration-200 backdrop-blur-md p-3.5 rounded-2xl border border-white/15 flex items-center gap-3 shadow-inner">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center shrink-0">
                    <x-heroicon-s-cube class="w-5 h-5 text-blue-200" />
                </div>
                <div>
                    <span class="text-[10px] font-bold text-blue-200 uppercase tracking-wider block">LOGISTIK TERKIRIM</span>
                    <p class="text-base sm:text-lg font-black text-white leading-none mt-1">
                        @if($logistikTerkirim > 0)
                            {{ number_format($logistikTerkirim) }} <span class="text-xs font-normal text-blue-200">Item</span>
                        @else
                            <span class="text-xs font-semibold text-blue-200/80">0 Item</span>
                        @endif
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>