@extends('layouts.app')

@section('content')
@push('styles')
<!-- Leaflet CSS & Custom Pulse Animation -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 100%;
        min-height: 420px;
        width: 100%;
        border-radius: 0.75rem;
        z-index: 10;
    }
    .leaflet-routing-container { display: none !important; }
    .custom-pulse-marker { position: relative; }
    .custom-pulse-marker::after {
        content: '';
        position: absolute;
        width: 100%; height: 100%; top: 0; left: 0;
        background-color: rgba(239, 68, 68, 0.4);
        border-radius: 50%;
        animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 0.8; }
        100% { transform: scale(2.4); opacity: 0; }
    }
</style>
@endpush

<div class="w-full space-y-6 pb-10">
    <!-- Hero Banner -->
    <x-dashboard.hero-banner 
        :posko="$posko ?? null" 
        :armadaSiap="$armadaSiap ?? 0" 
        :personelSiaga="$personelSiaga ?? 0" 
        :lokasiTerdampak="$lokasiTerdampak ?? 0" 
        :logistikTerkirim="$logistikTerkirim ?? 0" 
    />

    <!-- Summary Cards -->
    <x-dashboard.summary-cards 
        :pengajuanMasukCount="$pengajuanMasukCount ?? 0" 
        :distribusiBerjalanCount="$distribusiBerjalanCount ?? 0" 
        :stokKritisCount="$stokKritisCount ?? 0" 
        :totalPoskoKecil="$totalPoskoKecil ?? 0" 
    />

    <!-- Main Section: Live Map (Kiri) & Emergency Feed (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
        <div class="lg:col-span-8 flex flex-col h-full">
            <x-dashboard.map-gis />
        </div>
        <div class="lg:col-span-4">
            <x-dashboard.sos-feed />
        </div>
    </div>

    <!-- Machine Learning Analytics Section -->
    <x-dashboard.ml-analytics />

    <!-- Pintasan Menu Utama -->
    <x-dashboard.quick-menu />

    <!-- Log Aktivitas -->
    <x-dashboard.activity-log />
</div>

@endsection

@push('scripts')
<!-- Leaflet JS & Chart.js -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    let mainMap;
    const defaultLat = -7.7956;
    const defaultLng = 110.3695;

    const kendalaData = @json($kendalaJalans ?? []);
    const subPoskoData = @json($totalPoskoList ?? []);

    const activeKendala = kendalaData.length > 0 ? kendalaData : [
        { latitude: -7.7850, longitude: 110.3750, nama_lokasi: "Desa Tegalrejo", jenis_kendala: "jembatan_putus", deskripsi: "Jembatan Utama Putus akibat Banjir", is_active: 1 },
        { latitude: -7.8100, longitude: 110.3600, nama_lokasi: "Jl. Parangtritis Km 4", jenis_kendala: "longsor_total", deskripsi: "Akses Tertutup Longsor", is_active: 1 }
    ];

    const activePosko = subPoskoData.length > 0 ? subPoskoData : [
        { latitude: -7.7650, longitude: 110.3600, nama_posko: "Sub-Posko Sukamaju 01", jumlah_pengungsi: 127 },
        { latitude: -7.8150, longitude: 110.3850, nama_posko: "Sub-Posko Ngijo 02", jumlah_pengungsi: 85 }
    ];

    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. LEAFLET MAP INIT ---
        mainMap = L.map('map', { zoomControl: false }).setView([defaultLat, defaultLng], 12);
        L.control.zoom({ position: 'topright' }).addTo(mainMap);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap RESCUE-LOG'
        }).addTo(mainMap);

        L.circleMarker([defaultLat, defaultLng], {
            radius: 11,
            fillColor: '#2563eb',
            color: '#ffffff',
            weight: 3,
            fillOpacity: 1
        }).addTo(mainMap).bindPopup("<b>POSKO KOMANDO UTAMA</b>");

        let hazardLayerGroup = L.layerGroup().addTo(mainMap);
        activeKendala.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                L.circle([lat, lng], { color: '#dc2626', fillColor: '#dc2626', fillOpacity: 0.25, radius: 600 }).addTo(hazardLayerGroup);
                L.circleMarker([lat, lng], { radius: 8, fillColor: '#dc2626', color: '#fff', weight: 2, fillOpacity: 1, className: 'custom-pulse-marker' }).addTo(hazardLayerGroup);
            }
        });

        let poskoLayerGroup = L.layerGroup().addTo(mainMap);
        activePosko.forEach(posko => {
            const lat = parseFloat(posko.latitude);
            const lng = parseFloat(posko.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                L.circleMarker([lat, lng], { radius: 9, fillColor: '#10b981', color: '#fff', weight: 2, fillOpacity: 1 }).addTo(poskoLayerGroup);
            }
        });

        document.getElementById('chkHazard')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(hazardLayerGroup) : mainMap.removeLayer(hazardLayerGroup);
        });
        document.getElementById('chkPosko')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(poskoLayerGroup) : mainMap.removeLayer(poskoLayerGroup);
        });

        // --- 2. CHART.JS REAL-TIME (DETAIL TANGGAL & JAM HARIAN) ---

        // Format Label: Tanggal Harian & Jam Log Real-Time
        const labelsTanggalJam = [
            '10 Sep 08:00',
            '11 Sep 10:30',
            '12 Sep 14:15',
            '13 Sep 09:00',
            '14 Sep 16:45',
            '15 Sep 11:20',
            '15 Sep 19:00'
        ];

        // CHART 1: Tren Stok Real-Time
        const elChartStok = document.getElementById('chartTrenStokRealtime') || document.getElementById('chartTrenLogistikML');
        const ctxStok = elChartStok?.getContext('2d');
        
        if (ctxStok) {
            const gradientEmerald = ctxStok.createLinearGradient(0, 0, 0, 200);
            gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            new Chart(ctxStok, {
                type: 'line',
                data: {
                    labels: labelsTanggalJam,
                    datasets: [{
                        label: 'Total Stok Gudang',
                        data: [128500, 127200, 126000, 125400, 124100, 123200, 122850],
                        borderColor: '#10b981',
                        borderWidth: 2.5,
                        fill: true,
                        backgroundColor: gradientEmerald,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#059669',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: (items) => `Waktu: ${items[0].label}`,
                                label: (ctx) => ` Sisa Stok: ${ctx.raw.toLocaleString()} Item`
                            }
                        }
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 10, weight: '600' }, maxRotation: 0 } 
                        },
                        y: { 
                            grid: { color: '#f1f5f9', borderDash: [4, 4] }, 
                            ticks: { 
                                color: '#94a3b8', 
                                font: { size: 10, weight: '600' }, 
                                callback: (val) => val >= 1000 ? (val / 1000) + 'k' : val 
                            } 
                        }
                    }
                }
            });
        }

        // CHART 2: Riwayat Penyaluran Logistik Real-Time
        const elChartPenyaluran = document.getElementById('chartRiwayatPenyaluranRealtime') || document.getElementById('chartDistribusiML');
        const ctxPenyaluran = elChartPenyaluran?.getContext('2d');
        
        if (ctxPenyaluran) {
            const gradientBlue = ctxPenyaluran.createLinearGradient(0, 0, 0, 200);
            gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

            new Chart(ctxPenyaluran, {
                type: 'line',
                data: {
                    labels: labelsTanggalJam,
                    datasets: [{
                        label: 'Logistik Disalurkan',
                        data: [1300, 1200, 600, 1300, 900, 350, 400],
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        fill: true,
                        backgroundColor: gradientBlue,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: '#1d4ed8',
                        pointHoverBorderColor: '#ffffff',
                        pointHoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            enabled: true,
                            backgroundColor: '#0f172a',
                            titleFont: { size: 11, weight: 'bold' },
                            bodyFont: { size: 11 },
                            padding: 10,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: (items) => `Waktu: ${items[0].label}`,
                                label: (ctx) => ` Disalurkan: ${ctx.raw.toLocaleString()} Paket`
                            }
                        }
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: '#94a3b8', font: { size: 10, weight: '600' }, maxRotation: 0 } 
                        },
                        y: { 
                            grid: { color: '#f1f5f9', borderDash: [4, 4] }, 
                            ticks: { 
                                color: '#94a3b8', 
                                font: { size: 10, weight: '600' }, 
                                stepSize: 500 
                            } 
                        }
                    }
                }
            });
        }
    });
</script>
@endpush