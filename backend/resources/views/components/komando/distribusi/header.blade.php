<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Distribusi Logistik dan Fleet Tracking</h1>
        <p class="text-sm text-slate-500 mt-0.5">Monitoring pergerakan armada logistik dan manajemen kendala rute jalan secara real-time.</p>
    </div>

    <div class="flex items-center gap-3">
        <!-- TOMBOL TAMBAH ARMADA (Ikon Truk) -->
        <button type="button" onclick="openArmadaModal()" 
            class="h-10 inline-flex items-center justify-center px-4 bg-blue-700 hover:bg-blue-800 active:bg-blue-800 text-white text-sm font-medium rounded-md hover:shadow-md focus:ring-4 focus:ring-blue-200 transition shadow-sm whitespace-nowrap cursor-pointer">
            <x-heroicon-s-truck class="w-4 h-4 mr-2" />
            <span>Tambah Armada</span>
        </button>

        <!-- TOMBOL LAPORKAN KENDALA JALAN (Ikon Peringatan) -->
        <button type="button" onclick="openKendalaModal()" 
            class="h-10 inline-flex items-center justify-center px-4 bg-orange-500 hover:bg-orange-600 active:bg-orange-700 text-white text-sm font-medium rounded-md hover:shadow-md focus:ring-4 focus:ring-orange-200 transition shadow-sm whitespace-nowrap cursor-pointer">
            <x-heroicon-s-exclamation-triangle class="w-4 h-4 mr-2" />
            <span>Laporkan Kendala Jalan</span>
        </button>
    </div>
</div>