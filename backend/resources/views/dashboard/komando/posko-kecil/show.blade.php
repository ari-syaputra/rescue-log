@extends('layouts.app')

@section('title', 'Detail Posko Kecil - SiGap BPBD')

@section('content')
{{-- Diubah dari max-w-7xl menjadi w-full space-y-6 pb-12 agar lebar konten penuh --}}
<div class="w-full space-y-6 pb-12" x-data="{ activeTab: window.location.hash ? window.location.hash.substring(1) : 'informasi' }">


    {{-- Header Halaman --}}
    <x-sub-posko.detail.header :subPosko="$subPosko" />

    {{-- Hero Card Informasi Utama --}}
    <x-sub-posko.detail.hero-card :subPosko="$subPosko" />

    {{-- Navigation Tabs --}}
    <x-sub-posko.detail.nav-tabs />

    {{-- Main Content Grid --}}
    <div class="w-full">

        {{-- TAB 1: INFORMASI (Layout Grid 8 + 4) --}}
        <div x-show="activeTab === 'informasi'" x-cloak class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            {{-- Kolom Kiri: Informasi Umum, Ringkasan & Kebutuhan (Span 8) --}}
            <div class="lg:col-span-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-sub-posko.detail.info-general :subPosko="$subPosko" />
                    <x-sub-posko.detail.info-summary :subPosko="$subPosko" />
                </div>
                <x-sub-posko.detail.user-table :kebutuhans="$subPosko->kebutuhanMendesak ?? collect()" />
            </div>

            {{-- Kolom Kanan: Peta & Dokumentasi (Span 4) --}}
            <div class="lg:col-span-4 space-y-6">
                <x-sub-posko.detail.mini-map :subPosko="$subPosko" />
                <x-sub-posko.detail.documentation :subPosko="$subPosko" />
            </div>
        </div>

        {{-- TAB 2: LOGISTIK (Layout Full Width 100%) --}}
        <div x-show="activeTab === 'logistik'" x-cloak class="w-full">
            <x-sub-posko.detail.logistik-card :subPosko="$subPosko" />
        </div>

        {{-- TAB 3: DISTRIBUSI (Layout Full Width 100%) --}}
        <div x-show="activeTab === 'distribusi'" x-cloak class="w-full">
            <x-sub-posko.detail.placeholder-tab
                title="Distribusi Logistik"
                message="Data riwayat distribusi logistik yang dikirim ke posko ini belum tersedia." />
        </div>

        {{-- TAB 4: PERMINTAAN (Layout Full Width 100%) --}}
        <div x-show="activeTab === 'permintaan'" x-cloak class="w-full">
            <x-sub-posko.detail.placeholder-tab
                title="Permintaan Logistik"
                message="Belum ada pengajuan permintaan logistik dari posko lapangan ini." />
        </div>

        {{-- TAB 5: RIWAYAT AKTIVITAS (Layout Full Width 100%) --}}
        <div x-show="activeTab === 'riwayat'" x-cloak class="w-full">
            <x-sub-posko.detail.placeholder-tab
                title="Riwayat Aktivitas"
                message="Belum ada riwayat aktivitas yang tercatat untuk posko ini." />
        </div>

    </div>
</div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapWrapper:fullscreen {
            width: 100vw !important;
            height: 100vh !important;
            border-radius: 0 !important;
            background-color: #ffffff;
        }
        #mapWrapper:fullscreen #miniMap {
            height: 100vh !important;
            width: 100vw !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        let mapInstance;

        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $subPosko->latitude ?? -7.7956 }};
            const lng = {{ $subPosko->longitude ?? 110.3695 }};

            const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            });

            const satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri'
            });

            mapInstance = L.map('miniMap', {
                center: [lat, lng],
                zoom: 13,
                layers: [osm]
            });

            const baseMaps = {
                "Peta Jalan": osm,
                "Satelit": satellite
            };

            L.control.layers(baseMaps).addTo(mapInstance);

            @if($subPosko->latitude && $subPosko->longitude)
                L.marker([lat, lng])
                    .addTo(mapInstance)
                    .bindPopup(`
                        <div class="text-xs font-sans">
                            <strong class="font-bold block mb-1">{{ $subPosko->nama_posko }}</strong>
                            <p class="text-slate-500">{{ $subPosko->lokasi }}</p>
                        </div>
                    `)
                    .openPopup();
            @endif
        });

        document.addEventListener('fullscreenchange', function () {
            if (mapInstance) {
                setTimeout(() => {
                    mapInstance.invalidateSize();
                }, 150);
            }
        });

        function toggleMapFullscreen() {
            const mapContainer = document.getElementById('mapWrapper');
            if (!document.fullscreenElement) {
                if (mapContainer.requestFullscreen) {
                    mapContainer.requestFullscreen();
                } else if (mapContainer.webkitRequestFullscreen) {
                    mapContainer.webkitRequestFullscreen();
                }
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                }
            }
        }
    </script>
@endpush