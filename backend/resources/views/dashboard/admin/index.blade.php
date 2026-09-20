@extends('layouts.app')

@section('title', 'Dashboard BPBD Utama')

@section('content')
<div class="space-y-5">

    <!-- 1. HERO BANNER -->
    <x-admin.hero-banner :bpbd="$bpbd" />

    <!-- 2. STATISTIK UTAMA -->
    <x-admin.statistik-utama 
        :posko="$posko" 
        :bencana="$bencanaAktif" 
        :bpbd="$bpbd" 
        :permintaanCount="$permintaanMasukCount"
        :distribusiCount="$distribusiBerjalanCount" />

    <!-- 3. BARIS TENGAH (CHART & DYNAMIC CARDS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- CHART TREN BENCANA -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-4 h-4 text-rose-500" />
                    Tren Kejadian Bencana
                </h3>
                <span class="text-[10px] font-semibold bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md">7 Hari Terakhir</span>
            </div>
            
            <div class="h-48 relative">
                <canvas id="chartTren"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 mt-4 pt-3 border-t border-slate-100 text-center">
                <div class="p-2 bg-rose-50/60 rounded-xl">
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Bencana Aktif</span>
                    <p class="text-sm font-black text-rose-600">{{ $bencanaAktif ? '1 Aktif' : 'Nihil' }}</p>
                </div>
                <div class="p-2 bg-emerald-50/60 rounded-xl">
                    <span class="text-[10px] text-slate-400 font-bold uppercase">Status Tanggap</span>
                    <p class="text-sm font-black text-emerald-600">{{ $bencanaAktif ? 'Siaga' : 'Aman' }}</p>
                </div>
            </div>
        </div>

        <!-- STOK LOGISTIK CHART -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-archive-box class="w-4 h-4 text-amber-500" />
                    Stok Logistik
                </h3>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Detail &rarr;</a>
            </div>

            <div class="flex items-center justify-around my-auto py-2">
                <div class="w-28 h-28 relative flex items-center justify-center">
                    <canvas id="chartStok"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-lg font-black text-slate-800">{{ $persenTersedia }}%</span>
                        <span class="text-[9px] text-slate-400 font-bold uppercase">Tersedia</span>
                    </div>
                </div>

                <div class="space-y-1.5 text-xs">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-600 font-medium">Tersedia: <b class="text-slate-900">{{ $stokTersedia }}</b></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                        <span class="text-slate-600 font-medium">Menipis: <b class="text-slate-900">{{ $stokMenipis }}</b></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                        <span class="text-slate-600 font-medium">Habis: <b class="text-slate-900">{{ $stokHabis }}</b></span>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3 bg-amber-50/70 rounded-xl border border-amber-100 text-[11px] text-amber-800 flex items-center gap-2">
                <x-heroicon-s-bell class="w-4 h-4 text-amber-600 shrink-0" />
                <span><b>Prediksi Kebutuhan (ML):</b> {{ $stokMenipis > 0 ? 'Beberapa barang butuh restocking segera!' : 'Kondisi persediaan logistik dalam keadaan aman.' }}</span>
            </div>
        </div>

        <!-- PERMINTAAN MASUK CARD -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-document-text class="w-4 h-4 text-indigo-600" />
                    Permintaan Logistik Masuk
                </h3>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
            </div>

            <div class="flex-1 overflow-y-auto space-y-2 max-h-56 pr-1">
                @forelse($permintaanTerbaru as $item)
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">{{ $item->posko->nama_posko ?? 'Posko Lapangan' }}</h4>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-amber-100 text-amber-700">
                            {{ ucfirst($item->status) }}
                        </span>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center py-8">
                        <x-heroicon-o-inbox class="w-8 h-8 text-slate-300 mb-1" />
                        <p class="text-xs text-slate-400 font-medium">Belum ada permintaan masuk</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- 4. BARIS BAWAH (TABEL DISTRIBUSI & RINGKASAN LAPORAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- TABEL DISTRIBUSI TERAKHIR -->
        <div class="lg:col-span-8 bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-truck class="w-4 h-4 text-blue-600" />
                    Distribusi Terakhir
                </h3>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                            <th class="pb-3 font-bold">Tanggal</th>
                            <th class="pb-3 font-bold">Posko Tujuan</th>
                            <th class="pb-3 font-bold">Jenis Logistik</th>
                            <th class="pb-3 font-bold">Jumlah</th>
                            <th class="pb-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($distribusiTerakhir as $dist)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 text-slate-600 font-medium">{{ \Carbon\Carbon::parse($dist->tanggal_kirim)->format('d/m/Y') }}</td>
                                <td class="py-3 font-bold text-slate-800">{{ $dist->poskoTujuan->nama_posko ?? '-' }}</td>
                                <td class="py-3 text-slate-600">{{ $dist->kode_pengiriman }}</td>
                                <td class="py-3 font-bold text-slate-800">{{ $dist->armada->nama_armada ?? 'Armada BPBD' }}</td>
                                <td class="py-3">
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold 
                                        {{ $dist->status_pengiriman == 'terkirim' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                                        {{ ucfirst(str_replace('_', ' ', $dist->status_pengiriman ?? 'Dijadwalkan')) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                                    Belum ada riwayat distribusi logistik.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- RINGKASAN LAPORAN -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-100 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-clipboard-document-check class="w-4 h-4 text-emerald-600" />
                    Ringkasan Laporan
                </h3>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-3 gap-2 my-auto">
                <div class="p-3 bg-indigo-50/70 rounded-2xl text-center border border-indigo-100">
                    <x-heroicon-o-shield-check class="w-5 h-5 text-indigo-600 mx-auto mb-1" />
                    <span class="text-lg font-black text-indigo-900 block">{{ $totalBencanaDitangani }}</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase leading-tight block mt-0.5">Bencana Ditangani</span>
                </div>

                <div class="p-3 bg-emerald-50/70 rounded-2xl text-center border border-emerald-100">
                    <x-heroicon-o-archive-box class="w-5 h-5 text-emerald-600 mx-auto mb-1" />
                    <span class="text-lg font-black text-emerald-900 block">{{ $totalPaketTersalurkan }}</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase leading-tight block mt-0.5">Paket Tersalurkan</span>
                </div>

                <div class="p-3 bg-purple-50/70 rounded-2xl text-center border border-purple-100">
                    <x-heroicon-o-user-group class="w-5 h-5 text-purple-600 mx-auto mb-1" />
                    <span class="text-lg font-black text-purple-900 block">{{ number_format($totalPengungsiTerlayani, 0, ',', '.') }}</span>
                    <span class="text-[9px] text-slate-500 font-bold uppercase leading-tight block mt-0.5">Pengungsi Terlayani</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart Tren Bencana (Dinamis dari Controller)
        const ctxTren = document.getElementById('chartTren')?.getContext('2d');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [
                        { 
                            label: 'Total Kejadian', 
                            data: @json($chartBencanaData), 
                            borderColor: '#ef4444', 
                            backgroundColor: '#ef4444', 
                            tension: 0.3, 
                            borderWidth: 2, 
                            pointRadius: 3 
                        },
                        { 
                            label: 'Selesai Ditangani', 
                            data: @json($chartDitanganiData), 
                            borderColor: '#10b981', 
                            backgroundColor: '#10b981', 
                            tension: 0.3, 
                            borderWidth: 2, 
                            pointRadius: 3 
                        }
                    ]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { legend: { display: false } }, 
                    scales: { y: { beginAtZero: true, ticks: { precision: 0 } }, x: { grid: { display: false } } } 
                }
            });
        }

        // Donut Chart Stok Logistik (Dinamis dari Controller)
        const ctxStok = document.getElementById('chartStok')?.getContext('2d');
        if (ctxStok) {
            new Chart(ctxStok, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Menipis', 'Habis'],
                    datasets: [{ 
                        data: [{{ $stokTersedia }}, {{ $stokMenipis }}, {{ $stokHabis }}], 
                        backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], 
                        borderWidth: 0, 
                        cutout: '75%' 
                    }]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    plugins: { legend: { display: false } } 
                }
            });
        }
    });
</script>
@endpush