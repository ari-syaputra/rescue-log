<div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs relative flex flex-col h-full justify-between space-y-3">
    <div class="flex items-center justify-between shrink-0">
        <div class="flex items-center gap-2">
            <h3 class="text-xs font-black text-slate-900 uppercase tracking-wide">Peta Taktis Live (GIS)</h3>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700">LIVE</span>
        </div>
        <span class="text-[11px] text-slate-400">Update terakhir: {{ now()->format('H:i:s') }} WIB</span>
    </div>

    <div class="w-full flex-1 rounded-xl bg-slate-100 border border-slate-200 relative overflow-hidden min-h-[440px]">
        <div id="map" class="w-full h-full"></div>
        
        <div class="absolute top-3 left-12 z-[500] bg-white/95 backdrop-blur-xs p-3 rounded-xl shadow-md border border-slate-200/80 space-y-1.5 text-xs w-44">
            <p class="font-extrabold text-[10px] text-slate-400 uppercase tracking-wider">FILTER LAYER PETA</p>
            <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                <span>Layer Hazard</span>
                <input type="checkbox" id="chkHazard" checked class="rounded text-red-500 focus:ring-0 cursor-pointer">
            </label>
            <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                <span>Layer Sub-Posko</span>
                <input type="checkbox" id="chkPosko" checked class="rounded text-emerald-500 focus:ring-0 cursor-pointer">
            </label>
            <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                <span>Layer Armada</span>
                <input type="checkbox" id="chkArmada" checked class="rounded text-blue-500 focus:ring-0 cursor-pointer">
            </label>
        </div>

        <div class="absolute bottom-3 inset-x-3 z-[500] bg-white/95 backdrop-blur-xs px-4 py-2 rounded-xl shadow-md border border-slate-200/80 flex flex-wrap items-center justify-between text-[11px] font-bold text-slate-700 gap-2">
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600"></span> Posko Komando</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Sub-Posko Aktif</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-600"></span> Armada Bergerak</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-red-500/30 border border-red-500 rounded-xs"></span> Zona Bahaya</span>
            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-600 text-white flex items-center justify-center text-[8px]">x</span> Rintangan Jalan</span>
        </div>
    </div>
</div>