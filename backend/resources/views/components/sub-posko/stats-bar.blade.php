@props(['totalPosko' => 0, 'totalPetugas' => 0, 'distribusiBerjalan' => 18])

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">

    <!-- Card 1: Total Posko -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-building-office-2 class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Total Posko</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $totalPosko }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Semua Posko Terdaftar</p>
        </div>
    </div>

    <!-- Card 2: Total Petugas -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-user-group class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Total Petugas</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $totalPetugas }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Petugas di lapangan</p>
        </div>
    </div>

    <!-- Card 3: Distribusi Berjalan -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
            <x-heroicon-s-truck class="w-6 h-6" />
        </div>
        <div class="space-y-0.5 min-w-0">
            <p class="text-xs font-medium text-slate-500">Distribusi Berjalan</p>
            <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $distribusiBerjalan }}</h3>
            <p class="text-[11px] text-slate-400 truncate">Pengiriman aktif</p>
        </div>
    </div>

</div>