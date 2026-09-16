@props(['posko' => null, 'bencana' => null])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

    <!-- Card 1: Status Posko Komando -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-3.5 relative overflow-hidden">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-building-office-2 class="w-6 h-6" />
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold text-slate-500 block truncate">Status Posko Komando</span>
            
            @if($posko && $posko->status === 'aktif')
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-base font-extrabold text-emerald-700">Aktif</span>
                </div>
                <p class="text-[11px] font-medium text-emerald-600 mt-1 truncate">
                    {{ $posko->nama_posko }}
                </p>
            @else
                <div class="flex items-center gap-1.5 mt-1">
                    <span class="w-2.5 h-2.5 rounded-full bg-slate-400"></span>
                    <span class="text-base font-extrabold text-slate-600">Standby</span>
                </div>
                <p class="text-[11px] font-medium text-slate-400 mt-1">
                    Posko Belum Diaktifkan
                </p>
            @endif

            <a href="{{ route('admin.posko.create') }}" 
               class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:underline mt-2">
                Kelola Posko &rarr;
            </a>
        </div>
    </div>

    <!-- Card 2: Bencana Berjalan -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-3.5 relative overflow-hidden">
        <div class="w-11 h-11 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold text-slate-500 block truncate">Bencana Berjalan</span>
            
            @if($bencana)
                <span class="text-xl font-extrabold text-red-600 block mt-0.5">1</span>
                <p class="text-[11px] font-medium text-red-600 mt-0.5 truncate" title="{{ $bencana->nama_bencana ?? $bencana->jenis_bencana }}">
                    {{ Str::limit($bencana->nama_bencana ?? $bencana->jenis_bencana, 20) }}
                </p>
            @else
                <span class="text-xl font-extrabold text-slate-400 block mt-0.5">0</span>
                <p class="text-[11px] font-medium text-slate-400 mt-0.5">Tidak Ada Bencana Aktif</p>
            @endif

            <a href="{{ route('admin.bencana') }}" 
               class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:underline mt-2">
                Lihat Bencana &rarr;
            </a>
        </div>
    </div>

    <!-- Card 3: Permintaan Masuk -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-3.5 relative overflow-hidden">
        <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-clipboard-document-list class="w-6 h-6" />
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold text-slate-500 block truncate">Permintaan Masuk</span>
            <span class="text-xl font-extrabold text-blue-600 block mt-0.5">0</span>
            <p class="text-[11px] font-medium text-slate-400 mt-0.5">Pengajuan Logistik</p>

            <a href="{{ route('admin.permintaan') }}" 
               class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:underline mt-2">
                Lihat Kebutuhan &rarr;
            </a>
        </div>
    </div>

    <!-- Card 4: Distribusi Berjalan -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-start gap-3.5 relative overflow-hidden">
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-truck class="w-6 h-6" />
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-xs font-semibold text-slate-500 block truncate">Distribusi Berjalan</span>
            <span class="text-xl font-extrabold text-amber-600 block mt-0.5">0</span>
            <p class="text-[11px] font-medium text-slate-400 mt-0.5">Pengiriman Logistik</p>

            <a href="{{ route('admin.distribusi.index') }}" 
               class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-600 hover:underline mt-2">
                Lihat Distribusi &rarr;
            </a>
        </div>
    </div>

</div>