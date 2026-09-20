@extends('layouts.app-lapangan')

@section('title', 'Dashboard Posko Lapangan')

@section('content')
    <div class="w-full space-y-4 sm:space-y-6 font-sans px-1 sm:px-0" x-data="{ isLoading: true }" x-init="setTimeout(() => {
        isLoading = false;
        setTimeout(() => {
            if (window.initSubPoskoMap) window.initSubPoskoMap();
        }, 300);
    }, 600)">

        <!-- SKELETON LOADING -->
        <div x-show="isLoading" class="space-y-4 sm:space-y-6">
            @include('components.skeleton.dashboard')
        </div>

        <!-- MAIN DASHBOARD CONTENT -->
        <div x-show="!isLoading" style="display: none;" class="space-y-4 sm:space-y-6">

            <!-- 1. HERO BANNER RINGKAS -->
            <div class="w-full bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-3xl p-5 sm:p-7 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="px-3 py-1 bg-blue-500/30 border border-blue-400/30 rounded-full text-xs font-semibold text-blue-200 inline-block mb-2">
                            Posko Lapangan Aktif
                        </span>
                        <h1 class="text-xl sm:text-2xl font-bold tracking-tight">
                            {{ $subPosko->nama_posko ?? 'Posko Lapangan' }}
                        </h1>
                        <p class="text-xs sm:text-sm text-blue-100/80 mt-1 max-w-xl">
                            {{ $bencanaAktif->nama_bencana ?? 'Operasional Tanggap Darurat Bencana' }} — {{ $bencanaAktif->lokasi_bencana ?? 'Wilayah Operasional' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md p-3 rounded-2xl border border-white/10 shrink-0">
                        <div class="p-2.5 bg-blue-500/20 rounded-xl text-blue-300">
                            <x-heroicon-s-users class="w-6 h-6" />
                        </div>
                        <div>
                            <span class="block text-[11px] uppercase tracking-wider text-blue-200 font-semibold">Total Pengungsi</span>
                            <span class="text-lg font-bold text-white">{{ number_format($totalPengungsiReal ?? 0) }} Jiwa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. KARTU MENU UTAMA (ACTION CARDS LANGSUNG DITULIS DENGAN AMAN) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4 font-sans">
                <!-- Pendataan Pengungsi -->
                <a href="{{ route('lapangan.pengungsi.index') }}" 
                   class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition flex flex-col justify-between group cursor-pointer">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center mb-2 group-hover:scale-105 transition">
                        <x-heroicon-s-users class="w-5 h-5 sm:w-6 sm:h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-800 leading-snug group-hover:text-purple-600 transition">Pendataan Pengungsi</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5 line-clamp-1">Input & update KK / khusus</p>
                    </div>
                </a>

                <!-- Pengajuan Logistik -->
                <a href="{{ route('lapangan.pengajuan.index') }}" 
                   class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition flex flex-col justify-between group cursor-pointer">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center mb-2 group-hover:scale-105 transition">
                        <x-heroicon-s-inbox-stack class="w-5 h-5 sm:w-6 sm:h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-800 leading-snug group-hover:text-blue-600 transition">Pengajuan Logistik</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5 line-clamp-1">Ajukan kebutuhan posko</p>
                    </div>
                </a>

                <!-- Status Distribusi -->
                <a href="{{ route('lapangan.stok.index') }}" 
                   class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition flex flex-col justify-between group cursor-pointer">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2 group-hover:scale-105 transition">
                        <x-heroicon-s-truck class="w-5 h-5 sm:w-6 sm:h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-800 leading-snug group-hover:text-emerald-600 transition">Status Distribusi</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5 line-clamp-1">Cek status stok & penerimaan</p>
                    </div>
                </a>

                <!-- Pengiriman & BAST -->
                <a href="{{ route('lapangan.penyaluran.index') }}" 
                   class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition flex flex-col justify-between group cursor-pointer">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mb-2 group-hover:scale-105 transition">
                        <x-heroicon-s-clipboard-document-check class="w-5 h-5 sm:w-6 sm:h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-800 leading-snug group-hover:text-amber-600 transition">Pengiriman & BAST</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5 line-clamp-1">Catat penyaluran & stok</p>
                    </div>
                </a>

                <!-- Request Ambulan -->
                <a href="{{ route('lapangan.ambulans.index') }}" 
                   class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200/80 shadow-xs hover:border-blue-400 hover:shadow-md transition flex flex-col justify-between group cursor-pointer col-span-2 md:col-span-1 lg:col-span-1">
                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-2 group-hover:scale-105 transition">
                        <x-heroicon-s-phone class="w-5 h-5 sm:w-6 sm:h-6" />
                    </div>
                    <div>
                        <h3 class="font-bold text-xs sm:text-sm text-slate-800 leading-snug group-hover:text-rose-600 transition">Request Ambulan</h3>
                        <p class="text-[10px] sm:text-xs text-slate-400 font-medium mt-0.5 line-clamp-1">Permintaan darurat medis</p>
                    </div>
                </a>
            </div>

            <!-- 3. PETA & DOKUMENTASI -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 items-stretch w-full">

                <!-- Dokumentasi Terkini -->
                <div class="h-full flex flex-col order-2 lg:order-1">
                    <x-sub-posko.detail.documentation :subPosko="$subPosko" />
                </div>

                <!-- Peta Lokasi -->
                <div class="h-full flex flex-col order-1 lg:order-2">
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between h-full space-y-3 sm:space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm sm:text-base font-bold text-slate-900">Peta Lokasi & Posko</h3>
                                <p class="text-[11px] sm:text-xs text-slate-500 font-medium mt-0.5">Monitoring koordinat operasional</p>
                            </div>

                            <button type="button" id="btn-dashboard-locate"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-xl text-xs font-bold transition border border-blue-200/60 cursor-pointer shrink-0">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>GPS Saya</span>
                            </button>
                        </div>

                        <!-- Map Container -->
                        <div id="dashboardMap" class="w-full flex-1 min-h-[240px] sm:min-h-[300px] rounded-xl overflow-hidden border border-slate-200 z-0"></div>

                        <div class="flex flex-wrap items-center justify-between gap-2 text-[11px] sm:text-xs pt-2 border-t border-slate-100">
                            <span class="text-slate-500">
                                Lat/Lng: <strong id="txt-coords" class="font-mono font-semibold text-slate-800">{{ $subPosko->latitude ?? '-7.7956' }}, {{ $subPosko->longitude ?? '110.3695' }}</strong>
                            </span>
                            <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[10px] sm:text-[11px] border border-emerald-200/70 inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Posko Aktif
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-fullscreen@1.0.2/dist/leaflet.fullscreen.css" />
    <style>
        .leaflet-pane { z-index: 10 !important; }
        .leaflet-top, .leaflet-bottom { z-index: 20 !important; }
        .leaflet-popup-content-wrapper {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            border-radius: 12px !important;
            padding: 4px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-fullscreen@1.0.2/dist/Leaflet.fullscreen.min.js"></script>

    <script>
        let mapInstance = null;

        window.initSubPoskoMap = function() {
            const mapContainer = document.getElementById('dashboardMap');
            if (!mapContainer) return;

            if (mapInstance) {
                setTimeout(() => { mapInstance.invalidateSize(); }, 200);
                return;
            }

            const initialLat = parseFloat("{{ $subPosko->latitude ?? '-7.7956' }}");
            const initialLng = parseFloat("{{ $subPosko->longitude ?? '110.3695' }}");

            const bencanaData = @json($bencanaAktif ?? null);

            let rawGeoJson = null;
            if (bencanaData) {
                rawGeoJson = bencanaData.geojson_polygon || 
                             bencanaData.geojson_area || 
                             bencanaData.polygon_area || 
                             bencanaData.polygon || null;
            }

            const osmStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            });

            const esriSatellite = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
                    attribution: 'Tiles &copy; Esri'
                });

            mapInstance = L.map('dashboardMap', {
                center: [initialLat, initialLng],
                zoom: 13,
                layers: [osmStreet],
                fullscreenControl: true,
                fullscreenControlOptions: { position: 'topleft' }
            });

            const baseMaps = { "Peta Jalan": osmStreet, "Satelit": esriSatellite };
            const overlayMaps = {};

            if (rawGeoJson) {
                try {
                    let parsedGeoJson = typeof rawGeoJson === 'string' ? JSON.parse(rawGeoJson) : rawGeoJson;
                    if (typeof parsedGeoJson === 'string') { parsedGeoJson = JSON.parse(parsedGeoJson); }

                    let disasterLayer = null;

                    if (Array.isArray(parsedGeoJson) && parsedGeoJson.length > 0 && parsedGeoJson[0].lat !== undefined) {
                        const latLngs = parsedGeoJson.map(item => [item.lat, item.lng]);
                        disasterLayer = L.polygon(latLngs, {
                            color: '#EF4444',
                            weight: 3,
                            opacity: 0.9,
                            fillColor: '#FCA5A5',
                            fillOpacity: 0.4
                        });
                    } else {
                        disasterLayer = L.geoJSON(parsedGeoJson, {
                            style: function () {
                                return { color: '#EF4444', weight: 3, opacity: 0.9, fillColor: '#FCA5A5', fillOpacity: 0.4 };
                            }
                        });
                    }

                    if (disasterLayer) {
                        const jenisBencana = bencanaData.jenis_bencana || 'Bencana Alam';
                        const lokasiBencana = bencanaData.lokasi_bencana || 'Area Terdampak Bencana';
                        const luasArea = bencanaData.luas_area_km2 ? `${bencanaData.luas_area_km2} km²` : '-';

                        disasterLayer.bindPopup(`
                            <div class="p-1 font-sans text-slate-800">
                                <strong class="text-xs font-bold block text-rose-600 mb-0.5">⚠️ ZONA TERDAMPAK BENCANA</strong>
                                <p class="text-sm font-bold text-slate-900 mb-0.5">${jenisBencana}</p>
                                <p class="text-xs text-slate-600 mb-1.5">${lokasiBencana}</p>
                                <span class="text-[10px] bg-rose-50 text-rose-700 px-2 py-0.5 rounded-md font-semibold border border-rose-200 inline-block">
                                    Luas Area: ${luasArea}
                                </span>
                            </div>
                        `);

                        disasterLayer.addTo(mapInstance);
                        overlayMaps["Zona Bencana"] = disasterLayer;

                        setTimeout(() => {
                            try {
                                const bounds = disasterLayer.getBounds();
                                if (bounds && bounds.isValid()) {
                                    bounds.extend([initialLat, initialLng]);
                                    mapInstance.fitBounds(bounds, { padding: [20, 20] });
                                }
                            } catch (errBounds) {}
                        }, 300);
                    }
                } catch (e) {}
            }

            L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(mapInstance);

            const poskoMarker = L.marker([initialLat, initialLng]).addTo(mapInstance)
                .bindPopup(`
                    <div class="p-1 font-sans text-slate-800">
                        <strong class="text-sm font-bold block text-slate-900 mb-0.5">{{ $subPosko->nama_posko ?? 'Posko Lapangan' }}</strong>
                        <p class="text-xs text-slate-500 mb-2">Lokasi Operasional Utama</p>
                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-600 text-white font-bold tracking-wide uppercase">POSKO AKTIF</span>
                    </div>
                `).openPopup();

            setTimeout(() => { if (mapInstance) mapInstance.invalidateSize(); }, 400);

            document.getElementById('btn-dashboard-locate')?.addEventListener('click', function() {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            poskoMarker.setLatLng([lat, lng]);
                            mapInstance.setView([lat, lng], 15);
                            document.getElementById('txt-coords').innerText = lat.toFixed(6) + ', ' + lng.toFixed(6);
                        },
                        function() { alert("Gagal mendeteksi lokasi GPS. Pastikan izin lokasi aktif."); },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                }
            });
        };
    </script>
@endpush