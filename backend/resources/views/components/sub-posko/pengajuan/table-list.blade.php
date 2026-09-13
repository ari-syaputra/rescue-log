<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200 uppercase text-xs">
                <tr>
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Kode Pengajuan</th>
                    <th class="py-3 px-4">Bencana</th>
                    <th class="py-3 px-4">Tanggal Diajukan</th>
                    <th class="py-3 px-4 text-center">Rincian Barang</th>
                    <th class="py-3 px-4 text-center">Status BPBD</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pengajuans as $index => $pengajuan)
                    <tr class="hover:bg-gray-50/80 transition">
                        <td class="py-3.5 px-4 font-medium">
                            {{ method_exists($pengajuans, 'firstItem') ? $pengajuans->firstItem() + $index : $index + 1 }}
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-blue-600">
                            {{ $pengajuan->kode_pengajuan }}
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="font-medium text-gray-800">{{ $pengajuan->bencana->nama_bencana ?? '-' }}</span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-500 whitespace-nowrap">
                            {{ $pengajuan->tanggal_pengajuan ? \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="py-3.5 px-4">
                            <!-- Menampilkan ringkasan 12 item eksplisit -->
                            <div class="flex flex-wrap justify-center gap-1 max-w-xs">
                                @if(($pengajuan->beras_kg ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Beras: {{ $pengajuan->beras_kg }} kg</span> @endif
                                @if(($pengajuan->air_minum_dus ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Air: {{ $pengajuan->air_minum_dus }} dus</span> @endif
                                @if(($pengajuan->makanan_kaleng_pack ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Mak. Kaleng: {{ $pengajuan->makanan_kaleng_pack }} pack</span> @endif
                                @if(($pengajuan->makanan_bayi_pack ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Mak. Bayi: {{ $pengajuan->makanan_bayi_pack }} pack</span> @endif
                                @if(($pengajuan->minyak_goreng_liter ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Minyak: {{ $pengajuan->minyak_goreng_liter }} L</span> @endif
                                @if(($pengajuan->popok_bayi_pcs ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Popok Bayi: {{ $pengajuan->popok_bayi_pcs }} pcs</span> @endif
                                @if(($pengajuan->popok_dewasa_pcs ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Popok Dewasa: {{ $pengajuan->popok_dewasa_pcs }} pcs</span> @endif
                                @if(($pengajuan->pembalut_wanita_pack ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Pembalut: {{ $pengajuan->pembalut_wanita_pack }} pack</span> @endif
                                @if(($pengajuan->hygiene_kit_paket ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Hygiene Kit: {{ $pengajuan->hygiene_kit_paket }} pkt</span> @endif
                                @if(($pengajuan->selimut_pcs ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Selimut: {{ $pengajuan->selimut_pcs }} pcs</span> @endif
                                @if(($pengajuan->matras_terpal_pcs ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">Terpal: {{ $pengajuan->matras_terpal_pcs }} pcs</span> @endif
                                @if(($pengajuan->obat_p3k_paket ?? 0) > 0) <span class="bg-gray-100 text-gray-700 text-[11px] px-2 py-0.5 rounded border">P3K: {{ $pengajuan->obat_p3k_paket }} pkt</span> @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            @if($pengajuan->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700">Menunggu BPBD</span>
                            @elseif($pengajuan->status == 'disetujui')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700">Disetujui BPBD</span>
                            @elseif($pengajuan->status == 'dieskalasi_provinsi')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-700">Dieskalasi ke Provinsi</span>
                            @elseif($pengajuan->status == 'dalam_pengiriman')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-700">Dalam Pengiriman</span>
                            @elseif($pengajuan->status == 'selesai')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">Selesai</span>
                            @elseif($pengajuan->status == 'ditolak')
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-700">Ditolak</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-600">{{ ucfirst($pengajuan->status) }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center space-x-2">
                                <!-- Tombol Detail (Tanpa eager load details.barang) -->
                                <button onclick="showDetail({{ json_encode($pengajuan->load(['bencana', 'user', 'posko'])) }})" 
                                        class="px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-200 transition cursor-pointer">
                                    Detail
                                </button>

                                <!-- Tombol Batalkan (Hanya jika status pending) -->
                                @if($pengajuan->status == 'pending')
                                    <form action="{{ route('komando.pengajuan.destroy', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 text-xs font-medium rounded-md hover:bg-rose-100 transition cursor-pointer">
                                            Batal
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-gray-400 italic">
                            Belum ada riwayat pengajuan kebutuhan ke BPBD.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if(method_exists($pengajuans, 'hasPages') && $pengajuans->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $pengajuans->links() }}
        </div>
    @endif
</div>