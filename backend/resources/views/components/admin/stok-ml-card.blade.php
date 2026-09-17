@props(['bpbd' => null, 'stok' => null])

@php
    // Fleksibilitas data dari controller
    $totalStok = $stok['total'] ?? ($bpbd->total_stok ?? 0);
    $tersedia  = $stok['tersedia'] ?? ($bpbd->stok_tersedia ?? 0);
    $menipis   = $stok['menipis'] ?? ($bpbd->stok_menipis ?? 0);
    $habis     = $stok['habis'] ?? ($bpbd->stok_habis ?? 0);
    
    // Hitung persentase ketersediaan
    $persenTersedia = $totalStok > 0 ? round(($tersedia / $totalStok) * 100) : 0;

    // Route Lookup Otomatis Berdasarkan Role User
    $gudangRoute = '#';
    if (Route::has('admin.inventaris') && auth()->user()?->role === 'admin') {
        $gudangRoute = route('admin.inventaris');
    } elseif (Route::has('komando.logistik.index') && in_array(auth()->user()?->role, ['komando', 'koordinator_komando', 'posko_komando'])) {
        $gudangRoute = route('komando.logistik.index');
    } elseif (Route::has('lapangan.stok.index') && auth()->user()?->role === 'lapangan') {
        $gudangRoute = route('lapangan.stok.index');
    } elseif (Route::has('admin.inventaris')) {
        // Fallback default ke route admin jika role tidak spesifik
        $gudangRoute = route('admin.inventaris');
    }
@endphp

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
    <div>
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                <x-heroicon-s-archive-box class="w-4 h-4 text-blue-600 shrink-0" />
                <span>Stok Logistik</span>
            </h2>
            
            <a href="{{ $gudangRoute }}" 
               class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1 transition-colors">
                Lihat Detail &rarr;
            </a>
        </div>

        <div class="flex items-center gap-4 my-3">
            <!-- Container Chart Donut -->
            <div class="relative w-28 h-28 shrink-0">
                <canvas id="chartStok"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-sm font-extrabold text-slate-900">{{ $persenTersedia }}%</span>
                    <span class="text-[9px] text-slate-500 font-bold">Tersedia</span>
                </div>
            </div>

            <!-- Breakdown Statistik -->
            <div class="space-y-1.5 text-xs w-full">
                <div class="text-xs font-bold text-slate-500 mb-1">
                    Total Stok 
                    <span class="text-slate-900 font-extrabold text-sm block">
                        {{ number_format($totalStok, 0, ',', '.') }} item
                    </span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="flex items-center gap-1.5 text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Tersedia
                    </span>
                    <span class="font-bold text-slate-800">{{ number_format($tersedia, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="flex items-center gap-1.5 text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Menipis
                    </span>
                    <span class="font-bold text-slate-800">{{ number_format($menipis, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center text-[11px]">
                    <span class="flex items-center gap-1.5 text-slate-600">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Habis
                    </span>
                    <span class="font-bold text-slate-800">{{ number_format($habis, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert ML Prediksi -->
    <div class="mt-3 bg-red-50/80 border border-red-100 rounded-xl p-3 flex gap-2.5 items-start">
        <x-heroicon-s-bell class="w-5 h-5 text-red-500 shrink-0 mt-0.5" />
        <div>
            <span class="text-[11px] font-bold text-red-800 block">Prediksi Kebutuhan (ML)</span>
            <p class="text-[10px] text-red-600 leading-tight mt-0.5">
                @if($menipis > 0 || $habis > 0)
                    Terdeteksi <strong>{{ $menipis + $habis }} item</strong> berstatus menipis/habis. Disarankan restock dalam 7–10 hari ke depan.
                @else
                    Kondisi persediaan aman. Belum ada kebutuhan <em>restock</em> mendesak minggu ini.
                @endif
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('chartStok');
        if (ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Menipis', 'Habis'],
                    datasets: [{
                        data: [{{ $tersedia }}, {{ $menipis }}, {{ $habis }}],
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: true }
                    }
                }
            });
        }
    });
</script>