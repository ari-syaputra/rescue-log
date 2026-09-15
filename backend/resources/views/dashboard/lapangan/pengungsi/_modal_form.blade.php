<!-- MODAL OVERLAY -->
<div id="modal-pendataan" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- Latar Belakang Blur -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0" id="modal-backdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            
            <!-- PANEL MODAL UTAMA -->
            <div id="modal-panel" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 border border-slate-100">
                
                <!-- HEADER MODAL -->
                <div class="bg-blue-700 px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white/10 rounded-xl text-white backdrop-blur-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white leading-tight" id="modal-title">Form Pendataan Pengungsi</h3>
                            <p class="text-blue-100 text-xs mt-0.5">Pastikan data diisi sesuai kondisi real-time di pos lapangan.</p>
                        </div>
                    </div>
                    <button type="button" onclick="closePendataanModal()" class="text-white/80 hover:text-white transition p-1 hover:bg-white/10 rounded-lg">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- FORM BODY - ID ditambahkan 'form-pendataan-pengungsi' agar tertangkap script Offline-First -->
                <form id="form-pendataan-pengungsi" action="{{ route('lapangan.pengungsi.store') }}" method="POST">
                    @csrf
                    <div class="max-h-[75vh] overflow-y-auto p-6 space-y-6 bg-slate-50/50">
                        
                        <!-- 1. KARTU INFORMASI OTOMATIS -->
                        <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 sm:p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-xs font-bold text-blue-900 tracking-wider uppercase">INFORMASI OTOMATIS</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-200/60 text-blue-700 tracking-wider uppercase">OTOMATIS</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <!-- Tanggal Pendataan -->
                                <div class="flex items-center gap-3">
                                    <div class="p-2.5 bg-blue-100/70 text-blue-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[11px] text-slate-500 font-medium">Tanggal Pendataan</div>
                                        <div id="waktu-realtime" class="text-xs font-bold text-slate-800">-</div>
                                    </div>
                                </div>

                                <!-- Suhu Lokasi -->
                                <div class="flex items-center gap-3 md:border-l md:border-blue-100 md:pl-4">
                                    <div class="p-2.5 bg-blue-100/70 text-blue-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[11px] text-slate-500 font-medium">Suhu Lokasi</div>
                                        <div id="cuaca-suhu" class="text-xs font-bold text-slate-800">Memuat suhu...</div>
                                    </div>
                                </div>

                                <!-- Kondisi Cuaca -->
                                <div class="flex items-center gap-3 md:border-l md:border-blue-100 md:pl-4">
                                    <div class="p-2.5 bg-blue-100/70 text-blue-600 rounded-xl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/></svg>
                                    </div>
                                    <div>
                                        <div class="text-[11px] text-slate-500 font-medium">Kondisi Cuaca</div>
                                        <div id="cuaca-kondisi" class="text-xs font-bold text-slate-800">Memuat cuaca...</div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-1.5 text-[11px] text-blue-600 font-medium mt-3 pt-2 border-t border-blue-100/60">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Data diambil otomatis dari perangkat dan API cuaca.</span>
                            </div>
                        </div>

                        <!-- Input Hidden Suhu & Cuaca -->
                        <input type="hidden" name="suhu_celcius" id="input-suhu">
                        <input type="hidden" name="cuaca" id="input-cuaca">

                        <!-- 2. RINCIAN DEMOGRAFI PENGUNGSI -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <h4 class="text-sm font-bold text-slate-800">Rincian Demografi Pengungsi</h4>
                                </div>
                                <span class="text-[11px] text-slate-400 font-medium"><span class="text-rose-500">*</span> Field bertanda bintang wajib diisi</span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                
                                <!-- CARD BIRU TOTAL PENGUNGSI -->
                                <div class="bg-linear-to-br from-blue-600 to-blue-700 rounded-2xl p-5 text-white shadow-md flex flex-col justify-between relative overflow-hidden">
                                    <div class="relative z-10">
                                        <div class="text-[11px] font-bold uppercase tracking-wider text-blue-100 mb-1">TOTAL PENGUNGSI</div>
                                        <div class="flex items-baseline gap-2">
                                            <input type="number" 
                                                   name="total_pengungsi" 
                                                   id="input-total-pengungsi"
                                                   value="{{ $pendataan_terakhir->total_pengungsi ?? '0' }}" 
                                                   class="text-4xl font-black bg-transparent text-white border-none p-0 w-28 outline-none cursor-not-allowed" 
                                                   readonly>
                                            <span class="text-base font-bold text-blue-100">JIWA</span>
                                        </div>
                                    </div>
                                    <div class="relative z-10 text-[11px] text-blue-100/90 pt-3 border-t border-blue-400/40 font-medium">
                                        Balita <strong id="lbl-balita">0</strong> • Dewasa <strong id="lbl-dewasa">0</strong> • Lansia <strong id="lbl-lansia">0</strong>
                                    </div>
                                    <!-- Decorative Bg Icon -->
                                    <svg class="absolute -right-4 -bottom-4 w-28 h-28 text-white/10" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>

                                <!-- KOLOM INPUT DEMOGRAFI -->
                                <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    
                                    <!-- Balita -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Anak Balita (0-5 th) <span class="text-rose-500">*</span></label>
                                        <div class="relative flex items-center">
                                            <div class="absolute left-3 p-1.5 bg-purple-50 text-purple-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <input type="number" name="balita" id="input-balita" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->balita ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                            <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                                        </div>
                                    </div>

                                    <!-- Dewasa -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Dewasa (18-59 th) <span class="text-rose-500">*</span></label>
                                        <div class="relative flex items-center">
                                            <div class="absolute left-3 p-1.5 bg-emerald-50 text-emerald-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </div>
                                            <input type="number" name="dewasa" id="input-dewasa" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->dewasa ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                            <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                                        </div>
                                    </div>

                                    <!-- Lansia -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lansia (&ge; 60 th) <span class="text-rose-500">*</span></label>
                                        <div class="relative flex items-center">
                                            <div class="absolute left-3 p-1.5 bg-amber-50 text-amber-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                            </div>
                                            <input type="number" name="lansia" id="input-lansia" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->lansia ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                            <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                                        </div>
                                    </div>

                                    <!-- Ibu Hamil -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ibu Hamil <span class="text-rose-500">*</span></label>
                                        <div class="relative flex items-center">
                                            <div class="absolute left-3 p-1.5 bg-rose-50 text-rose-500 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                            </div>
                                            <input type="number" name="ibu_hamil" id="input-ibu-hamil" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->ibu_hamil ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                            <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                                        </div>
                                    </div>

                                    <!-- Disabilitas -->
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Disabilitas <span class="text-rose-500">*</span></label>
                                        <div class="relative flex items-center">
                                            <div class="absolute left-3 p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                                            </div>
                                            <input type="number" name="disabilitas" id="input-disabilitas" oninput="hitungTotalPengungsi()" value="{{ $pendataan_terakhir->disabilitas ?? '' }}" placeholder="0" min="0" class="w-full pl-12 pr-12 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-semibold outline-none" required>
                                            <span class="absolute right-3.5 text-xs text-slate-400 font-medium">jiwa</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- 3. KONDISI & FASILITAS TEMPAT -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-xs">
                            <div class="flex items-center gap-2 border-b border-slate-100 pb-3 mb-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V12a2 2 0 012-2h2a2 2 0 012 2v9"/></svg>
                                <h4 class="text-sm font-bold text-slate-800">Kondisi & Fasilitas Tempat</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <!-- Tipe Tempat -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tipe Tempat <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 p-1.5 bg-slate-100 text-slate-500 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </div>
                                        <select name="tipe_tempat" id="input-tipe-tempat" onchange="cekValidasiForm()" class="w-full pl-12 pr-8 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none appearance-none bg-white" required>
                                            <option value="">Pilih tipe tempat</option>
                                            @foreach(['Balai Desa', 'Masjid/Tempat Ibadah', 'Sekolah', 'Tenda/Lapangan'] as $tipe)
                                                <option value="{{ $tipe }}" {{ ($pendataan_terakhir->tipe_tempat ?? '') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="w-4 h-4 text-slate-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                                <!-- Akses Air -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akses Air <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 p-1.5 bg-blue-50 text-blue-500 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 14m-6 0a6 6 0 10-12 0 6 6 0 0012 0zM12 3v11"/></svg>
                                        </div>
                                        <select name="akses_air" id="input-akses-air" onchange="cekValidasiForm()" class="w-full pl-12 pr-8 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none appearance-none bg-white" required>
                                            <option value="">Pilih akses air</option>
                                            @foreach(['Cukup', 'Terbatas', 'Tidak Ada'] as $air)
                                                <option value="{{ $air }}" {{ ($pendataan_terakhir->akses_air ?? '') == $air ? 'selected' : '' }}>{{ $air }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="w-4 h-4 text-slate-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                                <!-- Akses Jalan -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Akses Jalan <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 p-1.5 bg-slate-100 text-slate-500 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                        </div>
                                        <select name="akses_jalan" id="input-akses-jalan" onchange="cekValidasiForm()" class="w-full pl-12 pr-8 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none appearance-none bg-white" required>
                                            <option value="">Pilih akses jalan</option>
                                            @foreach(['Mobil/Truk Bisa Masuk', 'Hanya Motor', 'Harus Jalan Kaki'] as $jalan)
                                                <option value="{{ $jalan }}" {{ ($pendataan_terakhir->akses_jalan ?? '') == $jalan ? 'selected' : '' }}>{{ $jalan }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="w-4 h-4 text-slate-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                                <!-- Lama Pengungsian -->
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lama Pengungsian <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <div class="absolute left-3 p-1.5 bg-blue-50 text-blue-600 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <select name="lama_pengungsian" id="input-lama-pengungsian" onchange="cekValidasiForm()" class="w-full pl-12 pr-8 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-medium outline-none appearance-none bg-white" required>
                                            <option value="">Pilih lama pengungsian</option>
                                            @for($i = 1; $i <= 30; $i++)
                                                <option value="{{ $i }}" {{ ($pendataan_terakhir->lama_pengungsian ?? 1) == $i ? 'selected' : '' }}>{{ $i }} Hari</option>
                                            @endfor
                                        </select>
                                        <svg class="w-4 h-4 text-slate-400 absolute right-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- FOOTER MODAL -->
                    <div class="bg-white px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 rounded-b-2xl">
                        <!-- Alert Status Kelengkapan -->
                        <div id="alert-form-status" class="flex items-center gap-2.5 text-left">
                            <div class="p-2 rounded-full bg-rose-100 text-rose-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <h5 id="alert-status-title" class="text-xs font-bold text-slate-800">5 data wajib belum lengkap</h5>
                                <p id="alert-status-sub" class="text-[11px] text-slate-400 font-medium">Lengkapi semua data untuk menyimpan.</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2.5 w-full sm:w-auto">
                            <button type="button" onclick="closePendataanModal()" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-bold hover:bg-slate-50 transition shadow-xs cursor-pointer">
                                Batal
                            </button>
                            <button type="submit" id="btn-submit-pendataan" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <span>Simpan & Hitung Logistik AI</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // 1. Hitung Total Pengungsi Otomatis
    function hitungTotalPengungsi() {
        const balita = parseInt(document.getElementById('input-balita').value) || 0;
        const dewasa = parseInt(document.getElementById('input-dewasa').value) || 0;
        const lansia = parseInt(document.getElementById('input-lansia').value) || 0;

        const total = balita + dewasa + lansia;
        document.getElementById('input-total-pengungsi').value = total;

        document.getElementById('lbl-balita').innerText = balita;
        document.getElementById('lbl-dewasa').innerText = dewasa;
        document.getElementById('lbl-lansia').innerText = lansia;

        cekValidasiForm();
    }

    // 2. Cek Validasi Form Real-time
    function cekValidasiForm() {
        const fields = [
            'input-balita', 
            'input-dewasa', 
            'input-lansia', 
            'input-ibu-hamil', 
            'input-disabilitas',
            'input-tipe-tempat',
            'input-akses-air',
            'input-akses-jalan',
            'input-lama-pengungsian'
        ];

        let sisaKosong = 0;
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el || el.value === '' || el.value === null) {
                sisaKosong++;
            }
        });

        const alertTitle = document.getElementById('alert-status-title');
        const alertSub = document.getElementById('alert-status-sub');
        const alertBox = document.getElementById('alert-form-status');

        if (sisaKosong > 0) {
            alertTitle.innerText = `${sisaKosong} data wajib belum lengkap`;
            alertTitle.className = 'text-xs font-bold text-slate-800';
            alertSub.innerText = 'Lengkapi semua data untuk menyimpan.';
            alertBox.querySelector('div').className = 'p-2 rounded-full bg-rose-100 text-rose-600';
        } else {
            alertTitle.innerText = 'Semua data wajib lengkap';
            alertTitle.className = 'text-xs font-bold text-emerald-700';
            alertSub.innerText = 'Siap dikirim ke server AI.';
            alertBox.querySelector('div').className = 'p-2 rounded-full bg-emerald-100 text-emerald-600';
        }
    }

    function openPendataanModal() {
        // Realtime Clock
        const now = new Date();
        const formatWaktu = `${String(now.getDate()).padStart(2, '0')}/${String(now.getMonth() + 1).padStart(2, '0')}/${now.getFullYear()} ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WIB`;
        document.getElementById('waktu-realtime').innerText = formatWaktu;

        hitungTotalPengungsi();

        // Coordinates
        const lat = "{{ $subPosko->latitude ?? -7.7956 }}";
        const lon = "{{ $subPosko->longitude ?? 110.3695 }}";

        // Open-Meteo Weather API
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`)
            .then(response => response.json())
            .then(data => {
                if(data && data.current_weather) {
                    const temp = data.current_weather.temperature;
                    const weatherCode = data.current_weather.weathercode;

                    let deskripsiCuaca = "Cerah / Berawan";
                    if(weatherCode >= 51 && weatherCode <= 67) deskripsiCuaca = "Hujan Ringan/Sedang";
                    if(weatherCode >= 80 && weatherCode <= 99) deskripsiCuaca = "Hujan Deras / Badai";

                    document.getElementById('cuaca-suhu').innerText = temp + " °C";
                    document.getElementById('cuaca-kondisi').innerText = deskripsiCuaca;

                    document.getElementById('input-suhu').value = temp;
                    document.getElementById('input-cuaca').value = deskripsiCuaca;
                }
            })
            .catch(error => {
                console.error('Gagal memuat data cuaca:', error);
                document.getElementById('cuaca-suhu').innerText = "28.5 °C";
                document.getElementById('cuaca-kondisi').innerText = "Berawan";
                document.getElementById('input-suhu').value = 28.5;
                document.getElementById('input-cuaca').value = "Berawan";
            });

        // Buka Modal
        const modal = document.getElementById('modal-pendataan');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            backdrop.classList.add('opacity-100');
            panel.classList.add('opacity-100', 'translate-y-0', 'scale-100');
            panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }, 10);
    }

    function closePendataanModal() {
        const modal = document.getElementById('modal-pendataan');
        const backdrop = document.getElementById('modal-backdrop');
        const panel = document.getElementById('modal-panel');
        
        backdrop.classList.remove('opacity-100');
        panel.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
        panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>