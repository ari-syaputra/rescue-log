<!-- MODAL PILIH PENEMPATAN GIS & AKTIFKAN BENCANA -->
@if(isset($bencana) && $bencana)
<div id="modalPlacement" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl max-h-[95vh] overflow-y-auto border border-slate-100">
        
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Atur Penempatan GIS Posko</h3>
                <p class="text-xs text-slate-500">Target Posko: <strong id="modal_posko_title" class="text-indigo-600"></strong></p>
            </div>
            <button type="button" onclick="closeModalPlacement()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg font-bold transition">✕</button>
        </div>

        <form action="{{ route('admin.posko.activate-existing') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="bencana_id" value="{{ $bencana->id }}">
            <input type="hidden" name="posko_id" id="place_posko_id">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alamat Deskriptif Lokasi Posko *</label>
                <textarea name="lokasi" id="place_lokasi" required rows="2" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pilih Titik Koordinat (Klik / Geser Marker Pada Peta)</label>
                <div id="map-placement"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Latitude (Lat)</label>
                    <input type="text" name="latitude" id="place_lat" readonly required class="w-full bg-transparent border-0 p-0 text-xs font-mono font-bold text-slate-800">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase">Longitude (Lng)</label>
                    <input type="text" name="longitude" id="place_lng" readonly required class="w-full bg-transparent border-0 p-0 text-xs font-mono font-bold text-slate-800">
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeModalPlacement()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition cursor-pointer">Konfirmasi & Aktifkan Posko</button>
            </div>
        </form>
    </div>
</div>
@endif