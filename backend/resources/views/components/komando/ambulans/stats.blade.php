@props(['stats'])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Total Panggilan (Paling Kiri) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-document-text class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">TOTAL PANGGILAN</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stats['total'] ?? 0 }}</span>
                <span class="text-xs text-slate-400 font-medium">Panggilan</span>
            </div>
        </div>
    </div>

    <!-- Evakuasi Aktif -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-truck class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">EVAKUASI AKTIF</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stats['meluncur'] ?? 0 }}</span>
                <span class="text-xs text-slate-400 font-medium">Ambulans</span>
            </div>
        </div>
    </div>

    <!-- Pasien Tertangani -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-check-circle class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">PASIEN TERTANGANI</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stats['selesai'] ?? 0 }}</span>
                <span class="text-xs text-slate-400 font-medium">Pasien</span>
            </div>
        </div>
    </div>

    <!-- Darurat Pending (Paling Kanan) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-xs flex items-center">
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-exclamation-triangle class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">DARURAT PENDING</p>
            <div class="flex items-baseline gap-1 mt-0.5">
                <span class="text-2xl font-extrabold text-slate-900 leading-none">{{ $stats['pending'] ?? 0 }}</span>
                <span class="text-xs text-slate-400 font-medium">Laporan</span>
            </div>
        </div>
    </div>
</div>