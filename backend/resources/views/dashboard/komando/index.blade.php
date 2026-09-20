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
        <div class="lg:col-span-4 space-y-4">
            
            <!-- EMERGENCY FEED CARD -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                        Emergency Feed - SOS Medis
                    </h3>
                    <span class="text-[10px] font-extrabold bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full">
                        {{ isset($sosFeeds) ? $sosFeeds->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @if(isset($sosFeeds) && $sosFeeds->count() > 0)
                        @foreach($sosFeeds as $sos)
                            <div class="p-3 bg-rose-50/60 border border-rose-100 rounded-xl flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-rose-900">{{ $sos->nama_pasien ?? 'Panggilan Darurat' }}</h4>
                                    <p class="text-[10px] text-slate-500 mt-0.5">{{ $sos->lokasi_penjemputan ?? 'Lokasi Terdampak' }}</p>
                                </div>
                                <span class="text-[10px] font-bold text-rose-600 bg-white px-2 py-1 rounded-lg shadow-2xs">
                                    DARURAT
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="py-6 text-center text-slate-400 text-xs font-medium">
                            Sistem kondusif. Tidak ada panggilan SOS medis aktif.
                        </div>
                    @endif
                </div>
            </div>

            <!-- PERMINTAAN LOGISTIK MASUK CARD -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <x-heroicon-o-inbox-arrow-down class="w-4 h-4 text-indigo-600" />
                        Permintaan Logistik Masuk
                    </h3>
                    <span class="text-[10px] font-extrabold bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">
                        {{ isset($permintaanList) ? $permintaanList->count() : 0 }}
                    </span>
                </div>

                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @if(isset($permintaanList) && $permintaanList->count() > 0)
                        @foreach($permintaanList as $req)
                            <div class="p-3 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-between">
                                <div>
                                    <h4 class="text-xs font-bold text-slate-800">{{ $req->posko->nama_posko ?? 'Sub-Posko' }}</h4>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $req->created_at ? $req->created_at->diffForHumans() : '-' }}</p>
                                </div>
                                <span class="text-[10px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md">
                                    Menunggu
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="py-6 text-center text-slate-400 text-xs font-medium">
                            Tidak ada permintaan logistik masuk dari Sub-Posko.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- Machine Learning Analytics Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-chart-bar class="w-4 h-4 text-emerald-600" />
                    Tren Stok Real-time
                </h3>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">7 Hari Terakhir</span>
            </div>
            <div class="h-44 relative">
                <canvas id="chartTrenStokRealtime"></canvas>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-arrow-trending-up class="w-4 h-4 text-blue-600" />
                    Riwayat Penyaluran Real-time
                </h3>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">Live Data</span>
            </div>
            <div class="h-44 relative">
                <canvas id="chartRiwayatPenyaluranRealtime"></canvas>
            </div>
        </div>
    </div>

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

    // Peta Dinamis (Prioritas: Posko Komando -> BPBD Induk -> Default Bantul)
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

        // A. BPBD INDUK
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

        // B. POSKO KOMANDO UTAMA
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

        // C. SUB-POSKO LAPANGAN
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

        // D. LAYER BENCANA ZONA MERAH
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

        // --- 2. CHART.JS DATA DINAMIS DARI CONTROLLER ---
        const labelsRealtime = @json($chartLabels ?? []);
        const dataStokRealtime = @json($chartStokData ?? []);
        const dataDistribusiRealtime = @json($chartDistribusiData ?? []);

        const ctxStok = document.getElementById('chartTrenStokRealtime')?.getContext('2d');
        if (ctxStok) {
            const gradientEmerald = ctxStok.createLinearGradient(0, 0, 0, 200);
            gradientEmerald.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
            gradientEmerald.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

            new Chart(ctxStok, {
                type: 'line',
                data: {
                    labels: labelsRealtime,
                    datasets: [{
                        label: 'Total Stok Posko',
                        data: dataStokRealtime,
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

        const ctxPenyaluran = document.getElementById('chartRiwayatPenyaluranRealtime')?.getContext('2d');
        if (ctxPenyaluran) {
            const gradientBlue = ctxPenyaluran.createLinearGradient(0, 0, 0, 200);
            gradientBlue.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
            gradientBlue.addColorStop(1, 'rgba(37, 99, 235, 0.0)');

            new Chart(ctxPenyaluran, {
                type: 'line',
                data: {
                    labels: labelsRealtime,
                    datasets: [{
                        label: 'Logistik Disalurkan',
                        data: dataDistribusiRealtime,
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