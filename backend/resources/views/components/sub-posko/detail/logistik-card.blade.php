@props(['subPosko'])

<div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold text-slate-800">Logistik Tersedia</h3>
            <p class="text-sm text-slate-500">Daftar logistik real-time yang tersimpan di posko ini.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @forelse($subPosko->stokInventaris ?? [] as $stok)
            <div class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center space-x-4">
                <div class="w-12 h-12 rounded-full bg-indigo-100/80 flex items-center justify-center text-indigo-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-700">{{ $stok->nama_barang ?? ($stok->inventarisGudang->nama_barang ?? 'Barang') }}</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $stok->jumlah ?? 0 }}</p>
                    <p class="text-xs text-slate-400">{{ $stok->satuan ?? ($stok->inventarisGudang->satuan ?? 'Unit') }}</p>
                </div>
            </div>
        @empty
            <div class="col-span-full py-8 text-center text-slate-400 text-xs">
                Belum ada data stok logistik untuk posko ini.
            </div>
        @endforelse
    </div>

    <div class="p-4 rounded-xl bg-indigo-50/60 border border-indigo-100/80 flex items-center space-x-3 text-sm text-indigo-700">
        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>Data stok diperbarui pada {{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
    </div>
</div>