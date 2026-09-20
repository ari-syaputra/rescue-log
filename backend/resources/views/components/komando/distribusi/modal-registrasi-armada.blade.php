<!-- MODAL POPUP REGISTRASI ARMADA BARU -->
<div id="armadaModal" onclick="if(event.target === this) closeArmadaModal()"
    class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 transition-opacity">

    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-200 flex flex-col">

        <!-- HEADER MODAL (Warna Biru) -->
        <div class="bg-blue-700 text-white px-6 py-5 flex justify-between items-center border-b border-blue-700">
            <h3 class="font-bold text-base sm:text-lg flex items-center gap-3">
                <!-- Heroicon Blade Component: Truck (Solid) -->
                <x-heroicon-s-truck class="w-6 h-6 text-blue-100 shrink-0" />
                <span>Registrasi Unit Armada</span>
            </h3>

            <!-- TOMBOL CLOSE HEROICON (X) -->
            <button type="button" onclick="closeArmadaModal()"
                class="text-blue-100 hover:text-white hover:bg-blue-700/60 p-1.5 rounded-lg transition cursor-pointer flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- FORM BODY -->
        <form action="{{ route('komando.distribusi.armada.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Unit Armada <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_armada" required
                        placeholder="Cth: Ambulans Gawat Darurat 01"
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Jenis Kendaraan <span class="text-rose-500">*</span>
                    </label>
                    <select name="jenis_armada" required
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium outline-none transition-all">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Truk Pangan / Logistik">Truk Pangan / Logistik</option>
                        <option value="Ambulans Medis">Ambulans Medis</option>
                        <option value="Mobil Operasional / Pick Up">Mobil Operasional / Pick Up</option>
                        <option value="Motor Trail TRC">Motor Trail TRC</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Plat Nomor <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="plat_nomor" required placeholder="Cth: AB 1234 XY"
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium uppercase outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        No. HP / WA Driver
                    </label>
                    <input type="text" name="kontak_driver" placeholder="08xxxxxxxxxx"
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Pengemudi / Driver <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="nama_driver" required placeholder="Cth: Bpk. Slamet"
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Status Operational <span class="text-rose-500">*</span>
                    </label>
                    <select name="status" required
                        class="w-full text-sm border border-slate-300 rounded-xl p-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 font-medium outline-none transition-all">
                        <option value="tersedia" selected>Tersedia (Siaga)</option>
                        <option value="siap">Siap Kirim</option>
                        <option value="dalam_perjalanan">Dalam Perjalanan</option>
                        <option value="pemeliharaan">Pemeliharaan / Rusak</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 mt-2">
                <button type="button" onclick="closeArmadaModal()"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>

                <!-- TOMBOL SIMPAN (Warna Biru) -->
                <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition cursor-pointer">
                    Simpan Armada
                </button>
            </div>
        </form>
    </div>
</div>