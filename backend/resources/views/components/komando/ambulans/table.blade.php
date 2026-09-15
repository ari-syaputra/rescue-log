@props(['requests'])

<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
    <h3 class="text-sm font-bold text-slate-800">Daftar Panggilan Emergency Sub-Posko</h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 text-xs font-bold uppercase bg-slate-50/80">
                    <th class="p-3">Kode SOS & Waktu</th>
                    <th class="p-3">Sub-Posko Pengaju</th>
                    <th class="p-3">Kategori & Pasien</th>
                    <th class="p-3">Kondisi Medis</th>
                    <th class="p-3">Armada & RS Rujukan</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-center">Aksi Disposisi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                @forelse($requests as $item)
                    <tr class="hover:bg-slate-50/80 transition-colors {{ $item->status == 'menunggu_penanganan' ? 'bg-rose-50/30' : '' }}">
                        <!-- KODE SOS -->
                        <td class="p-3">
                            <p class="font-mono font-bold text-slate-900">{{ $item->kode_sos }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->waktu_request->diffForHumans() }}</p>
                        </td>

                        <!-- POSKO -->
                        <td class="p-3">
                            <p class="font-bold text-slate-800">{{ $item->posko->nama_posko ?? 'Posko Lapangan' }}</p>
                            <p class="text-[10px] text-slate-500">PJ: {{ $item->posko->penanggung_jawab ?? '-' }} ({{ $item->posko->kontak_hp ?? '-' }})</p>
                        </td>

                        <!-- KATEGORI -->
                        <td class="p-3">
                            @if($item->kategori_darurat == 'kritis_nyawa')
                                <span class="px-2 py-0.5 rounded text-[10px] font-extrabold bg-rose-100 text-rose-800 animate-pulse">🔴 KRITIS NYAWA</span>
                            @elseif($item->kategori_darurat == 'berat')
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">🟡 BERAT</span>
                            @else
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">🟢 SEDANG</span>
                            @endif
                            <p class="font-semibold text-slate-900 mt-1">{{ $item->nama_pasien }}</p>
                        </td>

                        <!-- KONDISI MEDIS -->
                        <td class="p-3 max-w-xs truncate" title="{{ $item->kondisi_medis }}">
                            {{ $item->kondisi_medis }}
                        </td>

                        <!-- ARMADA & RS -->
                        <td class="p-3">
                            @if($item->armada)
                                <p class="font-bold text-slate-800 flex items-center gap-1">
                                    <x-heroicon-s-truck class="w-4 h-4 text-slate-600 shrink-0" />
                                    <span>{{ $item->armada->nama_armada }}</span>
                                </p>
                                <p class="text-[10px] text-slate-500">Driver: {{ $item->armada->pengemudi ?? '-' }} ({{ $item->armada->kontak_pengemudi ?? '-' }})</p>
                                <p class="text-[10px] text-indigo-600 font-semibold mt-0.5 flex items-center gap-1">
                                    <x-heroicon-s-building-office-2 class="w-3.5 h-3.5 text-indigo-500 shrink-0" />
                                    <span>{{ $item->rs_rujukan ?? '-' }}</span>
                                </p>
                            @else
                                <span class="text-xs text-rose-500 italic font-semibold">Belum Di-plot</span>
                            @endif
                        </td>

                        <!-- STATUS -->
                        <td class="p-3">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase 
                                {{ $item->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status == 'menunggu_penanganan' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                {{ str_replace('_', ' ', $item->status) }}
                            </span>
                        </td>

                        <!-- AKSI -->
                        <td class="p-3 text-center">
                            @if($item->status == 'menunggu_penanganan')
                                <button type="button" 
                                    onclick="openModalAssign({{ $item->id }}, '{{ $item->kode_sos }}', '{{ $item->nama_pasien }}')"
                                    class="h-8 inline-flex items-center justify-center px-3 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white font-medium text-xs rounded-md focus:ring-4 focus:ring-rose-200 transition shadow-xs cursor-pointer">
                                    Plot Ambulans
                                    <x-heroicon-s-arrow-right class="w-3.5 h-3.5 ml-1" />
                                </button>
                            @else
                                <form action="{{ route('komando.ambulans.update-status', $item->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-[11px] font-semibold border-slate-200 rounded-md p-1 bg-slate-50 focus:ring-2 focus:ring-blue-500/30 cursor-pointer">
                                        <option value="ambulans_meluncur" {{ $item->status == 'ambulans_meluncur' ? 'selected' : '' }}>Ambulans Meluncur</option>
                                        <option value="proses_evakuasi" {{ $item->status == 'proses_evakuasi' ? 'selected' : '' }}>Proses Evakuasi</option>
                                        <option value="selesai" {{ $item->status == 'selesai' ? 'selected' : '' }}>Selesai / RS Target</option>
                                        <option value="dibatalkan" {{ $item->status == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            Belum ada panggilan darurat medis dari Sub-Posko Lapangan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>