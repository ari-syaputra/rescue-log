@extends('layouts.app-lapangan')

@section('content')
<div class="w-full space-y-6">

    <!-- HERO BANNER INFORMASI BENCANA AKTIF -->
    <x-sub-posko.hero-banner 
        :bencana="$bencanaAktif ?? null" 
        :total-pengungsi="$totalPengungsiReal ?? 0" 
    />

    <!-- 4 CARD MENU INTERAKTIF (SEJAJAR DENGAN HERO BANNER) -->
    <div class="w-full">
        @include('components.sub-posko.navbarlp.action-cards')
    </div>

    <!-- STATUS ALERT / PERINGATAN DARURAT -->
    @if (view()->exists('components.sub-posko.alert'))
        <x-sub-posko.alert :sub-posko="$subPosko" />
    @else
        <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl shadow-xs">
            <h3 class="font-bold text-amber-800 text-sm">⚠️ Status Darurat</h3>
            <p class="text-xs text-amber-700 mt-1">Petugas lapangan harap selalu memperbarui koordinat lokasi terkini.</p>
        </div>
    @endif

    <!-- LAYOUT GRID 2 KOLOM: DOKUMENTASI (KIRI) & PETA LOKASI BENCANA (KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch w-full">
        
        <!-- Kolom Kiri: Dokumentasi Posko -->
        <div class="h-full flex flex-col">
            @if (view()->exists('components.sub-posko.detail.documentation'))
                <x-sub-posko.detail.documentation :sub-posko="$subPosko" />
            @else
                <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200 h-full">
                    <h2 class="font-semibold text-lg text-slate-800">Dokumentasi Terkini</h2>
                    <p class="text-xs text-slate-500 mt-1">Belum ada foto dokumentasi yang diunggah.</p>
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Peta Kejadian Bencana & Lokasi Posko -->
        <div class="h-full flex flex-col">
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between h-full space-y-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Peta Lokasi Bencana & Posko</h3>
                        <p class="text-[11px] text-slate-400 font-medium">Monitoring titik koordinat lapangan</p>
                    </div>
                    
                    <!-- Tombol Deteksi GPS -->
                    <button type="button" id="btn-dashboard-locate" class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-semibold transition border border-indigo-200 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        GPS Saya
                    </button>
                </div>

                <!-- Leaflet Map Container -->
                <div id="dashboardMap" class="w-full flex-1 min-h-[280px] rounded-xl overflow-hidden border border-slate-200 z-0"></div>

                <!-- Footer Koordinat Terkini -->
                <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100">
                    <span class="text-slate-500">
                        Lat/Lng: <strong id="txt-coords" class="font-mono text-slate-700">{{ $subPosko->latitude ?? '-7.7956' }}, {{ $subPosko->longitude ?? '110.3695' }}</strong>
                    </span>
                    <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700 font-semibold text-[10px]">
                        Posko Lapangan Aktif
                    </span>
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
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-fullscreen@1.0.2/dist/Leaflet.fullscreen.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const initialLat = parseFloat("{{ $subPosko->latitude ?? '-7.7956' }}");
            const initialLng = parseFloat("{{ $subPosko->longitude ?? '110.3695' }}");

            const osmStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            });

            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18,
                attribution: 'Tiles &copy; Esri'
            });

            const map = L.map('dashboardMap', {
                center: [initialLat, initialLng],
                zoom: 13,
                layers: [osmStreet],
                fullscreenControl: true,
                fullscreenControlOptions: { position: 'topleft' }
            });

            L.control.layers({
                "Peta Jalan": osmStreet,
                "Satelit": esriSatellite
            }, null, { position: 'topright' }).addTo(map);

            const poskoMarker = L.marker([initialLat, initialLng]).addTo(map)
                .bindPopup(`
                    <div class="text-xs font-sans">
                        <strong class="text-sm font-bold block mb-1">{{ $subPosko->nama_posko ?? 'Posko Lapangan' }}</strong>
                        <p class="text-slate-500 mb-1">Lokasi Operasional Utama</p>
                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-600 text-white font-semibold">POSKO AKTIF</span>
                    </div>
                `).openPopup();

            document.getElementById('btn-dashboard-locate').addEventListener('click', function () {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            poskoMarker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 15);
                            document.getElementById('txt-coords').innerText = lat.toFixed(6) + ', ' + lng.toFixed(6);
                        },
                        function () {
                            alert("Gagal mendeteksi lokasi GPS. Pastikan izin lokasi aktif.");
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                }
            });
        });
    </script>
@endpush