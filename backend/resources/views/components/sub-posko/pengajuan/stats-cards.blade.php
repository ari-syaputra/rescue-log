<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Pengajuan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-document-text class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium whitespace-nowrap">Total Pengajuan</p>
            <h3 class="text-xl font-bold text-gray-800">{{ $totalPengajuan ?? 0 }}</h3>
        </div>
    </div>

    <!-- Pending / Menunggu BPBD -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-clock class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium whitespace-nowrap">Menunggu BPBD</p>
            <h3 class="text-xl font-bold text-amber-600">{{ $pendingCount ?? 0 }}</h3>
        </div>
    </div>

    <!-- Disetujui BPBD -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-check-circle class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium whitespace-nowrap">Disetujui BPBD</p>
            <h3 class="text-xl font-bold text-emerald-600">{{ $disetujuiCount ?? 0 }}</h3>
        </div>
    </div>

    <!-- Ditolak BPBD -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-center">
        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mr-4 shrink-0">
            <x-heroicon-s-x-circle class="w-6 h-6" />
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium whitespace-nowrap">Ditolak BPBD</p>
            <h3 class="text-xl font-bold text-rose-600">{{ $ditolakCount ?? 0 }}</h3>
        </div>
    </div>
</div>