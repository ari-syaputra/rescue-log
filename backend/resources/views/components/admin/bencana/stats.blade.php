<!-- HEADER PUSAT KOMANDO INSIDEN & TOMBOL INISIASI MANUAL -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pusat Komando Insiden</h1>
        <p class="text-xs font-medium text-slate-500 mt-1">Monitoring real-time deteksi bencana BMKG & manajemen operasi tanggap darurat.</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- Tombol Aksi Utama: Full-Page Inisiasi Bencana Manual -->
        <a href="{{ route('admin.bencana.create') }}" 
           class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Inisiasi Bencana Manual</span>
        </a>

        <!-- Status Indicator Live -->
        <span class="inline-flex items-center px-3.5 py-2.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
            <span class="w-2 h-2 mr-2 bg-emerald-500 rounded-full animate-pulse"></span>
            Sistem Live
        </span>
    </div>
</div>

<!-- 4 STAT CARDS KONSISTEN PRESISI -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Card 1: TERDETEKSI HARI INI -->
    <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-blue-600 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-blue-100/80 text-blue-600 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9" stroke-width="2"/>
                <circle cx="12" cy="12" r="5" stroke-width="2"/>
                <circle cx="12" cy="12" r="2" fill="currentColor"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TERDETEKSI HARI INI</p>
            <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['terdeteksi_hari_ini'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Bencana terdeteksi BMKG</p>
        </div>
    </div>

    <!-- Card 2: PERLU VALIDASI -->
    <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-amber-500 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-amber-100/80 text-amber-600 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PERLU VALIDASI</p>
            <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['perlu_validasi'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Menunggu verifikasi admin</p>
        </div>
    </div>

    <!-- Card 3: OPERASI BERJALAN -->
    <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-rose-600 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-rose-100/80 text-rose-600 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">OPERASI BERJALAN</p>
            <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['sedang_berjalan'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Penanganan sedang berlangsung</p>
        </div>
    </div>

    <!-- Card 4: OPERASI SELESAI -->
    <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-emerald-600 flex items-center gap-4">
        <div class="w-14 h-14 rounded-2xl bg-emerald-100/80 text-emerald-600 flex items-center justify-center shrink-0">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </div>
        <div>
            <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">OPERASI SELESAI</p>
            <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['selesai'] ?? 0 }}</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Operasi telah ditutup</p>
        </div>
    </div>

</div>