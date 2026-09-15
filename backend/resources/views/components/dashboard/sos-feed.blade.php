<div class="flex flex-col justify-between space-y-4">
    <!-- SECTION EMERGENCY SOS FEED -->
    <div class="bg-white p-4 rounded-2xl border border-blue-100/80 shadow-xs space-y-3">
        <div class="flex items-center justify-between border-b border-blue-50 pb-2">
            <div class="flex items-center gap-2">
                <h4 class="text-xs font-black text-red-600 uppercase tracking-wide">EMERGENCY FEED - SOS MEDIS</h4>
                <span class="w-5 h-5 rounded-full bg-red-600 text-white font-bold text-[10px] flex items-center justify-center">
                    {{ isset($requests) ? $requests->whereIn('status', ['menunggu_penanganan', 'ambulans_meluncur', 'proses_evakuasi'])->count() : 0 }}
                </span>
            </div>
        </div>

        {{-- Gunakan $requests ?? collect() agar tidak error jika variabel tidak ada --}}
        @forelse(($requests ?? collect())->take(5) as $req)
            <div class="p-3.5 {{ $req->status === 'menunggu_penanganan' ? 'bg-red-50/60 border-red-200/80' : 'bg-blue-50/50 border-blue-100' }} border rounded-xl space-y-2.5">
                <div class="flex justify-between items-start">
                    <div>
                        @if($req->status === 'menunggu_penanganan')
                            <span class="px-1.5 py-0.5 bg-red-600 text-white font-black text-[9px] rounded-md uppercase">BARU</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-blue-700 text-white font-black text-[9px] rounded-md uppercase">
                                {{ str_replace('_', ' ', $req->status) }}
                            </span>
                        @endif
                        <h5 class="text-xs font-bold text-blue-900 mt-1">
                            {{ $req->nama_pasien ?? 'Pasien Darurat' }} 
                            @if(!empty($req->usia))
                                <span class="font-normal text-blue-600/70">({{ $req->jenis_kelamin ?? '-' }}, {{ $req->usia }} Th)</span>
                            @endif
                        </h5>
                        <p class="text-[11px] text-blue-700/70">{{ $req->lokasi_detail ?? ($req->posko->nama_posko ?? 'Lokasi tidak spesifik') }}</p>
                    </div>
                    <span class="text-[10px] text-blue-500 font-semibold">
                        {{ $req->created_at ? $req->created_at->format('H:i') . ' WIB' : '-' }}
                    </span>
                </div>

                <div class="flex items-center justify-between text-[11px]">
                    <span class="font-bold text-red-600">Kategori: {{ $req->kategori_darurat ?? $req->kategori ?? 'Medis' }}</span>
                    @if(!empty($req->jarak_km))
                        <span class="text-blue-700 font-medium">Jarak: {{ $req->jarak_km }} km</span>
                    @endif
                </div>
                
                {{-- Tombol Aksi Dispatch --}}
                <a href="{{ Route::has('komando.sos.index') ? route('komando.sos.index') : '#' }}" 
                   class="w-full h-9 inline-flex items-center justify-center px-4 py-2 {{ $req->status === 'menunggu_penanganan' ? 'bg-red-600 hover:bg-red-700 focus:ring-red-200' : 'bg-blue-700 hover:bg-blue-800 focus:ring-blue-200' }} active:bg-blue-900 text-white text-xs font-bold rounded-lg shadow-sm hover:shadow transition-all duration-150 gap-2 cursor-pointer focus:ring-4">
                    <x-fas-truck-medical class="w-3.5 h-3.5" />
                    <span>Dispatch Ambulans</span>
                </a>
            </div>
        @empty
            <!-- Tampilan Jika Tidak Ada Permintaan SOS atau Variabel Kosong -->
            <div class="p-4 text-center bg-blue-50/30 rounded-xl border border-dashed border-blue-200">
                <p class="text-xs text-blue-600/70 font-medium">Sistem kondusif. Tidak ada panggilan SOS medis aktif.</p>
            </div>
        @endforelse
    </div>

<!-- SECTION PERMINTAAN LOGISTIK MASUK -->
<div class="bg-white p-4 rounded-2xl border border-blue-100/80 shadow-xs space-y-3">
    <div class="flex items-center justify-between border-b border-blue-50 pb-2">
        <h4 class="text-xs font-black text-blue-900 uppercase tracking-wide">PERMINTAAN LOGISTIK MASUK</h4>
        <span class="w-5 h-5 rounded-full bg-orange-500 text-white font-bold text-[10px] flex items-center justify-center">
            {{ isset($permintaanLogistik) ? $permintaanLogistik->where('status', 'menunggu')->count() : 0 }}
        </span>
    </div>

    <div class="space-y-2.5">
        @forelse(($permintaanLogistik ?? collect())->take(5) as $item)
            <div class="p-3 bg-blue-50/40 rounded-xl border border-blue-100 space-y-2 text-[11px]">
                <div class="flex justify-between items-center font-bold">
                    <span class="text-blue-900">{{ $item->posko->nama_posko ?? 'Sub-Posko' }}</span>
                    <span class="text-amber-700 bg-amber-100/80 px-2 py-0.5 rounded-md border border-amber-200 text-[10px]">
                        {{ str_replace('_', ' ', ucfirst($item->status)) }}
                    </span>
                </div>

                {{-- Menampilkan 2 Ringkasan Barang Pertama dari Mapping Model --}}
                <p class="text-blue-700/80 font-medium">
                    Kode: <span class="font-bold text-blue-900">{{ $item->kode_pengajuan }}</span> 
                    @if($item->beras_kg > 0) | Beras: {{ $item->beras_kg }} Kg @endif
                    @if($item->air_minum_dus > 0) | Air: {{ $item->air_minum_dus }} Dus @endif
                    @if($item->makanan_kaleng_pack > 0) | Makanan: {{ $item->makanan_kaleng_pack }} Pack @endif
                </p>
                
                <div class="flex items-center justify-between pt-2 border-t border-blue-100 gap-2">
                    <span class="font-bold text-emerald-700">
                        Total Items: {{ $item->total_kebutuhan ?? 'Beberapa Barang' }}
                    </span>
                    
                    {{-- Navigasi Menggunakan Route Name Berdasarkan web.php --}}
                    <a href="{{ Route::has('komando.validasi.index') ? route('komando.validasi.index') : '#' }}" 
                       class="h-7 inline-flex items-center justify-center px-3 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 text-white text-[11px] font-bold rounded-md shadow-xs transition duration-150 cursor-pointer focus:ring-2 focus:ring-blue-200">
                        <span>Review & Validasi</span>
                        <x-heroicon-o-chevron-right class="w-3 h-3 ml-1 stroke-[2.5]" />
                    </a>
                </div>
            </div>
        @empty
            <div class="p-4 text-center bg-blue-50/30 rounded-xl border border-dashed border-blue-200">
                <p class="text-xs text-blue-600/70 font-medium">Tidak ada permintaan logistik masuk dari Sub-Posko.</p>
            </div>
        @endforelse
    </div>
</div>
</div>