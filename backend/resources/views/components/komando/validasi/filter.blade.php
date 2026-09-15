<div class="bg-white p-4 rounded-2xl shadow-xs border border-blue-100">
    <form method="GET" action="{{ route('komando.validasi.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
        <!-- Input Search -->
        <div class="md:col-span-6 relative">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-blue-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Kode Pengajuan..." class="w-full h-10 pl-10 pr-4 text-sm bg-blue-50/40 border border-blue-200/70 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition">
        </div>

        <!-- Filter Status -->
        <div class="md:col-span-4">
            <select name="status" class="w-full h-10 px-3.5 text-sm bg-blue-50/40 border border-blue-200/70 rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition cursor-pointer">
                <option value="">Semua Status</option>
                <option value="pending" {{ strtolower(request('status')) == 'pending' ? 'selected' : '' }}>Menunggu Validasi</option>
                <option value="disetujui" {{ strtolower(request('status')) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="selesai" {{ strtolower(request('status')) == 'selesai' ? 'selected' : '' }}>Diterima Sub-Posko</option>
                <option value="ditolak" {{ strtolower(request('status')) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Tombol Filter -->
        <div class="md:col-span-2">
            <button type="submit"
                class="w-full h-10 inline-flex items-center justify-center px-4 bg-blue-700 hover:bg-blue-800 active:bg-blue-800 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-blue-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
        </div>
    </form>
</div>