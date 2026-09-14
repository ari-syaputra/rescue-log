@props(['riwayatPenyaluran'])

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden w-full p-4 sm:p-0">

    <div class="block sm:hidden space-y-3">
        @forelse($riwayatPenyaluran as $riwayat)
            <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-200/80 flex flex-col gap-2.5">
                <!-- Header Kartu: Kode & Waktu -->
                <div class="flex items-center justify-between border-b border-gray-200/60 pb-2">
                    <span class="font-bold text-gray-900 text-xs">
                        #DIST-{{ str_pad($riwayat->id, 3, '0', STR_PAD_LEFT) }}
                    </span>
                    <span class="text-[11px] text-gray-500 font-medium">
                        {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : '-' }} WIB
                    </span>
                </div>

                <!-- Detail Barang & Jumlah Keluar -->
                <div class="flex items-start justify-between gap-2 pt-0.5">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Barang Disalurkan</span>
                        <h4 class="font-bold text-gray-900 text-sm leading-tight">
                            {{ $riwayat->barang->nama_barang ?? 'Barang Terhapus' }}
                        </h4>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Jumlah Keluar</span>
                        <span class="font-extrabold text-sm text-amber-600">
                            -{{ $riwayat->jumlah }}
                        </span>
                    </div>
                </div>

                <!-- Keterangan Penyaluran -->
                <div class="pt-1 text-xs border-t border-gray-200/40">
                    <span class="text-gray-400 block text-[10px] uppercase font-semibold">Keterangan</span>
                    <p class="text-gray-600 font-medium leading-relaxed">
                        {{ $riwayat->keterangan ?? '-' }}
                    </p>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-gray-400 text-xs font-medium bg-gray-50 rounded-xl border border-dashed border-gray-200">
                Belum ada riwayat penyaluran logistik.
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP: TABEL STANDAR (Sembunyi di HP) -->
    <div class="hidden sm:block overflow-x-auto w-full">
        <table class="w-full text-left border-collapse min-w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="py-3.5 px-6 w-[21%] text-center">Waktu Transaksi</th>
                    <th class="py-3.5 px-6 w-[29%]">Barang Disalurkan</th>
                    <th class="py-3.5 px-6 w-[25%]">Jumlah Keluar</th>
                    <th class="py-3.5 px-6 w-[15%]">Keterangan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                @forelse($riwayatPenyaluran as $riwayat)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-4 px-6 text-xs text-gray-500 align-middle text-center">
                            <span class="font-bold text-gray-900 block">#DIST-{{ str_pad($riwayat->id, 3, '0', STR_PAD_LEFT) }}</span>
                            {{ $riwayat->created_at ? $riwayat->created_at->format('d M Y, H:i') : '-' }} WIB
                        </td>
                        <td class="py-4 px-6 font-semibold text-gray-900 align-middle">
                            {{ $riwayat->barang->nama_barang ?? 'Barang Terhapus' }}
                        </td>
                        <td class="py-4 px-6 font-bold text-amber-600 align-middle whitespace-nowrap">
                            -{{ $riwayat->jumlah }}
                        </td>
                        <td class="py-4 px-6 text-gray-600 align-middle break-words">
                            {{ $riwayat->keterangan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-12 text-center text-gray-400 align-middle">
                            Belum ada riwayat penyaluran logistik.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="mt-4 sm:mt-0 px-4 py-3 sm:px-6 sm:py-4 bg-gray-50 sm:border-t border-gray-200 rounded-b-2xl">
        {{ $riwayatPenyaluran->links() }}
    </div>
</div>