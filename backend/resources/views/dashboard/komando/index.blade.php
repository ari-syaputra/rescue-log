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
        :armadaSiap="$armadaSiap ?? 12" 
        :personelSiaga="$personelSiaga ?? 48" 
        :lokasiTerdampak="$lokasiTerdampak ?? 4" 
        :logistikTerkirim="$logistikTerkirim ?? 234" 
    />

    <!-- Summary Cards -->
    <x-dashboard.summary-cards 
        :pengajuanMasukCount="$pengajuanMasukCount ?? 3" 
        :distribusiBerjalanCount="$distribusiBerjalanCount ?? 1" 
        :stokKritisCount="$stokKritisCount ?? 4" 
        :totalPoskoKecil="$totalPoskoKecil ?? 5" 
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

    // Data Real dari Backend
    const currentPosko = @json($posko ?? null);
    const bpbdInduk = @json($bpbd ?? null);
    const bencanaAktif = @json($bencana ?? null);
    const subPoskoList = @json($totalPoskoList ?? []);

    // Tentukan Pusat Peta (Prioritas: Posko Komando -> BPBD Induk -> Fallback Bantul)
    const defaultLat = currentPosko && currentPosko.latitude ? parseFloat(currentPosko.latitude) : (bpbdInduk && bpbdInduk.latitude ? parseFloat(bpbdInduk.latitude) : -7.8893);
    const defaultLng = currentPosko && currentPosko.longitude ? parseFloat(currentPosko.longitude) : (bpbdInduk && bpbdInduk.longitude ? parseFloat(bpbdInduk.longitude) : 110.3288);

    document.addEventListener("DOMContentLoaded", () => {
        // --- 1. LEAFLET MAP INIT ---
        mainMap = L.map('map', { zoomControl: false }).setView([defaultLat, defaultLng], 12);
        L.control.zoom({ position: 'topright' }).addTo(mainMap);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap RESCUE-LOG'
        }).addTo(mainMap);

        setTimeout(() => { mainMap.invalidateSize(); }, 300);

        // ==========================================
        // A. LAYER BPBD KABUPATEN (Gudang Induk)
        // ==========================================
        if (bpbdInduk && bpbdInduk.latitude && bpbdInduk.longitude) {
            const bpbdIcon = L.divIcon({
                className: 'custom-bpbd-icon',
                html: `<div class="w-7 h-7 bg-amber-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white text-[11px] font-bold">🏛️</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            L.marker([parseFloat(bpbdInduk.latitude), parseFloat(bpbdInduk.longitude)], { icon: bpbdIcon })
                .addTo(mainMap)
                .bindPopup(`<b>🏛️ ${bpbdInduk.nama_kabupaten_kota || 'BPBD Induk'}</b><br><small>${bpbdInduk.alamat_kantor || ''}</small>`);
        }

        // ==========================================
        // B. LAYER POSKO KOMANDO UTAMA (Posko Saya)
        // ==========================================
        if (currentPosko && currentPosko.latitude && currentPosko.longitude) {
            const komandoIcon = L.divIcon({
                className: 'custom-komando-icon',
                html: `<div class="w-8 h-8 bg-indigo-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white text-[12px] font-bold">🏢</div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            L.marker([parseFloat(currentPosko.latitude), parseFloat(currentPosko.longitude)], { icon: komandoIcon })
                .addTo(mainMap)
                .bindPopup(`<b>🏢 ${currentPosko.nama_posko} (Posko Komando Utama)</b><br>PJ: ${currentPosko.penanggung_jawab}<br>Status: AKTIF OPERASI`);
        }

        // ==========================================
        // C. LAYER SUB-POSKO LAPANGAN (HIJAU)
        // ==========================================
        let subPoskoLayerGroup = L.layerGroup().addTo(mainMap);

        const subIcon = L.divIcon({
            className: 'custom-sub-icon',
            html: `<div class="w-6 h-6 bg-emerald-600 rounded-full border-2 border-white shadow-md flex items-center justify-center text-white text-[10px] font-bold">⛺</div>`,
            iconSize: [24, 24],
            iconAnchor: [12, 12]
        });

        subPoskoList.forEach(sp => {
            const lat = parseFloat(sp.latitude);
            const lng = parseFloat(sp.longitude);
            if (!isNaN(lat) && !isNaN(lng)) {
                L.marker([lat, lng], { icon: subIcon })
                    .addTo(subPoskoLayerGroup)
                    .bindPopup(`<b>⛺ ${sp.nama_posko} (Sub-Posko Lapangan)</b><br>PJ: ${sp.penanggung_jawab}<br>Petugas: ${sp.jumlah_petugas || 0} Jiwa`);
            }
        });

        // ==========================================
        // D. LAYER BENCANA (TITIK MERAH + POLIGON GEOJSON)
        // ==========================================
        let hazardLayerGroup = L.layerGroup().addTo(mainMap);

        if (bencanaAktif && bencanaAktif.koordinat_operasional_lat && bencanaAktif.koordinat_operasional_lng) {
            const bLat = parseFloat(bencanaAktif.koordinat_operasional_lat);
            const bLng = parseFloat(bencanaAktif.koordinat_operasional_lng);

            const bencanaIcon = L.divIcon({
                className: 'custom-bencana-icon',
                html: `<div class="w-6 h-6 bg-rose-600 rounded-full border-2 border-white shadow-lg custom-pulse-marker flex items-center justify-center text-white text-[10px] font-bold">⚠️</div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });

            L.marker([bLat, bLng], { icon: bencanaIcon })
                .addTo(hazardLayerGroup)
                .bindPopup(`<b>⚠️ [Bencana] ${bencanaAktif.jenis_bencana}</b><br>Lokasi: ${bencanaAktif.lokasi_bencana}`);

            // Render Poligon GeoJSON Area Terdampak
            let polygonData = bencanaAktif.geojson_polygon;
            if (typeof polygonData === 'string') {
                try { polygonData = JSON.parse(polygonData); } catch (e) {}
            }

            if (Array.isArray(polygonData) && polygonData.length >= 3) {
                const polygonLatLngs = polygonData.map(pt => [parseFloat(pt.lat), parseFloat(pt.lng)]);
                L.polygon(polygonLatLngs, {
                    color: '#dc2626',
                    weight: 2,
                    fillColor: '#ef4444',
                    fillOpacity: 0.25,
                    dashArray: '5, 5'
                }).bindTooltip(`Zona Terdampak: ${bencanaAktif.jenis_bencana}`, {
                    sticky: true,
                    className: 'text-xs font-bold border-0 shadow-md'
                }).addTo(hazardLayerGroup);
            }
        }

        // Toggle Layer Checkbox Control (Jika Elemen Checkbox Ada)
        document.getElementById('chkHazard')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(hazardLayerGroup) : mainMap.removeLayer(hazardLayerGroup);
        });
        document.getElementById('chkPosko')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(subPoskoLayerGroup) : mainMap.removeLayer(subPoskoLayerGroup);
        });

        // --- 2. CHART.JS REAL-TIME ---
        const labelsTanggalJam = ['10 Sep 08:00', '11 Sep 10:30', '12 Sep 14:15', '13 Sep 09:00', '14 Sep 16:45', '15 Sep 11:20', '16 Sep 08:00'];

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
                        label: 'Total Stok Posko',
                        data: [12850, 12720, 12600, 12540, 12410, 12320, 12285],
                        borderColor: '#10b981',
                        borderWidth: 2.5,
                        fill: true,
                        backgroundColor: gradientEmerald,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } } },
                        y: { grid: { color: '#f1f5f9', borderDash: [4, 4] }, ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } } }
                    }
                }
            });
        }

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
                        data: [130, 120, 60, 130, 90, 35, 40],
                        borderColor: '#2563eb',
                        borderWidth: 2.5,
                        fill: true,
                        backgroundColor: gradientBlue,
                        tension: 0.35,
                        pointRadius: 4,
                        pointBackgroundColor: '#2563eb',
                        pointBorderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } } },
                        y: { grid: { color: '#f1f5f9', borderDash: [4, 4] }, ticks: { color: '#94a3b8', font: { size: 10, weight: '600' } } }
                    }
                }
            });
        }
    });
</script>
@endpush