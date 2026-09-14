@extends('layouts.app')

@section('title', 'Response Center SOS Ambulans - Posko Komando')

@section('content')
<div class="space-y-6">

    <!-- Flash Message -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Response Center SOS Ambulans</h1>
            <p class="text-xs font-medium text-slate-500 mt-1">Disposisi & pemantauan evakuasi darurat medis pengungsi secara real-time.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                <span class="w-2 h-2 mr-2 bg-rose-500 rounded-full animate-pulse"></span>
                Emergency Monitor Live
            </span>
        </div>
    </div>

    <!-- 4 Stat Cards Emergency -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-rose-600 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0 font-bold text-xl">🆘</div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">DARURAT PENDING</p>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['pending'] }}</h3>
                <p class="text-[11px] text-rose-600 font-medium">Butuh disposisi cepat</p>
            </div>
        </div>

        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-amber-500 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 font-bold text-xl">🚑</div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">EVAKUASI AKTIF</p>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['meluncur'] }}</h3>
                <p class="text-[11px] text-slate-500">Ambulans bergerak</p>
            </div>
        </div>

        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-emerald-600 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-xl">✅</div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">PASIEN TERTANGANI</p>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['selesai'] }}</h3>
                <p class="text-[11px] text-slate-500">Sampai di RS Rujukan</p>
            </div>
        </div>

        <div class="bg-white p-4.5 rounded-2xl border border-slate-200/80 shadow-xs border-b-4 border-b-blue-600 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 font-bold text-xl">📋</div>
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">TOTAL PANGGILAN</p>
                <h3 class="text-2xl font-extrabold text-slate-900 leading-tight mt-0.5">{{ $stats['total'] }}</h3>
                <p class="text-[11px] text-slate-500">Selama masa bencana</p>
            </div>
        </div>
    </div>

    <!-- Main Table Response Center -->
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
                            <td class="p-3">
                                <p class="font-mono font-bold text-slate-900">{{ $item->kode_sos }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->waktu_request->diffForHumans() }}</p>
                            </td>
                            <td class="p-3">
                                <p class="font-bold text-slate-800">{{ $item->posko->nama_posko ?? 'Posko Lapangan' }}</p>
                                <p class="text-[10px] text-slate-500">PJ: {{ $item->posko->penanggung_jawab ?? '-' }} ({{ $item->posko->kontak_hp ?? '-' }})</p>
                            </td>
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
                            <td class="p-3 max-w-xs truncate" title="{{ $item->kondisi_medis }}">
                                {{ $item->kondisi_medis }}
                            </td>
                            <td class="p-3">
                                @if($item->armada)
                                    <p class="font-bold text-slate-800 flex items-center gap-1">
                                        <span>🚑</span> {{ $item->armada->nama_armada }}
                                    </p>
                                    <p class="text-[10px] text-slate-500">Driver: {{ $item->armada->pengemudi ?? '-' }} ({{ $item->armada->kontak_pengemudi ?? '-' }})</p>
                                    <p class="text-[10px] text-indigo-600 font-semibold mt-0.5">🏥 {{ $item->rs_rujukan ?? '-' }}</p>
                                @else
                                    <span class="text-xs text-rose-500 italic font-semibold">Belum Di-plot</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase 
                                    {{ $item->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status == 'menunggu_penanganan' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-center">
                                @if($item->status == 'menunggu_penanganan')
                                    <button type="button" 
                                        onclick="openModalAssign({{ $item->id }}, '{{ $item->kode_sos }}', '{{ $item->nama_pasien }}')"
                                        class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-2xs transition-all cursor-pointer">
                                        Plot Ambulans &rarr;
                                    </button>
                                @else
                                    <form action="{{ route('komando.ambulans.update-status', $item->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="text-[11px] font-semibold border-slate-200 rounded-lg p-1 bg-slate-50">
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

</div>

<!-- Modal Plotting Ambulans & RS Rujukan -->
<div id="modalAssign" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100">
        <div class="bg-slate-900 text-white px-5 py-4 flex justify-between items-center">
            <h3 class="font-bold text-sm">🚑 Plotting Armada Ambulans & RS Rujukan</h3>
            <button onclick="closeModalAssign()" class="text-slate-400 hover:text-white font-bold text-xl">&times;</button>
        </div>

        <form id="formAssign" action="" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <p class="text-xs text-slate-500">Kode SOS: <strong id="assignKodeSos" class="text-slate-900 font-mono"></strong></p>
                <p class="text-xs text-slate-500">Pasien: <strong id="assignNamaPasien" class="text-slate-900"></strong></p>
            </div>

            <!-- Select Option Armada Standby -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    PILIH UNIT ARMADA AMBULANS (STANDBY)
                </label>
                <select name="armada_id" required class="w-full text-xs border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 font-medium">
                    <option value="">-- Pilih Armada Active --</option>
                    @forelse($armadaStandby as $unit)
                        <option value="{{ $unit->id }}">
                            {{ $unit->nama_armada }} ({{ $unit->plat_nomor }}) - Driver: {{ $unit->nama_driver }}
                        </option>
                    @empty
                        <option value="" disabled>-- Tidak Ada Armada Standby/Tersedia --</option>
                    @endforelse
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rumah Sakit Rujukan Tujuan</label>
                <input type="text" name="rs_rujukan" required placeholder="Contoh: RSUD Sleman / RS PKU Muhammadiyah" class="w-full text-xs border border-slate-300 rounded-xl p-2.5">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModalAssign()" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm">Tugaskan Ambulans &rarr;</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModalAssign(id, kodeSos, namaPasien) {
        document.getElementById('formAssign').action = `/komando/ambulans/${id}/assign`;
        document.getElementById('assignKodeSos').innerText = kodeSos;
        document.getElementById('assignNamaPasien').innerText = namaPasien;
        document.getElementById('modalAssign').classList.remove('hidden');
    }

    function closeModalAssign() {
        document.getElementById('modalAssign').classList.add('hidden');
    }
</script>
@endpush
@endsection