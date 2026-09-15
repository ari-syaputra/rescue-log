@props(['activeKendalaCount' => 0])

<div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-blue-600 to-blue-800 rounded-2xl p-5 text-white shadow-md flex items-center justify-between gap-6">
    
    <!-- Bagian Kiri: Icon & Informasi -->
    <div class="flex items-center gap-4 z-10">
        <!-- Icon AI Square -->
        <div class="w-12 h-12 rounded-xl bg-blue-500/50 border border-blue-400/40 flex items-center justify-center shrink-0 text-white shadow-inner">
            <x-heroicon-o-cpu-chip class="w-7 h-7" />
        </div>

        <div>
            <div class="flex items-center gap-2.5">
                <h2 class="text-base font-bold tracking-tight text-white">AI Dynamic Rerouting Engine</h2>
                <span id="aiStatusBadge" class="text-[10px] bg-blue-400/40 text-blue-100 border border-blue-300/30 px-2.5 py-0.5 rounded-full font-semibold tracking-wider uppercase">
                    Active Routing
                </span>
            </div>
            
            <!-- Deskripsi Dinamis AI -->
            <p id="aiDescriptionText" class="text-blue-100/90 text-xs mt-1 leading-relaxed">
                Sistem mendeteksi <strong class="text-white font-bold">{{ $activeKendalaCount }} hambatan aktif</strong>. Navigasi otomatis mengalihkan rute pengiriman armada untuk menghindari area bahaya, longsor, banjir, dan jembatan putus.
            </p>
        </div>
    </div>

    <!-- Bagian Kanan: Heroicon Dekoratif (Diperbesar) -->
    <div class="hidden md:flex items-center shrink-0 opacity-30 z-0 pointer-events-none pr-2 gap-1 text-blue-100">
        <x-heroicon-o-map class="w-16 h-16" />
        <x-heroicon-o-arrow-path class="w-7 h-7 -ml-4 mb-6" />
    </div>

</div>