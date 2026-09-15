<!-- MODAL OVERLAY: Klik area luar untuk menutup modal -->
<div id="kendalaModal"
    onclick="if(event.target === this) closeKendalaModal()"
    class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center z-50 hidden transition-opacity overflow-y-auto py-6">
    
    <!-- MODAL CARD CONTAINER -->
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl overflow-hidden max-h-[90vh] flex flex-col m-4">

        <!-- HEADER MODAL (WARNA BIRU 700) -->
        <div class="bg-blue-700 p-4 flex items-center justify-between border-b border-blue-800 shrink-0">
            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                <!-- Heroicon: exclamation-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-amber-300 shrink-0">
                    <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                </svg>
                <span>Laporkan Kendala Jalan Baru</span>
            </h3>
            
            <!-- TOMBOL SILANG (X) KLIK KUTIF HEROICON -->
            <button type="button" 
                onclick="closeKendalaModal()" 
                class="text-blue-100 hover:text-white hover:bg-blue-600/80 p-1.5 rounded-lg transition cursor-pointer flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- FORM BODY (SCROLLABLE) -->
        <form method="POST" action="{{ route('komando.distribusi.kendala.store') }}" class="p-6 space-y-4 overflow-y-auto">
            @csrf

            <!-- NAMA LOKASI & JENIS KENDALA -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Lokasi / Jalan</label>
                    <input type="text" id="input_nama_lokasi" name="nama_lokasi" required
                        placeholder="Contoh: Jembatan Sungai A, KM 14"
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Jenis Kendala</label>
                    <select name="jenis_kendala" required
                        class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all">
                        <option value="longsor">Tanah Longsor</option>
                        <option value="jembatan_putus">Jembatan Putus</option>
                        <option value="banjir">Banjir / Genangan</option>
                        <option value="pohon_tumbang">Pohon Tumbang</option>
                        <option value="jalan_rusak">Jalan Rusak Parah</option>
                    </select>
                </div>
            </div>

            <!-- PETA PEMILIHAN LOKASI (INTERAKTIF) -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pilih Titik di Peta <span class="text-rose-500">*</span>
                    </label>
                    <button type="button" onclick="getCurrentGPSLocation()"
                        class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-600 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-2.5 py-1 rounded-lg transition border border-blue-200/60 cursor-pointer">
                        <!-- Heroicon: crosshair / location -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span>Gunakan Lokasi Saya</span>
                    </button>
                </div>

                <!-- Container Mini Map -->
                <div id="modalMap"
                    class="w-full h-60 rounded-xl border border-slate-200 overflow-hidden shadow-inner bg-slate-100">
                </div>
                <p class="text-[11px] text-slate-400 mt-1">Klik atau geser pin merah pada peta di atas untuk menentukan koordinat presisi.</p>
            </div>

            <!-- KOORDINAT (READONLY AUTOMATIC) -->
            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Latitude</label>
                    <input type="number" step="any" id="input_latitude" name="latitude" required readonly
                        placeholder="-7.797068"
                        class="w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg font-mono text-slate-600 focus:outline-none">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Longitude</label>
                    <input type="number" step="any" id="input_longitude" name="longitude" required readonly
                        placeholder="110.370529"
                        class="w-full px-3 py-1.5 text-xs bg-white border border-slate-200 rounded-lg font-mono text-slate-600 focus:outline-none">
                </div>
            </div>

            <!-- DESKRIPSI -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Keparahan</label>
                <textarea name="deskripsi" rows="2" placeholder="Jelaskan kondisi tingkat keparahan kendala jalan..."
                    class="w-full px-3.5 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-500 focus:bg-white transition-all"></textarea>
            </div>

            <!-- FOOTER ACTION -->
            <div class="pt-3 flex items-center justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="closeKendalaModal()"
                    class="px-4 py-2 text-sm font-semibold text-slate-700 bg-slate-200 hover:bg-slate-300 border border-slate-300 rounded-xl transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition shadow-md shadow-blue-600/20 cursor-pointer">
                    Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>