@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <!-- 1. HERO BANNER (Menerima variabel $bpbd) -->
    <x-admin.hero-banner :bpbd="$bpbd" />

    <!-- 2. STATISTIK UTAMA (Menerima $posko dan $bencanaAktif dari Controller) -->
    <x-admin.statistik-utama :posko="$posko" :bencana="$bencanaAktif" :bpbd="$bpbd" />

    <!-- 3. BARIS TENGAH (3 KOLOM) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-4">
            <x-admin.chart-tren-bencana />
        </div>
        <div class="lg:col-span-4">
            <x-admin.stok-ml-card />
        </div>
        <div class="lg:col-span-4">
            <x-admin.permintaan-masuk-card />
        </div>
    </div>

    <!-- 4. BARIS BAWAH (DISTRIBUSI TERAKHIR & RINGKASAN LAPORAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-8">
            <x-admin.tabel-distribusi-terakhir />
        </div>
        <div class="lg:col-span-4">
            <x-admin.ringkasan-laporan />
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Line Chart Tren Bencana
        const ctxTren = document.getElementById('chartTren')?.getContext('2d');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: ['19 Apr', '24 Apr', '29 Apr', '4 Mei', '9 Mei', '14 Mei', '19 Mei'],
                    datasets: [
                        { label: 'Bencana', data: [5, 7, 9, 6, 10, 7, 7], borderColor: '#ef4444', backgroundColor: '#ef4444', tension: 0.3, borderWidth: 2, pointRadius: 2 },
                        { label: 'Ditangani', data: [3, 4, 5, 3, 6, 4, 3], borderColor: '#10b981', backgroundColor: '#10b981', tension: 0.3, borderWidth: 2, pointRadius: 2 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } } }
            });
        }

        // Donut Chart Stok Logistik
        const ctxStok = document.getElementById('chartStok')?.getContext('2d');
        if (ctxStok) {
            new Chart(ctxStok, {
                type: 'doughnut',
                data: {
                    labels: ['Tersedia', 'Menipis', 'Habis'],
                    datasets: [{ data: [78, 18, 4], backgroundColor: ['#10b981', '#f59e0b', '#ef4444'], borderWidth: 0, cutout: '75%' }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        }
    });
</script>
@endpush