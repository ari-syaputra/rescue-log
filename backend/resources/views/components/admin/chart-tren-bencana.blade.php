@props(['bencana' => null])

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <x-heroicon-s-chart-bar class="w-4 h-4 text-blue-600 shrink-0" />
                <span>Tren Kejadian Bencana</span>
            </h2>
            <select class="text-[11px] border border-slate-200 rounded-lg px-2 py-1 bg-slate-50 text-slate-600 font-medium focus:outline-none focus:ring-1 focus:ring-blue-500">
                <option>7 Hari Terakhir</option>
                <option>30 Hari Terakhir</option>
            </select>
        </div>
        
        <div class="h-44 w-full relative my-2">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    <!-- Ringkasan Status Bencana dari Controller -->
    <div class="grid grid-cols-2 gap-3 mt-3">
        <div class="bg-red-50/60 p-2.5 rounded-xl border border-red-100">
            <span class="text-[10px] font-semibold text-slate-500 block truncate">Bencana Aktif</span>
            <div class="flex items-baseline gap-1.5 mt-0.5">
                <span class="text-base font-extrabold text-slate-900">
                    {{ $bencana ? '1' : '0' }}
                </span>
                <span class="text-[10px] font-bold text-red-600">
                    {{ $bencana ? 'Berjalan' : 'Nihil' }}
                </span>
            </div>
        </div>

        <div class="bg-emerald-50/60 p-2.5 rounded-xl border border-emerald-100">
            <span class="text-[10px] font-semibold text-slate-500 block truncate">Status Tanggap</span>
            <div class="flex items-baseline gap-1.5 mt-0.5">
                <span class="text-xs font-extrabold text-emerald-700 truncate">
                    {{ $bencana ? 'Siaga Operasional' : 'Aman' }}
                </span>
            </div>
        </div>
    </div>
</div>