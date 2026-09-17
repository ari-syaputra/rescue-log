@props(['pengajuans'])

<div class="bg-white rounded-2xl shadow-xs border border-blue-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead class="bg-slate-100/80 border-b border-slate-200/60">
                <tr class="text-slate-600 text-xs font-bold uppercase tracking-wider">
                    <th class="py-4 px-6 w-48">NO. PENGAJUAN</th>
                    <th class="py-4 px-6 w-44">SUB POSKO</th>
                    <th class="py-4 px-6 w-40">BENCANA</th>
                    <th class="py-4 px-6 w-32">STATUS</th>
                    <th class="py-4 px-6">RINGKASAN ITEM</th>
                    <th class="py-4 px-6 text-right w-48">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-700">
                @forelse($pengajuans as $item)
                    @php
                        // Kumpulkan semua item yang bernilai > 0 ke dalam array
                        $itemList = [];
                        if(($item->beras_kg ?? 0) > 0) $itemList[] = ['label' => 'Beras', 'qty' => $item->beras_kg, 'unit' => 'kg'];
                        if(($item->air_minum_dus ?? 0) > 0) $itemList[] = ['label' => 'Air', 'qty' => $item->air_minum_dus, 'unit' => 'dus'];
                        if(($item->makanan_kaleng_pack ?? 0) > 0) $itemList[] = ['label' => 'Mkn Kaleng', 'qty' => $item->makanan_kaleng_pack, 'unit' => 'pack'];
                        if(($item->makanan_bayi_pack ?? 0) > 0) $itemList[] = ['label' => 'Mkn Bayi', 'qty' => $item->makanan_bayi_pack, 'unit' => 'pack'];
                        if(($item->minyak_goreng_liter ?? 0) > 0) $itemList[] = ['label' => 'Minyak', 'qty' => $item->minyak_goreng_liter, 'unit' => 'L'];
                        if(($item->popok_bayi_pcs ?? 0) > 0) $itemList[] = ['label' => 'Popok Bayi', 'qty' => $item->popok_bayi_pcs, 'unit' => 'pcs'];
                        if(($item->popok_dewasa_pcs ?? 0) > 0) $itemList[] = ['label' => 'Popok Dws', 'qty' => $item->popok_dewasa_pcs, 'unit' => 'pcs'];
                        if(($item->pembalut_wanita_pack ?? 0) > 0) $itemList[] = ['label' => 'Pembalut', 'qty' => $item->pembalut_wanita_pack, 'unit' => 'pack'];
                        if(($item->hygiene_kit_paket ?? 0) > 0) $itemList[] = ['label' => 'Hygiene', 'qty' => $item->hygiene_kit_paket, 'unit' => 'pkt'];
                        if(($item->selimut_pcs ?? 0) > 0) $itemList[] = ['label' => 'Selimut', 'qty' => $item->selimut_pcs, 'unit' => 'pcs'];
                        if(($item->matras_terpal_pcs ?? 0) > 0) $itemList[] = ['label' => 'Matras', 'qty' => $item->matras_terpal_pcs, 'unit' => 'pcs'];
                        if(($item->obat_p3k_paket ?? 0) > 0) $itemList[] = ['label' => 'P3K', 'qty' => $item->obat_p3k_paket, 'unit' => 'pkt'];

                        $limitDisplay = 3; // Jumlah item ringkasan yang ditampilkan di awal
                        $totalItems = count($itemList);
                    @endphp

                    <tr class="hover:bg-blue-50/30 transition-colors">
                        <!-- KODE PENGAJUAN -->
                        <td class="py-4 px-6 align-top font-semibold text-blue-600 whitespace-nowrap">
                            <span class="bg-blue-50 text-blue-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-blue-100">
                                {{ $item->kode_pengajuan }}
                            </span>
                        </td>

                        <!-- SUB POSKO -->
                        <td class="py-4 px-6 align-top font-semibold text-slate-800 whitespace-nowrap">
                            {{ $item->posko->nama_posko ?? $item->user->name ?? '-' }}
                        </td>

                        <!-- BENCANA -->
                        <td class="py-4 px-6 align-top text-slate-700 font-medium">
                            {{ $item->bencana->jenis_bencana ?? $item->posko->bencana->jenis_bencana ?? $item->bencana->nama_bencana ?? '-' }}
                        </td>

                        <!-- STATUS -->
                        <td class="py-4 px-6 align-top whitespace-nowrap">
                            @if(strtolower($item->status) == 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-amber-50 text-amber-700 rounded-lg border border-amber-200/60">
                                    Pending
                                </span>
                            @elseif(strtolower($item->status) == 'disetujui')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200/60">
                                    Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold bg-slate-100 text-slate-600 rounded-lg border border-slate-200">
                                    {{ ucfirst($item->status) }}
                                </span>
                            @endif
                        </td>

                        <!-- RINGKASAN ITEM (TOGGLE COLLAPSE/EXPAND) -->
                        <td class="py-4 px-6 align-top text-xs">
                            <div class="space-y-1">
                                <!-- Display 3 Item Pertama -->
                                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                                    @foreach(array_slice($itemList, 0, $limitDisplay) as $i)
                                        <div><b>{{ $i['label'] }}:</b> {{ $i['qty'] }} {{ $i['unit'] }}</div>
                                    @endforeach
                                </div>

                                <!-- Detail Item Sisa (Hidden By Default) -->
                                @if($totalItems > $limitDisplay)
                                    <div id="more-items-{{ $item->id }}" class="hidden grid grid-cols-2 gap-x-4 gap-y-1 pt-1 border-t border-slate-100">
                                        @foreach(array_slice($itemList, $limitDisplay) as $i)
                                            <div><b>{{ $i['label'] }}:</b> {{ $i['qty'] }} {{ $i['unit'] }}</div>
                                        @endforeach
                                    </div>

                                    <!-- Tombol Toggle Tampilkan Semua -->
                                    <button type="button" 
                                            onclick="toggleMoreItems({{ $item->id }}, this)" 
                                            class="mt-1.5 text-[11px] font-bold text-blue-600 hover:text-blue-800 focus:outline-none inline-flex items-center gap-1 cursor-pointer">
                                        <span>+{{ $totalItems - $limitDisplay }} item lainnya</span>
                                        <span>▼</span>
                                    </button>
                                @endif
                            </div>
                        </td>

                        <!-- AKSI -->
                        <td class="py-4 px-6 text-right align-top whitespace-nowrap">
                            @if(strtolower($item->status) == 'pending')
                                <button type="button" 
                                        onclick="openModalApprove({{ json_encode($item) }})"
                                        class="h-9 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium text-xs rounded-lg transition shadow-xs cursor-pointer">
                                    Setujui & Sesuaikan
                                </button>
                            @else
                                <span class="text-xs text-slate-400 italic font-semibold">Selesai</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-16 text-center text-slate-400 italic text-sm">
                            Belum ada pengajuan masuk dari Sub-Posko.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(method_exists($pengajuans, 'hasPages') && $pengajuans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pengajuans->links() }}
        </div>
    @endif
</div>

<!-- MODAL PENYESUAIAN & CONFIRMATION LOGISTIK -->
<div id="modalApprove" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-2xl border border-slate-100 max-h-[90vh] flex flex-col">
        
        <!-- Header Modal -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-900">Validasi & Penyesuaian Logistik</h3>
                <p class="text-xs text-slate-500">Kode Pengajuan: <span id="modal_kode_pengajuan" class="font-bold text-blue-600"></span></p>
            </div>
            <button type="button" onclick="closeModalApprove()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">✕</button>
        </div>

        <!-- Form Penyesuaian -->
        <form id="formApprove" method="POST" class="space-y-4 overflow-y-auto pr-1 flex-1">
            @csrf
            <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100 text-xs text-blue-900">
                💡 <b>Petunjuk:</b> Periksa dan atur jumlah logistik yang <b>disetujui</b> sesuai dengan ketersediaan stok di Posko Komando.
            </div>

            <!-- Table Input Items -->
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase">
                        <th class="py-2.5 px-3">Nama Barang</th>
                        <th class="py-2.5 px-3 text-center">Permintaan Sub-Posko</th>
                        <th class="py-2.5 px-3 text-center w-36">Jumlah ACC (Disetujui)</th>
                    </tr>
                </thead>
                <tbody id="modal_items_body" class="divide-y divide-slate-100">
                    <!-- Items JS Injected Here -->
                </tbody>
            </table>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Komando (Opsional)</label>
                <textarea name="catatan_komando" rows="2" placeholder="Alasan penyesuaian atau instruksi pengiriman..." 
                          class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>

            <!-- Footer Buttons -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                <button type="button" onclick="closeModalApprove()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">
                    Setujui & Eksekusi Logistik
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi Toggle Tampilkan Semua / Sembunyikan Item Ringkasan
    function toggleMoreItems(id, btn) {
        const target = document.getElementById(`more-items-${id}`);
        if (target.classList.contains('hidden')) {
            target.classList.remove('hidden');
            btn.innerHTML = `<span>Sembunyikan</span> <span>▲</span>`;
        } else {
            target.classList.add('hidden');
            const totalRemaining = target.children.length;
            btn.innerHTML = `<span>+${totalRemaining} item lainnya</span> <span>▼</span>`;
        }
    }

    const fieldMapping = {
        'beras_kg': { label: 'Beras', unit: 'kg' },
        'air_minum_dus': { label: 'Air Minum', unit: 'dus' },
        'makanan_kaleng_pack': { label: 'Makanan Kaleng', unit: 'pack' },
        'makanan_bayi_pack': { label: 'Makanan Bayi', unit: 'pack' },
        'minyak_goreng_liter': { label: 'Minyak Goreng', unit: 'liter' },
        'popok_bayi_pcs': { label: 'Popok Bayi', unit: 'pcs' },
        'popok_dewasa_pcs': { label: 'Popok Dewasa', unit: 'pcs' },
        'pembalut_wanita_pack': { label: 'Pembalut Wanita', unit: 'pack' },
        'hygiene_kit_paket': { label: 'Hygiene Kit', unit: 'paket' },
        'selimut_pcs': { label: 'Selimut', unit: 'pcs' },
        'matras_terpal_pcs': { label: 'Matras Terpal', unit: 'pcs' },
        'obat_p3k_paket': { label: 'Obat P3K', unit: 'paket' }
    };

    function openModalApprove(item) {
        document.getElementById('modal_kode_pengajuan').innerText = item.kode_pengajuan;
        document.getElementById('formApprove').action = `/komando/validasi/${item.id}/approve`;

        const tbody = document.getElementById('modal_items_body');
        tbody.innerHTML = '';

        let hasItems = false;

        for (const [field, meta] of Object.entries(fieldMapping)) {
            const qtyMinta = parseFloat(item[field] || 0);

            if (qtyMinta > 0) {
                hasItems = true;
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-slate-50';
                tr.innerHTML = `
                    <td class="py-3 px-3 font-semibold text-slate-800">
                        ${meta.label}
                    </td>
                    <td class="py-3 px-3 text-center text-slate-600 font-medium">
                        ${qtyMinta} ${meta.unit}
                    </td>
                    <td class="py-3 px-3 text-center">
                        <input type="number" step="any" min="0" name="items[${meta.label}]" value="${qtyMinta}" 
                               class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-xs font-bold text-center focus:ring-2 focus:ring-blue-500 outline-none">
                    </td>
                `;
                tbody.appendChild(tr);
            }
        }

        if (!hasItems) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-4 text-slate-400">Tidak ada item dalam pengajuan ini.</td></tr>`;
        }

        document.getElementById('modalApprove').classList.remove('hidden');
    }

    function closeModalApprove() {
        document.getElementById('modalApprove').classList.add('hidden');
    }
</script>