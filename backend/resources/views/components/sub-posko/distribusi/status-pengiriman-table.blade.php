@props(['pengirimans', 'pengajuans' => []])

@php
    // Kumpulkan ID Pengajuan yang sudah memiliki record pengiriman
    $pengajuanIdsInPengiriman = $pengirimans->pluck('pengajuan_id')->filter()->toArray();

    // Filter pengajuan yang belum masuk ke tabel pengiriman (seperti status pending/baru diajukan)
    $pengajuanUnsent = collect($pengajuans)->filter(function ($p) use ($pengajuanIdsInPengiriman) {
        return !in_array($p->id, $pengajuanIdsInPengiriman);
    });
@endphp

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden p-4 sm:p-6 font-sans">
    <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
        <x-heroicon-s-truck class="w-5 h-5 text-emerald-600 shrink-0" />
        <span>Status Pengiriman Logistik dari Posko Komando</span>
    </h3>

    <!-- 1. TAMPILAN MOBILE: KARTU (HP / Layar Kecil) -->
    <div class="block sm:hidden space-y-3">
        <!-- Render Pengajuan yang Masih Pending / Menunggu Verifikasi -->
        @foreach($pengajuanUnsent as $p)
            @php
                $details = [];
                if ($p->beras_kg > 0) $details[] = ['nama' => 'Beras', 'jumlah' => $p->beras_kg . ' Kg'];
                if ($p->air_minum_dus > 0) $details[] = ['nama' => 'Air Minum', 'jumlah' => $p->air_minum_dus . ' Dus'];
                if ($p->makanan_kaleng_pack > 0) $details[] = ['nama' => 'Makanan Kaleng', 'jumlah' => $p->makanan_kaleng_pack . ' Pack'];
                if ($p->makanan_bayi_pack > 0) $details[] = ['nama' => 'Makanan Bayi', 'jumlah' => $p->makanan_bayi_pack . ' Pack'];
                if ($p->minyak_goreng_liter > 0) $details[] = ['nama' => 'Minyak Goreng', 'jumlah' => $p->minyak_goreng_liter . ' Liter'];
                if ($p->popok_bayi_pcs > 0) $details[] = ['nama' => 'Popok Bayi', 'jumlah' => $p->popok_bayi_pcs . ' Pcs'];
                if ($p->popok_dewasa_pcs > 0) $details[] = ['nama' => 'Popok Dewasa', 'jumlah' => $p->popok_dewasa_pcs . ' Pcs'];
                if ($p->pembalut_wanita_pack > 0) $details[] = ['nama' => 'Pembalut Wanita', 'jumlah' => $p->pembalut_wanita_pack . ' Pack'];
                if ($p->hygiene_kit_paket > 0) $details[] = ['nama' => 'Hygiene Kit', 'jumlah' => $p->hygiene_kit_paket . ' Paket'];
                if ($p->selimut_pcs > 0) $details[] = ['nama' => 'Selimut', 'jumlah' => $p->selimut_pcs . ' Pcs'];
                if ($p->matras_terpal_pcs > 0) $details[] = ['nama' => 'Matras / Terpal', 'jumlah' => $p->matras_terpal_pcs . ' Pcs'];
                if ($p->obat_p3k_paket > 0) $details[] = ['nama' => 'Obat-obatan / P3K', 'jumlah' => $p->obat_p3k_paket . ' Paket'];
            @endphp
            <div class="p-4 bg-amber-50/40 rounded-xl border border-amber-200/60 flex flex-col gap-3">
                <div class="flex items-center justify-between border-b border-amber-200/50 pb-2.5">
                    <span class="font-bold text-gray-900 text-sm">#{{ $p->kode_pengajuan }}</span>
                    <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-amber-100 text-amber-800 rounded-full inline-flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Verifikasi
                    </span>
                </div>
                <div class="space-y-1.5 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Item Diajukan</span>
                        <p class="text-gray-800 font-medium leading-relaxed">
                            {{ count($details) > 0 ? implode(', ', array_map(fn($d) => $d['nama'] . ' (' . $d['jumlah'] . ')', $details)) : 'Logistik Bantuan' }}
                        </p>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Waktu Pengajuan</span>
                        <p class="text-amber-800 font-medium">{{ \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Render Pengajuan yang Sudah Memiliki Status Pengiriman -->
        @forelse($pengirimans as $item)
            @php
                $p = $item->pengajuan;
                $details = [];
                if ($p) {
                    if ($p->beras_kg > 0) $details[] = ['nama' => 'Beras', 'jumlah' => $p->beras_kg . ' Kg'];
                    if ($p->air_minum_dus > 0) $details[] = ['nama' => 'Air Minum', 'jumlah' => $p->air_minum_dus . ' Dus'];
                    if ($p->makanan_kaleng_pack > 0) $details[] = ['nama' => 'Makanan Kaleng', 'jumlah' => $p->makanan_kaleng_pack . ' Pack'];
                    if ($p->makanan_bayi_pack > 0) $details[] = ['nama' => 'Makanan Bayi', 'jumlah' => $p->makanan_bayi_pack . ' Pack'];
                    if ($p->minyak_goreng_liter > 0) $details[] = ['nama' => 'Minyak Goreng', 'jumlah' => $p->minyak_goreng_liter . ' Liter'];
                    if ($p->popok_bayi_pcs > 0) $details[] = ['nama' => 'Popok Bayi', 'jumlah' => $p->popok_bayi_pcs . ' Pcs'];
                    if ($p->popok_dewasa_pcs > 0) $details[] = ['nama' => 'Popok Dewasa', 'jumlah' => $p->popok_dewasa_pcs . ' Pcs'];
                    if ($p->pembalut_wanita_pack > 0) $details[] = ['nama' => 'Pembalut Wanita', 'jumlah' => $p->pembalut_wanita_pack . ' Pack'];
                    if ($p->hygiene_kit_paket > 0) $details[] = ['nama' => 'Hygiene Kit', 'jumlah' => $p->hygiene_kit_paket . ' Paket'];
                    if ($p->selimut_pcs > 0) $details[] = ['nama' => 'Selimut', 'jumlah' => $p->selimut_pcs . ' Pcs'];
                    if ($p->matras_terpal_pcs > 0) $details[] = ['nama' => 'Matras / Terpal', 'jumlah' => $p->matras_terpal_pcs . ' Pcs'];
                    if ($p->obat_p3k_paket > 0) $details[] = ['nama' => 'Obat-obatan / P3K', 'jumlah' => $p->obat_p3k_paket . ' Paket'];
                }

                $statusDistribusi = strtolower($item->status_distribusi ?? '');
                $statusPengajuan = strtolower($p->status ?? '');

                $isSelesai = in_array($statusDistribusi, ['selesai', 'diterima di posko']) || $statusPengajuan == 'selesai';
                $isDalamPengiriman = in_array($statusDistribusi, ['dalam_pengiriman', 'dalam_perjalanan']) || $statusPengajuan == 'dalam_pengiriman';
                $isDisetujui = in_array($statusPengajuan, ['disetujui', 'disetujui_sebagian']) && !$isDalamPengiriman && !$isSelesai;
            @endphp

            <div class="p-4 bg-gray-50/70 rounded-xl border border-gray-100 flex flex-col gap-3">
                <div class="flex items-center justify-between border-b border-gray-200/60 pb-2.5">
                    <span class="font-bold text-gray-900 text-sm">#{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}</span>
                    <div>
                        @if ($isSelesai)
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-emerald-100 text-emerald-700 rounded-full inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Diterima
                            </span>
                        @elseif($isDalamPengiriman)
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-blue-100 text-blue-700 rounded-full inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Dalam Pengiriman
                            </span>
                        @elseif($isDisetujui)
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-amber-100 text-amber-700 rounded-full inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Disetujui
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 text-[11px] font-semibold bg-gray-100 text-gray-700 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $item->status_distribusi)) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Item Dikirim</span>
                        <p class="text-gray-800 font-medium leading-relaxed">
                            {{ count($details) > 0 ? implode(', ', array_map(fn($d) => $d['nama'] . ' (' . $d['jumlah'] . ')', $details)) : 'Logistik Bantuan Bencana' }}
                        </p>
                    </div>

                    <div>
                        <span class="text-gray-400 block text-[10px] uppercase font-semibold">Estimasi / Waktu Sampai</span>
                        <p class="font-medium">
                            @if ($item->waktu_diterima)
                                <span class="text-emerald-700">{{ \Carbon\Carbon::parse($item->waktu_diterima)->format('d M Y, H:i') }} WIB</span>
                            @elseif($isDalamPengiriman)
                                <span class="text-blue-700">{{ $item->estimasi_waktu ?? 'Dalam Perjalanan' }}</span>
                            @else
                                <span class="text-gray-400 italic">Menunggu Pengiriman</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="pt-2 border-t border-gray-200/60 flex justify-end">
                    @if ($isDalamPengiriman)
                        <button type="button"
                            onclick="openDetailModal('{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}', '{{ route('lapangan.stok.konfirmasi', $item->id) }}', {{ json_encode($details) }}, '{{ $item->status_distribusi }}', '{{ $p->catatan_komando ?? '' }}')"
                            class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <x-heroicon-s-check-circle class="w-4 h-4 shrink-0" />
                            <span>Konfirmasi Sampai</span>
                        </button>
                    @else
                        <button type="button"
                            onclick="openDetailModal('{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}', null, {{ json_encode($details) }}, '{{ $item->status_distribusi }}', '{{ $p->catatan_komando ?? '' }}')"
                            class="w-full py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg text-xs font-semibold transition text-center cursor-pointer">
                            Lihat Detail
                        </button>
                    @endif
                </div>
            </div>
        @empty
            @if(count($pengajuanUnsent) === 0)
                <div class="py-8 text-center text-gray-400 text-xs font-medium bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    Belum ada pengiriman logistik aktif dari Posko Komando.
                </div>
            @endif
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP: TABEL STANDAR (Layar Komputer) -->
    <div class="hidden sm:block overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    <th class="py-3 px-4">ID Pengajuan</th>
                    <th class="py-3 px-4">Item Dikirim</th>
                    <th class="py-3 px-4">Status Distribusi</th>
                    <th class="py-3 px-4">Estimasi / Waktu Sampai</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-700">
                <!-- 2A. Render Pengajuan Pending / Menunggu Verifikasi Komando -->
                @foreach($pengajuanUnsent as $p)
                    @php
                        $details = [];
                        if ($p->beras_kg > 0) $details[] = ['nama' => 'Beras', 'jumlah' => $p->beras_kg . ' Kg'];
                        if ($p->air_minum_dus > 0) $details[] = ['nama' => 'Air Minum', 'jumlah' => $p->air_minum_dus . ' Dus'];
                        if ($p->makanan_kaleng_pack > 0) $details[] = ['nama' => 'Makanan Kaleng', 'jumlah' => $p->makanan_kaleng_pack . ' Pack'];
                        if ($p->makanan_bayi_pack > 0) $details[] = ['nama' => 'Makanan Bayi', 'jumlah' => $p->makanan_bayi_pack . ' Pack'];
                        if ($p->minyak_goreng_liter > 0) $details[] = ['nama' => 'Minyak Goreng', 'jumlah' => $p->minyak_goreng_liter . ' Liter'];
                        if ($p->popok_bayi_pcs > 0) $details[] = ['nama' => 'Popok Bayi', 'jumlah' => $p->popok_bayi_pcs . ' Pcs'];
                        if ($p->popok_dewasa_pcs > 0) $details[] = ['nama' => 'Popok Dewasa', 'jumlah' => $p->popok_dewasa_pcs . ' Pcs'];
                        if ($p->pembalut_wanita_pack > 0) $details[] = ['nama' => 'Pembalut Wanita', 'jumlah' => $p->pembalut_wanita_pack . ' Pack'];
                        if ($p->hygiene_kit_paket > 0) $details[] = ['nama' => 'Hygiene Kit', 'jumlah' => $p->hygiene_kit_paket . ' Paket'];
                        if ($p->selimut_pcs > 0) $details[] = ['nama' => 'Selimut', 'jumlah' => $p->selimut_pcs . ' Pcs'];
                        if ($p->matras_terpal_pcs > 0) $details[] = ['nama' => 'Matras / Terpal', 'jumlah' => $p->matras_terpal_pcs . ' Pcs'];
                        if ($p->obat_p3k_paket > 0) $details[] = ['nama' => 'Obat-obatan / P3K', 'jumlah' => $p->obat_p3k_paket . ' Paket'];
                    @endphp
                    <tr class="bg-amber-50/30 hover:bg-amber-50/60 transition">
                        <td class="py-3.5 px-4 font-bold text-gray-900">
                            #{{ $p->kode_pengajuan }}
                        </td>
                        <td class="py-3.5 px-4 max-w-xs">
                            <div class="truncate text-gray-800 font-medium">
                                {{ count($details) > 0 ? implode(', ', array_map(fn($d) => $d['nama'] . ' (' . $d['jumlah'] . ')', array_slice($details, 0, 3))) : 'Logistik Bantuan' }}
                                @if (count($details) > 3)
                                    <span class="text-xs text-blue-600 font-bold">+{{ count($details) - 3 }} barang lagi</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-800 rounded-full inline-flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Verifikasi Komando
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-gray-500 italic text-xs">
                            Diajukan {{ \Carbon\Carbon::parse($p->created_at)->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <button type="button"
                                onclick="openDetailModal('{{ $p->kode_pengajuan }}', null, {{ json_encode($details) }}, 'Menunggu Verifikasi', 'Pengajuan baru berhasil dibuat dan menunggu persetujuan Posko Komando.')"
                                class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold transition cursor-pointer">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                @endforeach

                <!-- 2B. Render Pengajuan yang Sudah Berstatus Pengiriman -->
                @forelse($pengirimans as $item)
                    @php
                        $p = $item->pengajuan;
                        $details = [];
                        if ($p) {
                            if ($p->beras_kg > 0) $details[] = ['nama' => 'Beras', 'jumlah' => $p->beras_kg . ' Kg'];
                            if ($p->air_minum_dus > 0) $details[] = ['nama' => 'Air Minum', 'jumlah' => $p->air_minum_dus . ' Dus'];
                            if ($p->makanan_kaleng_pack > 0) $details[] = ['nama' => 'Makanan Kaleng', 'jumlah' => $p->makanan_kaleng_pack . ' Pack'];
                            if ($p->makanan_bayi_pack > 0) $details[] = ['nama' => 'Makanan Bayi', 'jumlah' => $p->makanan_bayi_pack . ' Pack'];
                            if ($p->minyak_goreng_liter > 0) $details[] = ['nama' => 'Minyak Goreng', 'jumlah' => $p->minyak_goreng_liter . ' Liter'];
                            if ($p->popok_bayi_pcs > 0) $details[] = ['nama' => 'Popok Bayi', 'jumlah' => $p->popok_bayi_pcs . ' Pcs'];
                            if ($p->popok_dewasa_pcs > 0) $details[] = ['nama' => 'Popok Dewasa', 'jumlah' => $p->popok_dewasa_pcs . ' Pcs'];
                            if ($p->pembalut_wanita_pack > 0) $details[] = ['nama' => 'Pembalut Wanita', 'jumlah' => $p->pembalut_wanita_pack . ' Pack'];
                            if ($p->hygiene_kit_paket > 0) $details[] = ['nama' => 'Hygiene Kit', 'jumlah' => $p->hygiene_kit_paket . ' Paket'];
                            if ($p->selimut_pcs > 0) $details[] = ['nama' => 'Selimut', 'jumlah' => $p->selimut_pcs . ' Pcs'];
                            if ($p->matras_terpal_pcs > 0) $details[] = ['nama' => 'Matras / Terpal', 'jumlah' => $p->matras_terpal_pcs . ' Pcs'];
                            if ($p->obat_p3k_paket > 0) $details[] = ['nama' => 'Obat-obatan / P3K', 'jumlah' => $p->obat_p3k_paket . ' Paket'];
                        }

                        $statusDistribusi = strtolower($item->status_distribusi ?? '');
                        $statusPengajuan = strtolower($p->status ?? '');

                        $isSelesai = in_array($statusDistribusi, ['selesai', 'diterima di posko']) || $statusPengajuan == 'selesai';
                        $isDalamPengiriman = in_array($statusDistribusi, ['dalam_pengiriman', 'dalam_perjalanan']) || $statusPengajuan == 'dalam_pengiriman';
                        $isDisetujui = in_array($statusPengajuan, ['disetujui', 'disetujui_sebagian']) && !$isDalamPengiriman && !$isSelesai;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="py-3.5 px-4 font-bold text-gray-900">
                            #{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}
                        </td>
                        <td class="py-3.5 px-4 max-w-xs">
                            <div class="truncate text-gray-800 font-medium">
                                @if (count($details) > 0)
                                    {{ implode(', ', array_map(fn($d) => $d['nama'] . ' (' . $d['jumlah'] . ')', array_slice($details, 0, 3))) }}
                                    @if (count($details) > 3)
                                        <span class="text-xs text-blue-600 font-bold">+{{ count($details) - 3 }} barang lagi</span>
                                    @endif
                                @else
                                    Logistik Bantuan Bencana
                                @endif
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if ($isSelesai)
                                <span class="px-3 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Diterima di Posko
                                </span>
                            @elseif($isDalamPengiriman)
                                <span class="px-3 py-1 text-xs font-semibold bg-blue-100 text-blue-700 rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Dalam Pengiriman
                                </span>
                            @elseif($isDisetujui)
                                <span class="px-3 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Disetujui (Menunggu Armada)
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full inline-flex items-center gap-1.5">
                                    {{ ucfirst(str_replace('_', ' ', $item->status_distribusi)) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-gray-600">
                            @if ($item->waktu_diterima)
                                <span class="text-emerald-700 font-medium">{{ \Carbon\Carbon::parse($item->waktu_diterima)->format('d M Y, H:i') }} WIB</span>
                            @elseif($isDalamPengiriman)
                                <span class="text-blue-700 font-medium">{{ $item->estimasi_waktu ?? 'Dalam Perjalanan' }}</span>
                            @else
                                <span class="text-gray-400 italic">Menunggu Pengiriman</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            @if ($isDalamPengiriman)
                                <button type="button"
                                    onclick="openDetailModal('{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}', '{{ route('lapangan.stok.konfirmasi', $item->id) }}', {{ json_encode($details) }}, '{{ $item->status_distribusi }}', '{{ $p->catatan_komando ?? '' }}')"
                                    class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5">
                                    <x-heroicon-s-check-circle class="w-3.5 h-3.5 shrink-0" />
                                    <span>Konfirmasi Sampai</span>
                                </button>
                            @else
                                <button type="button"
                                    onclick="openDetailModal('{{ $p->kode_pengajuan ?? 'REQ-' . $item->id }}', null, {{ json_encode($details) }}, '{{ $item->status_distribusi }}', '{{ $p->catatan_komando ?? '' }}')"
                                    class="px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-xs font-semibold transition cursor-pointer">
                                    Lihat Detail
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    @if(count($pengajuanUnsent) === 0)
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-400 text-xs font-medium">
                                Belum ada pengiriman logistik aktif dari Posko Komando.
                            </td>
                        </tr>
                    @endif
                @endforelse
            </tbody>
        </table>
    </div>
</div>