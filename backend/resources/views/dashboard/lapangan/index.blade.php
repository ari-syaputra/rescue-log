@extends('layouts.app-lapangan')

@section('title', 'Dashboard Posko Lapangan')

@section('content')
    <div class="w-full space-y-6 font-sans" x-data="{ isLoading: true }" x-init="setTimeout(() => {
        isLoading = false;
        $nextTick(() => { window.dispatchEvent(new Event('resize')); });
    }, 1000)">

        <div x-show="isLoading" class="space-y-6">
            @include('components.skeleton.dashboard')
        </div>

        <div x-show="!isLoading" style="display: none;" class="space-y-6">

            <x-sub-posko.hero-banner :bencana="$bencanaAktif ?? null" :total-pengungsi="$totalPengungsiReal ?? 0" />

            <div class="w-full">
                @include('components.sub-posko.navbarlp.action-cards')
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch w-full">

                <div class="h-full flex flex-col">
                    @if (view()->exists('components.sub-posko.detail.documentation'))
                        <x-sub-posko.detail.documentation :sub-posko="$subPosko" />
                    @else
                        <div
                            class="bg-white p-6 rounded-2xl shadow-xs border border-slate-200/80 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="font-bold text-base text-slate-900">Dokumentasi Terkini</h2>
                                    <span class="text-[11px] font-semibold text-slate-400">0 Foto</span>
                                </div>
                                <p class="text-xs text-slate-500 leading-relaxed">Belum ada foto kegiatan posko atau
                                    dokumentasi lapangan yang diunggah.</p>
                            </div>

                            <div
                                class="mt-8 border-2 border-dashed border-slate-200 rounded-xl p-6 text-center bg-slate-50/50">
                                <svg class="w-8 h-8 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-xs font-semibold text-slate-600 block">Tidak ada gambar</span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="h-full flex flex-col">
                    <div
                        class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between h-full space-y-4">

                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-base font-bold text-slate-900">Peta Lokasi Bencana & Posko</h3>
                                <p class="text-xs text-slate-500 font-medium mt-0.5">Monitoring titik koordinat operasional
                                    lapangan</p>
                            </div>

                            <button type="button" id="btn-dashboard-locate"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100/80 rounded-xl text-xs font-bold transition border border-blue-200/60 cursor-pointer shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>GPS Saya</span>
                            </button>
                        </div>

                        <div id="dashboardMap"
                            class="w-full flex-1 min-h-[300px] rounded-xl overflow-hidden border border-slate-200 z-0">
                        </div>

                        <div
                            class="flex flex-wrap items-center justify-between gap-2 text-xs pt-2 border-t border-slate-100">
                            <span class="text-slate-500">
                                Lat/Lng: <strong id="txt-coords"
                                    class="font-mono font-semibold text-slate-800">{{ $subPosko->latitude ?? '-7.7956' }},
                                    {{ $subPosko->longitude ?? '110.3695' }}</strong>
                            </span>
                            <span
                                class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 font-bold text-[11px] border border-emerald-200/70 inline-flex items-center gap-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Posko Lapangan Aktif
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
        .leaflet-pane {
            z-index: 10 !important;
        }

        .leaflet-top,
        .leaflet-bottom {
            z-index: 20 !important;
        }

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
        document.addEventListener('DOMContentLoaded', function() {
            const initialLat = parseFloat("{{ $subPosko->latitude ?? '-7.7956' }}");
            const initialLng = parseFloat("{{ $subPosko->longitude ?? '110.3695' }}");

            const osmStreet = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            });

            const esriSatellite = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
                    attribution: 'Tiles &copy; Esri'
                });

            const map = L.map('dashboardMap', {
                center: [initialLat, initialLng],
                zoom: 13,
                layers: [osmStreet],
                fullscreenControl: true,
                fullscreenControlOptions: {
                    position: 'topleft'
                }
            });

            L.control.layers({
                "Peta Jalan": osmStreet,
                "Satelit": esriSatellite
            }, null, {
                position: 'topright'
            }).addTo(map);

            const poskoMarker = L.marker([initialLat, initialLng]).addTo(map)
                .bindPopup(`
                    <div class="p-1 font-sans text-slate-800">
                        <strong class="text-sm font-bold block text-slate-900 mb-0.5">{{ $subPosko->nama_posko ?? 'Posko Lapangan' }}</strong>
                        <p class="text-xs text-slate-500 mb-2">Lokasi Operasional Utama</p>
                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-600 text-white font-bold tracking-wide uppercase">POSKO AKTIF</span>
                    </div>
                `).openPopup();

            window.addEventListener('resize', function() {
                setTimeout(() => {
                    map.invalidateSize();
                }, 200);
            });

            document.getElementById('btn-dashboard-locate').addEventListener('click', function() {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;

                            poskoMarker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 15);
                            document.getElementById('txt-coords').innerText = lat.toFixed(6) + ', ' +
                                lng.toFixed(6);
                        },
                        function() {
                            alert(
                                "Gagal mendeteksi lokasi GPS. Pastikan izin lokasi pada browser diaktifkan.");
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000
                        }
                    );
                }
            });
        });
    </script>
@endpush