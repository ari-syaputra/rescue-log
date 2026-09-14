@props(['stoks'])

<!-- Backdrop Overlay dengan Efek Blur -->
<div x-show="showModal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4 transition-all"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

    <!-- Container Modal Lebar (max-w-2xl) -->
    <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl transform transition-all"
        @click.away="showModal = false">

        <!-- Header Modal -->
        <div class="bg-blue-700 px-7 py-5 flex justify-between items-center text-white">
            <div class="flex items-center space-x-3.5">
                <div class="p-2.5 bg-white/20 rounded-2xl">
                    <x-heroicon-s-inbox-stack class="w-7 h-7 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white leading-tight">Catat Penyaluran Logistik</h3>
                    <p class="text-xs text-blue-100 mt-0.5">Pastikan data penyaluran dicatat sesuai kondisi real-time.
                    </p>
                </div>
            </div>
            <button @click="showModal = false"
                class="text-white/80 hover:text-white transition p-1 hover:bg-white/10 rounded-lg cursor-pointer">
                <x-heroicon-m-x-mark class="w-6 h-6" />
            </button>
        </div>

        <!-- Form Body dengan Input Lebih Besar -->
        <form action="{{ route('lapangan.penyaluran.store') }}" method="POST" class="p-8 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                    Pilih Barang Stok <span class="text-red-500">*</span>
                </label>
                <select name="stok_inventaris_id" required
                    class="w-full px-4 py-3 rounded-2xl border-gray-200 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-gray-50/50 cursor-pointer">
                    <option value="">-- Pilih Stok Tersedia --</option>
                    @foreach ($stoks as $stok)
                        <option value="{{ $stok->id }}" class="cursor-pointer">
                            {{ $stok->nama_barang }} (Sisa: {{ $stok->jumlah }} {{ $stok->satuan }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                        Jumlah Disalurkan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0.01" name="jumlah_keluar" required
                        class="w-full px-4 py-3 rounded-2xl border-gray-200 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-gray-50/50"
                        placeholder="Contoh: 10">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                        Target Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="target_penerima" required
                        class="w-full px-4 py-3 rounded-2xl border-gray-200 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-gray-50/50"
                        placeholder="Contoh: Pengungsi RT 02 / Bpk. Ahmad">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 uppercase tracking-wider mb-2">
                    Keterangan Tambahan (Opsional)
                </label>
                <textarea name="keterangan" rows="3"
                    class="w-full px-4 py-3 rounded-2xl border-gray-200 text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-gray-50/50"
                    placeholder="Catatan tambahan..."></textarea>
            </div>

            <!-- Footer Action -->
            <div class="flex justify-end items-center gap-3 pt-4 border-t border-gray-100">
                <button type="button" @click="showModal = false"
                    class="px-6 py-3 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-3 text-sm bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-2xl shadow-md shadow-blue-500/20 transition cursor-pointer">
                    Simpan Penyaluran
                </button>
            </div>
        </form>

    </div>
</div>
