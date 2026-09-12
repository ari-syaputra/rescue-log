@extends('layouts.app')

@section('content')
@push('styles')
<!-- Leaflet CSS & Custom Pulse Animation -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    #map {
        height: 100%;
        min-height: 440px;
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

<div class="w-full space-y-5 pb-10">

    <!-- TOP HEADER BAR: STATUS OPERASI & WAKTU -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-black bg-red-100 text-red-600 border border-red-200 animate-pulse">
                <span class="w-2 h-2 mr-2 bg-red-500 rounded-full"></span>
                STATUS DARURAT 1 - AKTIF
            </span>
            <div>
                <h2 class="text-sm font-black text-slate-800 leading-tight">Operasi Tanggap Darurat Bencana</h2>
                <p class="text-[11px] text-slate-500 font-medium">Kabupaten Karanganyar, Jawa Tengah</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="text-xs font-bold text-slate-600 bg-slate-100 px-3 py-1.5 rounded-xl">
                {{ now()->format('H:i:s') }} WIB &bull; {{ now()->translatedFormat('d M Y') }}
            </span>
        </div>
    </div>

    <!-- 4 METRIC CARDS RINGKASAN TAKTIS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <!-- Card 1: Armada Transit -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <x-fas-truck class="w-5 h-5" />
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">ARMADA TERDISPENSASI</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-black text-slate-900">{{ $armadaTransit ?? 18 }}</span>
                    <span class="text-xs font-bold text-slate-500">Unit</span>
                </div>
                <span class="text-[10px] text-slate-400 block">ETA Terdekat: 12 mnt</span>
            </div>
        </div>

        <!-- Card 2: SOS Medis Alert -->
        <div class="bg-red-50/50 p-4 rounded-2xl border border-red-200/60 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                <x-fas-kit-medical class="w-5 h-5 animate-pulse" />
            </div>
            <div>
                <span class="text-[10px] font-extrabold text-red-600 uppercase tracking-wider block">POP-UP SOS MEDIS</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-black text-red-700">{{ $sosUnhandled ?? 3 }}</span>
                    <span class="text-xs font-bold text-red-600">Laporan</span>
                </div>
                <span class="text-[10px] font-bold text-red-500 block">High Priority</span>
            </div>
        </div>

        <!-- Card 3: Sub-Posko -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <x-heroicon-s-map-pin class="w-5 h-5" />
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">SUB-POSKO TERVERIFIKASI</span>
                <div class="flex items-baseline gap-1">
                    <span class="text-xl font-black text-slate-900">{{ $totalPoskoKecil ?? 12 }}</span>
                    <span class="text-xs font-bold text-slate-500">Titik</span>
                </div>
                <span class="text-[10px] text-emerald-600 font-bold block">Terverifikasi Hari Ini</span>
            </div>
        </div>

        <!-- Card 4: Buffer Stock -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <x-fas-boxes-stacked class="w-5 h-5" />
            </div>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">STOK BUFFER LOGISTIK</span>
                <span class="text-sm font-black text-emerald-600 uppercase block">SANGAT CUKUP</span>
                <span class="text-[10px] text-slate-400 block">92% Kapasitas Tersedia</span>
            </div>
        </div>
    </div>

    <!-- MAIN SECTION: MAP (LEFT 8 COLS) & SIDE FEED (RIGHT 4 COLS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">

        <!-- LEFT COLUMN: LIVE TACTICAL GIS MAP -->
        <div class="lg:col-span-8 flex flex-col h-full">
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs relative flex flex-col h-full justify-between">
                
                <!-- Header Card Peta -->
                <div class="flex items-center justify-between mb-3 shrink-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-black text-slate-900 uppercase tracking-wide">Peta Taktis Live (GIS)</h3>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-700">LIVE</span>
                    </div>
                    <span class="text-[11px] text-slate-400">Update terakhir: {{ now()->format('H:i:s') }} WIB</span>
                </div>

                <!-- GIS Map Viewport Interaktif (Flex-1 akan mengisi seluruh sisa tinggi kontainer secara otomatis) -->
                <div class="w-full flex-1 rounded-xl bg-slate-100 border border-slate-200 relative overflow-hidden min-h-[460px]">
                    <!-- Element Utama Leaflet Map -->
                    <div id="map" class="w-full h-full"></div>
                    
                    <!-- Floating Filter Layer Peta (Kiri Atas Peta) -->
                    <div class="absolute top-3 left-12 z-[500] bg-white/95 backdrop-blur-xs p-3 rounded-xl shadow-md border border-slate-200/80 space-y-1.5 text-xs w-44">
                        <p class="font-extrabold text-[10px] text-slate-400 uppercase tracking-wider">FILTER LAYER PETA</p>
                        <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                            <span>Layer Hazard</span>
                            <input type="checkbox" id="chkHazard" checked class="rounded text-red-500 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                            <span>Layer Sub-Posko</span>
                            <input type="checkbox" id="chkPosko" checked class="rounded text-emerald-500 focus:ring-0">
                        </label>
                        <label class="flex items-center justify-between font-bold text-slate-700 text-[11px] cursor-pointer">
                            <span>Layer Armada</span>
                            <input type="checkbox" id="chkArmada" checked class="rounded text-blue-500 focus:ring-0">
                        </label>
                    </div>

                    <!-- Map Legend Bar (Bawah Peta) -->
                    <div class="absolute bottom-3 inset-x-3 z-[500] bg-white/95 backdrop-blur-xs px-4 py-2 rounded-xl shadow-md border border-slate-200/80 flex flex-wrap items-center justify-between text-[11px] font-bold text-slate-700 gap-2">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600"></span> Posko Komando</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Sub-Posko Aktif</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-600"></span> Armada Bergerak</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 bg-red-500/30 border border-red-500 rounded-xs"></span> Zona Bahaya</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-red-600 text-white flex items-center justify-center text-[8px]">x</span> Rintangan Jalan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: FEED DISPATCH SOS & REQUEST LOGISTIK -->
        <div class="lg:col-span-4 flex flex-col justify-between space-y-4">
            
            <!-- SECTION 1: EMERGENCY SOS FEED -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <div class="flex items-center gap-2">
                        <h4 class="text-xs font-black text-red-600 uppercase tracking-wide">EMERGENCY FEED - SOS MEDIS</h4>
                        <span class="w-5 h-5 rounded-full bg-red-600 text-white font-bold text-[10px] flex items-center justify-center">3</span>
                    </div>
                </div>

                <!-- SOS Item 1 (Kritis) -->
                <div class="p-3 bg-red-50/60 border border-red-200/80 rounded-xl space-y-2">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-1.5 py-0.5 bg-red-600 text-white font-black text-[9px] rounded-md uppercase">BARU</span>
                            <h5 class="text-xs font-bold text-slate-900 mt-1">Siti Aminah <span class="font-normal text-slate-500">(Perempuan, 62 Th)</span></h5>
                            <p class="text-[11px] text-slate-500">Desa Tegalrejo, Kec. Karangpandan</p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold">10:45 WIB</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-red-600">Kategori: Sesak Napas (Prioritas 1)</span>
                        <span class="text-slate-400">Jarak: 8.2 km</span>
                    </div>
                    <button class="w-full py-2 bg-red-600 hover:bg-red-700 text-white font-bold text-xs rounded-xl shadow-md shadow-red-600/20 transition flex items-center justify-center gap-2">
                        <x-fas-truck-medical class="w-3.5 h-3.5" />
                        Dispatch Ambulans
                    </button>
                </div>

                <!-- SOS Item 2 -->
                <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-xl space-y-1.5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h5 class="text-xs font-bold text-slate-900">Budi Santoso <span class="font-normal text-slate-500">(Laki-laki, 45 Th)</span></h5>
                            <p class="text-[11px] text-slate-500">Desa Blulukan, Kec. Colomadu</p>
                        </div>
                        <span class="text-[10px] text-slate-400 font-semibold">10:42 WIB</span>
                    </div>
                    <div class="flex items-center justify-between text-[11px]">
                        <span class="font-bold text-red-600">Kategori: Luka Berat</span>
                        <span class="text-slate-400">Jarak: 11.5 km</span>
                    </div>
                    <button class="w-full py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl transition">
                        Dispatch Ambulans
                    </button>
                </div>
            </div>

            <!-- SECTION 2: PENDING LOGISTIK REQUESTS (ANTI-BULLWHIP) -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2.5">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wide">PERMINTAAN LOGISTIK MASUK</h4>
                    <span class="w-5 h-5 rounded-full bg-orange-500 text-white font-bold text-[10px] flex items-center justify-center">5</span>
                </div>

                <div class="space-y-2">
                    <!-- Request 1 -->
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 space-y-1 text-[11px]">
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-slate-800">Sub-Posko 04 (Sukama)</span>
                            <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">Menunggu</span>
                        </div>
                        <p class="text-slate-500">Req: 50 Paket Sembako | 20 Dus Air</p>
                        <div class="flex justify-between items-center pt-1 border-t border-slate-200/60">
                            <span class="font-bold text-emerald-600">Rekomendasi ML: 45 Paket (AI 92%)</span>
                            <a href="{{ route('komando.pengajuan.index') }}" class="text-blue-600 font-bold hover:underline">Review</a>
                        </div>
                    </div>

                    <!-- Request 2 -->
                    <div class="p-2.5 bg-slate-50 rounded-xl border border-slate-200/60 space-y-1 text-[11px]">
                        <div class="flex justify-between items-center font-bold">
                            <span class="text-slate-800">Sub-Posko 05 (Ngijo)</span>
                            <span class="text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">Menunggu</span>
                        </div>
                        <p class="text-slate-500">Req: 30 Selimut | 15 Matras</p>
                        <div class="flex justify-between items-center pt-1 border-t border-slate-200/60">
                            <span class="font-bold text-emerald-600">Rekomendasi ML: 28 Selimut (AI 95%)</span>
                            <a href="{{ route('komando.pengajuan.index') }}" class="text-blue-600 font-bold hover:underline">Review</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <!-- AKTIVITAS SISTEM TERBARU -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Aktivitas Sistem Terbaru</h3>
            <a href="#" class="text-xs font-bold text-blue-600 hover:underline">Lihat Semua Log</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                    <x-fas-truck class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-bold text-slate-800">Armada ARM-08 Berangkat</p>
                    <p class="text-[10px] text-slate-400">Menuju Sub-Posko 04 via Rute AI-01</p>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <x-fas-triangle-exclamation class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-bold text-slate-800">Rintangan Jalan Terdeteksi</p>
                    <p class="text-[10px] text-slate-400">Desa Tegalrejo (Jembatan Putus)</p>
                </div>
            </div>

            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200/60 flex items-center gap-3 text-xs">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                    <x-fas-boxes-stacked class="w-4 h-4" />
                </div>
                <div>
                    <p class="font-bold text-slate-800">Stok Sub-Posko 03 Diperbarui</p>
                    <p class="text-[10px] text-slate-400">Status Pasokan: Cukup</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

<script>
    let mainMap;
    // Set Titik Pusat Peta ke Yogyakarta/Sleman agar marker langsung terlihat di layar
    const defaultLat = -7.7956;
    const defaultLng = 110.3695;

    // Data dari Controller Backend dengan Fallback Data Dummy Visual
    const kendalaData = @json($kendalaJalans ?? []) ;
    const subPoskoData = @json($totalPoskoList ?? []) ;

    // Fallback Data Kendala (Jika Database Kosong, Gunakan Data Ini)
    const activeKendala = kendalaData.length > 0 ? kendalaData : [
        { latitude: -7.7850, longitude: 110.3750, nama_lokasi: "Desa Tegalrejo", jenis_kendala: "jembatan_putus", deskripsi: "Jembatan Utama Putus akibat Banjir", is_active: 1 },
        { latitude: -7.8100, longitude: 110.3600, nama_lokasi: "Jl. Parangtritis Km 4", jenis_kendala: "longsor_total", deskripsi: "Akses Tertutup Longsor", is_active: 1 },
        { latitude: -7.7700, longitude: 110.3900, nama_lokasi: "Area Kalitirto", jenis_kendala: "jalan_rusak", deskripsi: "Genangan Air & Jalan Berlubang", is_active: 1 }
    ];

    // Fallback Data Sub-Posko
    const activePosko = subPoskoData.length > 0 ? subPoskoData : [
        { latitude: -7.7650, longitude: 110.3600, nama_posko: "Sub-Posko Sukamaju 01", jumlah_pengungsi: 127 },
        { latitude: -7.8150, longitude: 110.3850, nama_posko: "Sub-Posko Ngijo 02", jumlah_pengungsi: 85 },
        { latitude: -7.7900, longitude: 110.3400, nama_posko: "Sub-Posko Blulukan 03", jumlah_pengungsi: 210 }
    ];

    function isHardBlocker(jenisInput, deskripsiInput) {
        const text = ((jenisInput || '') + ' ' + (deskripsiInput || '')).toLowerCase();
        const hardKeywords = ['jembatan_putus', 'jembatan putus', 'putus', 'longsor_total', 'terputus', 'ambrol', 'roboh'];
        return hardKeywords.some(key => text.includes(key));
    }

    document.addEventListener("DOMContentLoaded", () => {
        // 1. Inisialisasi Peta Utama
        mainMap = L.map('map', { zoomControl: false }).setView([defaultLat, defaultLng], 12);

        L.control.zoom({ position: 'topright' }).addTo(mainMap);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap RESCUE-LOG'
        }).addTo(mainMap);

        // 2. Render Marker Utama: Posko Komando (Biru)
        L.circleMarker([defaultLat, defaultLng], {
            radius: 12,
            fillColor: '#2563eb',
            color: '#ffffff',
            weight: 3,
            fillOpacity: 1
        }).addTo(mainMap).bindPopup("<div class='font-sans p-1'><b class='text-blue-600'>POSKO KOMANDO UTAMA</b><br><small>Pusat Kendali Taktis BPBD</small></div>");

        // 3. Render Marker & Zona Bahaya Lingkaran Merah (Hazard)
        let hazardLayerGroup = L.layerGroup().addTo(mainMap);
        activeKendala.forEach(item => {
            const lat = parseFloat(item.latitude);
            const lng = parseFloat(item.longitude);

            if (!isNaN(lat) && !isNaN(lng) && item.is_active) {
                const isBlocked = isHardBlocker(item.jenis_kendala, item.deskripsi);
                const markerColor = isBlocked ? '#dc2626' : '#f59e0b';

                // LINGKARAN MERAH ZONA BAHAYA (POLYGON CIRCLE)
                L.circle([lat, lng], {
                    color: markerColor,
                    fillColor: markerColor,
                    fillOpacity: 0.3,
                    radius: isBlocked ? 800 : 500
                }).addTo(hazardLayerGroup);

                // MARKER TITIK RINTANGAN BERKEDIP
                const marker = L.circleMarker([lat, lng], {
                    radius: 9,
                    fillColor: markerColor,
                    color: '#ffffff',
                    weight: 2,
                    fillOpacity: 0.9,
                    className: 'custom-pulse-marker'
                }).addTo(hazardLayerGroup);

                marker.bindPopup(`
                    <div class="p-1 font-sans">
                        <span class="text-[10px] font-bold ${isBlocked ? 'text-red-600' : 'text-amber-600'} uppercase">
                            ${isBlocked ? '⛔ ZONA BAHAYA / JALAN TERPUTUS' : '⚠️ JALAN RUSAK'}
                        </span>
                        <h4 class="font-bold text-xs text-slate-900 mt-0.5">${item.nama_lokasi}</h4>
                        <p class="text-[11px] text-slate-500">${item.deskripsi ?? ''}</p>
                    </div>
                `);
            }
        });

        // 4. Render Sub-Posko Lapangan (Hijau)
        let poskoLayerGroup = L.layerGroup().addTo(mainMap);
        activePosko.forEach(posko => {
            const lat = parseFloat(posko.latitude);
            const lng = parseFloat(posko.longitude);

            if (!isNaN(lat) && !isNaN(lng)) {
                const marker = L.circleMarker([lat, lng], {
                    radius: 10,
                    fillColor: '#10b981',
                    color: '#ffffff',
                    weight: 2,
                    fillOpacity: 0.9
                }).addTo(poskoLayerGroup);

                marker.bindPopup(`
                    <div class="p-1 font-sans">
                        <span class="text-[10px] font-bold text-emerald-600 uppercase">SUB-POSKO LAPANGAN</span>
                        <h4 class="font-bold text-xs text-slate-900 mt-0.5">${posko.nama_posko}</h4>
                        <p class="text-[11px] text-slate-500">Pengungsi: <b>${posko.jumlah_pengungsi ?? 0} Jiwa</b></p>
                    </div>
                `);
            }
        });

        // 5. Render Simulation Armada Bergerak (Ungu/Indigo)
        let armadaLayerGroup = L.layerGroup().addTo(mainMap);
        const armadaPositions = [
            { lat: -7.7800, lng: 110.3650, nama: "ARM-08 (Truk Logistik)", rute: "Menuju Sub-Posko 01" },
            { lat: -7.8000, lng: 110.3750, nama: "AMB-02 (Ambulans Medis)", rute: "Evakuasi Pasien Siti Aminah" }
        ];

        armadaPositions.forEach(arm => {
            const marker = L.circleMarker([arm.lat, arm.lng], {
                radius: 8,
                fillColor: '#4f46e5',
                color: '#ffffff',
                weight: 2,
                fillOpacity: 1
            }).addTo(armadaLayerGroup);

            marker.bindPopup(`
                <div class="p-1 font-sans">
                    <span class="text-[10px] font-bold text-indigo-600 uppercase">ARMADA BERGERAK</span>
                    <h4 class="font-bold text-xs text-slate-900 mt-0.5">${arm.nama}</h4>
                    <p class="text-[11px] text-slate-500">${arm.rute}</p>
                </div>
            `);
        });

        // Toggle Filter Layer Map
        document.getElementById('chkHazard')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(hazardLayerGroup) : mainMap.removeLayer(hazardLayerGroup);
        });
        document.getElementById('chkPosko')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(poskoLayerGroup) : mainMap.removeLayer(poskoLayerGroup);
        });
        document.getElementById('chkArmada')?.addEventListener('change', (e) => {
            e.target.checked ? mainMap.addLayer(armadaLayerGroup) : mainMap.removeLayer(armadaLayerGroup);
        });
    });
</script>
@endpush