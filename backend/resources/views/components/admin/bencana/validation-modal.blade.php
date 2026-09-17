<!-- MODAL POPUP VALIDASI & KAJI CEPAT TRC -->
<div id="modalValidasi" onclick="if(event.target === this) closeModal()"
    class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6 transition-opacity">

    <!-- Diperbesar dari max-w-2xl menjadi max-w-3xl -->
    <div
        class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl overflow-hidden border border-slate-100 animate-in fade-in zoom-in duration-200 flex flex-col">

        <!-- HEADER MODAL (Warna Orange / Amber) -->
        <div class="bg-amber-600 text-white px-6 py-5 flex justify-between items-center border-b border-amber-600">
            <h3 class="font-bold text-lg sm:text-xl flex items-center gap-3">
                <x-heroicon-s-exclamation-triangle class="w-7 h-7 text-amber-100 shrink-0" />
                <span>Validasi & Kaji Cepat TRC</span>
            </h3>

            <!-- TOMBOL CLOSE MENGGUNAKAN HEROICON -->
            <button type="button" onclick="closeModal()"
                class="text-amber-100 hover:text-white hover:bg-amber-700/60 p-1.5 rounded-lg transition cursor-pointer flex items-center justify-center">
                <x-heroicon-o-x-mark class="w-6 h-6" />
            </button>
        </div>

        <!-- FORM BODY -->
        <form id="formValidasi" action="" method="POST" enctype="multipart/form-data"
            class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- Card Informasi Deteksi Insiden (Teks diperbesar sedikit & layout disesuaikan) -->
            <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-5 text-sm text-slate-700 space-y-3">
                <div class="flex items-center justify-between border-b border-slate-200 pb-2.5">
                    <span class="font-bold text-slate-900 uppercase tracking-wider text-xs flex items-center gap-2">
                        <x-heroicon-s-map-pin class="w-5 h-5 text-amber-600" />
                        Informasi Insiden Bencana
                    </span>
                    <span id="valJenisBadge"
                        class="px-3 py-1 font-bold text-xs bg-amber-100 text-amber-800 rounded-md uppercase tracking-wide">Bencana</span>
                </div>

                <div class="grid grid-cols-3 gap-2 pt-1">
                    <span class="text-slate-500 font-medium">Jenis Bencana:</span>
                    <span id="valJenis" class="col-span-2 font-bold text-slate-800">-</span>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <span class="text-slate-500 font-medium">Wilayah / Lokasi:</span>
                    <span id="valWilayah" class="col-span-2 font-semibold text-slate-800">-</span>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <span class="text-slate-500 font-medium">Koordinat (Lat, Lng):</span>
                    <span class="col-span-2 font-mono text-slate-700"><span id="valLat">-</span>, <span
                            id="valLng">-</span></span>
                </div>

                <div class="grid grid-cols-3 gap-2">
                    <span class="text-slate-500 font-medium">Waktu Insiden:</span>
                    <span id="valWaktu" class="col-span-2 font-medium text-slate-800">-</span>
                </div>
            </div>

            <!-- Input Estimasi Pengungsi Awal -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Estimasi Pengungsi Awal (Kaji Cepat TRC) <span class="text-rose-500">*</span>
                </label>
                <input type="number" id="input_estimasi_pengungsi" name="estimasi_pengungsi_awal" min="1"
                    required placeholder="Contoh: 300"
                    class="w-full text-sm border border-slate-300 rounded-xl p-3.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-600 font-medium outline-none transition-all">
                <p class="text-xs text-slate-500 mt-1.5">Digunakan sebagai variabel acuan kuota rekomendasi logistik
                    awal.</p>
            </div>

            <!-- Input SK Status Darurat -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Dokumen SK Status Darurat (PDF/Gambar) <span class="text-rose-500">*</span>
                </label>
                <input type="file" id="input_sk_darurat" name="sk_status_darurat" accept=".pdf,.jpg,.jpeg,.png"
                    required
                    class="w-full text-xs border border-slate-300 rounded-xl p-2.5 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 transition-all cursor-pointer">
            </div>

            <!-- Info Warning Alert -->
            <div class="bg-amber-50/80 border border-amber-200/80 p-4 rounded-xl flex items-start gap-3">
                <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" />
                <p class="text-xs text-amber-900 leading-relaxed">
                    Setelah menekan <strong>Lanjut Setup Posko</strong>, data bencana akan divalidasi dan Anda akan
                    diarahkan untuk memplot Posko Komando Utama.
                </p>
            </div>

            <!-- TOMBOL AKSI FORM -->
            <div class="flex justify-end gap-3 pt-5 border-t border-slate-100 mt-3">
                <button type="button" onclick="closeModal()"
                    class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition cursor-pointer">
                    Batal
                </button>

                <!-- TOMBOL SIMPAN / LANJUT (Menggunakan Heroicon Panah Kanan) -->
                <button type="submit" id="btnSubmitValidasi"
                    class="px-6 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-sm font-bold rounded-xl shadow-sm transition flex items-center gap-2 cursor-pointer">
                    <x-heroicon-s-clipboard-document-check class="w-5 h-5 text-amber-100" />
                    <span>Lanjut Setup Posko</span>
                    <x-heroicon-m-arrow-right class="w-4 h-4 text-amber-100" />
                </button>
            </div>
        </form>
    </div>
</div>
