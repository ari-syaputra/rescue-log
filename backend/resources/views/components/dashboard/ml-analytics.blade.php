<!-- GRAFIK ANALISTIK TREN STOK & RIWAYAT PENYALURAN LOGISTIK REAL-TIME (HARIAN & JAM) -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
    
    <!-- CARD 1: TREN KETERSEDIAAN STOK -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/70 shadow-xs space-y-4 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Tren Stok Real-Time</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Update harian & per-jam pergerakan stok gudang</p>
            </div>
            <select id="filterPeriodeStok" class="text-[11px] font-bold bg-slate-50 border border-slate-200 text-slate-600 rounded-xl px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option value="today">Hari Ini</option>
                <option value="7d" selected>7 Hari Terakhir</option>
                <option value="30d">30 Hari Terakhir</option>
            </select>
        </div>
        <div class="w-full h-56 relative">
            <canvas id="chartTrenStokRealtime"></canvas>
        </div>
    </div>

    <!-- CARD 2: RIWAYAT PENYALURAN LOGISTIK -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/70 shadow-xs space-y-4 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Riwayat Penyaluran Real-Time</h3>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Catatan waktu & volume penyaluran ke sub-posko</p>
            </div>
            <span class="text-[10px] font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2.5 py-1 rounded-full flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                Live
            </span>
        </div>
        <div class="w-full h-56 relative">
            <canvas id="chartRiwayatPenyaluranRealtime"></canvas>
        </div>
    </div>

</div>