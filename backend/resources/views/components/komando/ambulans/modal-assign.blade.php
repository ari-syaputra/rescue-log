@props(['armadaStandby'])

<div id="modalAssign" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100">
        <div class="bg-slate-900 text-white px-5 py-4 flex justify-between items-center">
            <h3 class="font-bold text-sm flex items-center gap-2">
                <x-heroicon-s-truck class="w-4 h-4 text-rose-500" />
                <span>Plotting Armada Ambulans & RS Rujukan</span>
            </h3>
            <button onclick="closeModalAssign()" class="text-slate-400 hover:text-white font-bold text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <form id="formAssign" action="" method="POST" class="p-5 space-y-4">
            @csrf
            <div class="bg-slate-50 p-3 rounded-md border border-slate-200/80">
                <p class="text-xs text-slate-500">Kode SOS: <strong id="assignKodeSos" class="text-slate-900 font-mono"></strong></p>
                <p class="text-xs text-slate-500">Pasien: <strong id="assignNamaPasien" class="text-slate-900"></strong></p>
            </div>

            <!-- Select Option Armada Standby -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    PILIH UNIT ARMADA AMBULANS (STANDBY)
                </label>
                <select name="armada_id" required class="w-full text-xs border border-slate-300 rounded-md p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600 font-medium cursor-pointer">
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
                <input type="text" name="rs_rujukan" required placeholder="Contoh: RSUD Sleman / RS PKU Muhammadiyah" class="w-full text-xs border border-slate-300 rounded-md p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600">
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModalAssign()" class="h-10 inline-flex items-center justify-center px-4 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-medium rounded-md focus:ring-4 focus:ring-slate-100 transition shadow-xs whitespace-nowrap cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="h-10 inline-flex items-center justify-center px-4 bg-rose-600 hover:bg-rose-700 active:bg-rose-800 text-white text-sm font-medium rounded-md focus:ring-4 focus:ring-rose-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                    Tugaskan Ambulans
                    <x-heroicon-s-arrow-right class="w-4 h-4 ml-1.5" />
                </button>
            </div>
        </form>
    </div>
</div>