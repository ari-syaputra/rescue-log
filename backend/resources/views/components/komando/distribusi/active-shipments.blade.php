@props(['pengirimans', 'pengajuanSiapKirim' => [], 'armadas' => []])

<div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs h-full flex flex-col justify-between space-y-5">

    <div class="flex-1 flex flex-col min-h-0">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100 shrink-0">
            <h3 class="text-base font-bold text-slate-900">
                Siap Dikirim
            </h3>
            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200/60">
                {{ count($pengajuanSiapKirim) }} Paket
            </span>
        </div>

        <div class="space-y-3 overflow-y-auto pr-1 flex-1 min-h-[100px]">
            @forelse($pengajuanSiapKirim as $item)
                <div class="p-3.5 bg-white border border-blue-200 rounded-2xl space-y-2.5 relative shadow-xs">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[11px] font-bold font-mono text-blue-600 bg-blue-50 px-2 py-0.5 rounded border border-blue-100/80 inline-block">
                                {{ $item->kode_pengajuan }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-xs mt-1.5">
                                Tujuan: {{ $item->posko->nama_posko ?? ($item->user->name ?? 'Posko Utama') }}
                            </h4>
                        </div>
                        <span class="text-[11px] font-semibold px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded cursor-pointer">
                            Disetujui
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-1.5 text-[11px] font-medium text-slate-600">
                        @if ($item->beras_kg > 0)
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-md">Beras: {{ $item->beras_kg }}kg</span>
                        @endif
                        @if ($item->air_minum_dus > 0)
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-md">Air: {{ $item->air_minum_dus }}dus</span>
                        @endif
                        @if ($item->makanan_kaleng_pack > 0)
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-md">Mak Kaleng: {{ $item->makanan_kaleng_pack }}pk</span>
                        @endif
                        @if ($item->obat_p3k_paket > 0)
                            <span class="bg-slate-100 text-slate-600 px-2.5 py-0.5 rounded-md">P3K: {{ $item->obat_p3k_paket }}pkt</span>
                        @endif
                    </div>

                    <form action="{{ route('komando.distribusi.store') }}" method="POST" class="flex items-center gap-2 pt-1 w-full">
                        @csrf
                        <input type="hidden" name="pengajuan_id" value="{{ $item->id }}">

                        <div class="relative flex-1 min-w-0">
                            <select name="armada_id" required class="w-full bg-white border border-slate-300 text-slate-700 text-xs rounded-xl px-2.5 py-1.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all truncate">
                                <option value="">-- Pilih Armada Siaga --</option>
                                @foreach ($armadas as $armada)
                                    <option value="{{ $armada->id }}">
                                        {{ $armada->nama_armada }} ({{ $armada->plat_nomor }}) - {{ $armada->nama_driver }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition shadow-xs shrink-0 cursor-pointer">
                            Kirim
                        </button>
                    </form>
                </div>
            @empty
                <div class="h-full flex items-center justify-center p-4 text-center text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <p class="text-xs font-medium italic">Belum ada pengajuan baru yang siap dikirim.</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="flex-1 flex flex-col min-h-0">
        <div class="flex items-center justify-between mb-3 pb-2 border-b border-slate-100 shrink-0">
            <h3 class="text-base font-bold text-slate-900">
                Dalam Perjalanan
            </h3>
            <span class="text-xs font-bold bg-blue-50 text-blue-600 px-3 py-1 rounded-md border border-blue-100">
                Live
            </span>
        </div>

        <div class="flex-1 flex flex-col min-h-0 overflow-y-auto pr-0.5 space-y-3">
            @php
                $activeShipments = $pengirimans->filter(function($p) {
                    $st = strtolower($p->status_pengiriman ?? $p->status_distribusi ?? '');
                    return in_array($st, ['dalam_perjalanan', 'dalam perjalanan', 'dalam pengiriman', 'proses_dikirim']);
                });
            @endphp

            @forelse($activeShipments as $shipment)
                <div class="p-4 rounded-2xl border border-slate-200/90 bg-white shadow-xs flex flex-col justify-between shrink-0">
                    
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-xs font-bold font-mono text-blue-600 bg-blue-50 px-2.5 py-0.5 rounded-md border border-blue-100/80 inline-block">
                                #{{ $shipment->pengajuan->kode_pengajuan ?? ('REQ-PENGIRIMAN-' . $shipment->id) }}
                            </span>
                            <h4 class="font-bold text-slate-900 text-sm mt-1.5">
                                Tujuan: {{ $shipment->posko->nama_posko ?? ($shipment->pengajuan->posko->nama_posko ?? 'Sub-Posko Lapangan') }}
                            </h4>
                        </div>
                        <span class="text-[11px] font-bold bg-amber-50 text-amber-600 px-2.5 py-0.5 rounded-md border border-amber-200/50 flex items-center gap-1 shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            On Delivery
                        </span>
                    </div>

                    <div class="bg-slate-50/80 p-3 rounded-xl border border-slate-100 my-2 space-y-2">
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">ARMADA</p>
                                <p class="font-bold text-slate-800 text-[11px] mt-0.5 truncate">
                                    {{ $shipment->armada->nama_armada ?? 'Truk Logistik A1' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">DRIVER / NOPOL</p>
                                <p class="font-bold text-slate-800 text-[11px] mt-0.5 truncate">
                                    {{ $shipment->armada->plat_nomor ?? '-' }} ({{ $shipment->armada->nama_driver ?? '-' }})
                                </p>
                            </div>
                        </div>

                        <div class="pt-1.5 border-t border-slate-200/60 flex items-center justify-between text-[10px] text-slate-500">
                            <span>Jalur: <strong class="text-emerald-600 font-semibold">Lancar</strong></span>
                            <span>Dikirim: {{ $shipment->created_at ? $shipment->created_at->format('H:i') . ' WIB' : 'Baru saja' }}</span>
                        </div>
                    </div>

                    @php
                        $latAsal = $shipment->lat_asal ?? -7.8893;
                        $longAsal = $shipment->long_asal ?? 110.3288;
                        $latTujuan = $shipment->lat_tujuan ?? ($shipment->posko->latitude ?? ($shipment->pengajuan->posko->latitude ?? -7.8000));
                        $longTujuan = $shipment->long_tujuan ?? ($shipment->posko->longitude ?? ($shipment->pengajuan->posko->longitude ?? 110.3800));
                        $namaPosko = $shipment->posko->nama_posko ?? ($shipment->pengajuan->posko->nama_posko ?? 'Sub-Posko Tujuan');
                    @endphp

                    <button type="button"
                        onclick="drawDeliveryRoute({{ $latAsal }}, {{ $longAsal }}, {{ $latTujuan }}, {{ $longTujuan }}, '{{ addslashes($namaPosko) }}')"
                        class="w-full py-2 px-3 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 active:bg-blue-800 rounded-xl transition-all text-center cursor-pointer shadow-xs flex items-center justify-center gap-1.5 shrink-0">
                        <span>Tampilkan Rute di Peta</span>
                    </button>
                </div>
            @empty
                <div class="h-full flex items-center justify-center p-4 text-center text-slate-400 bg-white rounded-2xl border border-dashed border-slate-200">
                    <p class="text-xs font-medium text-slate-400">Belum ada pengiriman logistik dalam perjalanan.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>