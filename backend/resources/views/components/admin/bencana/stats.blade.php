@props(['stats' => []])

<!-- HEADER PUSAT KOMANDO INSIDEN & TOMBOL INISIASI MANUAL -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pusat Komando Insiden</h1>
        <p class="text-xs font-medium text-slate-500 mt-1">Monitoring real-time deteksi bencana BMKG & manajemen operasi tanggap darurat.</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Tombol Aksi Utama: Full-Page Inisiasi Bencana Manual -->
        <!-- Status Indicator Live -->
        <span class="inline-flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
            <span class="w-2 h-2 mr-2 bg-emerald-500 rounded-full animate-pulse"></span>
            Sistem Live
        </span>
        
        <div>
            <a href="{{ route('admin.bencana.create') }}"
            class="w-full md:w-auto shrink-0 h-10 inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-700 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-blue-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                <x-heroicon-s-plus class="w-4 h-4 mr-2" />
                Inisiasi Bencana Manual
            </a>
        </div>

    </div>
</div>

<!-- 4 STAT CARDS DESAIN PRESISI SAMPLE -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Card 1: TERDETEKSI HARI INI -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-radio class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Terdeteksi Hari Ini</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['terdeteksi_hari_ini'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Bencana terdeteksi BMKG</p>
        </div>
    </div>

    <!-- Card 2: PERLU VALIDASI -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-shield-exclamation class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Perlu Validasi</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['perlu_validasi'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Menunggu verifikasi admin</p>
        </div>
    </div>

    <!-- Card 3: OPERASI BERJALAN -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-bolt class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Operasi Berjalan</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['sedang_berjalan'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Penanganan berlangsung</p>
        </div>
    </div>

    <!-- Card 4: OPERASI SELESAI -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-check-badge class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Operasi Selesai</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $stats['selesai'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Operasi telah ditutup</p>
        </div>
    </div>

</div>