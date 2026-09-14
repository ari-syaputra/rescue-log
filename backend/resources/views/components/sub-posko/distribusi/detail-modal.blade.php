<!-- MODAL DETAIL RINCIAN LOGISTIK -->
<div id="detailLogistikModal"
    class="fixed inset-0 z-40 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 space-y-4">

        <!-- Header Modal -->
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-gray-900">Rincian Barang Logistik</h3>
                <p id="modalKodePengajuan" class="text-xs text-blue-600 font-semibold mt-0.5">#REQ-000</p>
            </div>
            <button type="button" onclick="closeDetailModal()"
                class="text-gray-400 hover:text-gray-600 font-bold text-xl leading-none">&times;</button>
        </div>

        <!-- Catatan dari Posko Komando (Jika Ada) -->
        <div id="modalCatatanContainer"
            class="hidden p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 font-medium">
            <span class="font-bold">Catatan Komando:</span> <span id="modalCatatanText">-</span>
        </div>

        <!-- Tabel Rincian Item Barang -->
        <div class="max-h-60 overflow-y-auto border border-gray-100 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 font-bold uppercase sticky top-0">
                    <tr>
                        <th class="py-2.5 px-3">Nama Barang</th>
                        <th class="py-2.5 px-3">Kategori</th>
                        <th class="py-2.5 px-3 text-right">Jumlah / Kuantitas</th>
                    </tr>
                </thead>
                <tbody id="modalTableBody" class="divide-y divide-gray-100 text-gray-700 font-medium">
                    <!-- Diisi via JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Action Footer -->
        <div class="pt-3 border-t border-gray-100 flex items-center justify-between gap-3">
            <button type="button" onclick="closeDetailModal()"
                class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-700 text-xs font-bold transition cursor-pointer">
                Tutup
            </button>
            <button id="btnOpenConfirmModal" type="button" onclick="showConfirmModal()"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-sm flex items-center gap-2 cursor-pointer">
                <x-heroicon-o-check class="w-4 h-4 shrink-0 stroke-[2.5]" />
                <span>Konfirmasi Diterima</span>
            </button>
        </div>

    </div>
</div>
