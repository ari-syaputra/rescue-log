@props(['pengirimans', 'pengajuanSiapKirim' => [], 'armadas' => []])

<div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
    
    <!-- 1. BAGIAN: PENGAJUAN SIAP DIKIRIM (MENUNGGU ARMADA) -->
    <div>
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="package-check" class="w-4 h-4 text-emerald-600"></i> Siap Dikirim (Menunggu Armada)
            </h3>
            <span class="text-[11px] font-extrabold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded-full border border-emerald-200">
                {{ count($pengajuanSiapKirim) }} Paket
            </span>
        </div>

        <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
            @forelse($pengajuanSiapKirim as $item)
                <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl space-y-2.5 hover:border-blue-300 transition-all">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                {{ $item->kode_pengajuan }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-xs mt-1">
                                Tujuan: {{ $item->posko->nama_posko ?? $item->user->name ?? 'Sub-Posko Lapangan' }}
                            </h4>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded">
                            Disetujui
                        </span>
                    </div>

                    <!-- RINGKASAN ITEM BARANG -->
                    <div class="flex flex-wrap gap-1 text-[10px] text-slate-600 bg-white p-2 rounded-lg border border-slate-100">
                        @if($item->beras_kg > 0) <span class="bg-slate-100 px-1.5 py-0.5 rounded">Beras: {{ $item->beras_kg }}kg</span> @endif
                        @if($item->air_minum_dus > 0) <span class="bg-slate-100 px-1.5 py-0.5 rounded">Air: {{ $item->air_minum_dus }}dus</span> @endif
                        @if($item->makanan_kaleng_pack > 0) <span class="bg-slate-100 px-1.5 py-0.5 rounded">Mak Kaleng: {{ $item->makanan_kaleng_pack }}pk</span> @endif
                        @if($item->obat_p3k_paket > 0) <span class="bg-slate-100 px-1.5 py-0.5 rounded">P3K: {{ $item->obat_p3k_paket }}pkt</span> @endif
                    </div>

                    <!-- FORM TUGASKAN ARMADA -->
                    <form action="{{ route('komando.distribusi.store') }}" method="POST" class="flex items-center gap-2 pt-1">
                        @csrf
                        <input type="hidden" name="pengajuan_id" value="{{ $item->id }}">
                        
                        <select name="armada_id" required class="flex-1 bg-white border border-slate-300 text-slate-800 text-xs rounded-lg px-2.5 py-1.5 focus:ring-blue-500 font-medium">
                            <option value="">-- Pilih Armada Siaga --</option>
                            @foreach($armadas as $armada)
                                <option value="{{ $armada->id }}">
                                    {{ $armada->nama_armada }} ({{ $armada->plat_nomor }}) - {{ $armada->nama_driver }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg transition shadow-xs flex items-center gap-1 shrink-0">
                            <i data-lucide="send" class="w-3.5 h-3.5"></i> Kirim
                        </button>
                    </form>
                </div>
            @empty
                <div class="py-6 text-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <p class="text-xs italic">Belum ada pengajuan baru yang siap dikirim.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- 2. BAGIAN: PENGIRIMAN AKTIF (DALAM PERJALANAN) -->
    <div>
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="navigation-2" class="w-4 h-4 text-blue-600"></i> Dalam Perjalanan (Live Tracking)
            </h3>
            <span class="text-xs bg-slate-100 text-slate-600 font-bold px-2 py-0.5 rounded-md">Live</span>
        </div>

        <div class="space-y-3 max-h-[350px] overflow-y-auto pr-1">
            @forelse($pengirimans->filter(fn($p) => in_array(strtolower($p->status_distribusi ?? ''), ['dalam perjalanan', 'dalam_perjalanan', 'dalam pengiriman'])) as $shipment)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:border-blue-200 hover:shadow-sm transition-all space-y-3">
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[11px] font-bold font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100">
                                #{{ $shipment->pengajuan->kode_pengajuan ?? 'SHIP-'.$shipment->id }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-sm mt-1">
                                Tujuan: {{ $shipment->posko->nama_posko ?? $shipment->pengajuan->posko->nama_posko ?? 'Posko Lapangan' }}
                            </h4>
                        </div>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> On Delivery
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-xs text-slate-600 bg-white p-2.5 rounded-lg border border-slate-100">
                        <div>
                            <p class="text-[10px] text-slate-400 font-semibold uppercase">Armada</p>
                            <p class="font-bold text-slate-800 flex items-center gap-1 mt-0.5">
                                <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-500"></i>
                                {{ $shipment->armada->nama_armada ?? 'Truk Box BPBD' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-semibold uppercase">Driver / No.Pol</p>
                            <p class="font-bold text-slate-800 mt-0.5">
                                {{ $shipment->armada->plat_nomor ?? 'AB 8012 YX' }}
                            </p>
                        </div>
                    </div>

                    @php
                        $latAsal = $shipment->lat_asal ?? -7.7956;
                        $longAsal = $shipment->long_asal ?? 110.3695;
                        $latTujuan = $shipment->lat_tujuan ?? $shipment->posko->latitude ?? $shipment->pengajuan->posko->latitude ?? -7.7970;
                        $longTujuan = $shipment->long_tujuan ?? $shipment->posko->longitude ?? $shipment->pengajuan->posko->longitude ?? 110.3700;
                        $namaPosko = $shipment->posko->nama_posko ?? $shipment->pengajuan->posko->nama_posko ?? 'Posko Tujuan';
                    @endphp

                    <button type="button"
                            onclick="drawDeliveryRoute({{ $latAsal }}, {{ $longAsal }}, {{ $latTujuan }}, {{ $longTujuan }}, '{{ addslashes($namaPosko) }}')"
                            class="w-full flex items-center justify-center gap-1.5 text-xs font-semibold bg-blue-50 text-blue-700 hover:bg-blue-600 hover:text-white py-2 px-3 rounded-lg border border-blue-200 transition-colors">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5"></i>
                        Tampilkan Rute di Peta
                    </button>
                </div>
            @empty
                <div class="py-8 text-center text-slate-400 bg-slate-50/50 rounded-xl border border-dashed border-slate-200">
                    <i data-lucide="truck" class="w-8 h-8 text-slate-300 mx-auto mb-1"></i>
                    <p class="text-xs font-semibold">Belum ada pengiriman logistik dalam perjalanan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>