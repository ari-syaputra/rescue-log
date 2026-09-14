@extends('layouts.app')

@section('title', 'Inisiasi Bencana Manual - BPBD')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #mapPicker {
        height: 500px;
        border-radius: 1rem;
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header & Navigation Back -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.bencana') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1 mb-1 transition-colors">
                &larr; Kembali ke Pusat Komando Bencana
            </a>
            <h1 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                <span class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
                Inisiasi Laporan Bencana Manual
            </h1>
            <p class="text-xs text-slate-500 mt-1">Daftarkan kejadian bencana lokal (kebakaran, banjir, longsor) yang tidak terdeteksi otomatis oleh API BMKG.</p>
        </div>
    </div>

    <!-- Main Grid Form & Interactive Map -->
    <form action="{{ route('admin.bencana.store-manual') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        <!-- SISI KIRI: Form Input Detail Insiden (5 COL) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-800 pb-2 border-b border-slate-100 flex items-center gap-2">
                📋 Informasi Insiden Bencana
            </h3>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Jenis Bencana</label>
                <select name="jenis_bencana" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
                    <option value="">-- Pilih Jenis Bencana --</option>
                    <option value="Kebakaran Pemukiman">Kebakaran Pemukiman</option>
                    <option value="Banjir Bandang">Banjir Bandang</option>
                    <option value="Tanah Longsor">Tanah Longsor</option>
                    <option value="Puting Beliung">Puting Beliung</option>
                    <option value="Kegagalan Teknologi / Industri">Kegagalan Teknologi / Industri</option>
                    <option value="Kekeringan Ekstrem">Kekeringan Ekstrem</option>
                    <option value="Gempabumi Lokal">Gempabumi Lokal</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Sumber Informasi Laporan</label>
                <select name="sumber_laporan" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
                    <option value="Laporan TRC Lapangan">Laporan TRC Lapangan</option>
                    <option value="Call Center 112 / Emergency">Call Center 112 / Emergency</option>
                    <option value="Laporan Perangkat Desa / Camat">Laporan Perangkat Desa / Camat</option>
                    <option value="Laporan Warga">Laporan Warga</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Lokasi / Wilayah Kejadian</label>
                <input type="text" name="wilayah" required placeholder="Contoh: Dusun Sukamaju, Kecamatan Sleman" class="w-full text-sm border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2.5">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Latitude</label>
                    <input type="text" id="input_lat" name="latitude" readonly required class="w-full text-sm bg-slate-50 border-slate-200 rounded-xl font-mono text-slate-600 p-2.5">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Longitude</label>
                    <input type="text" id="input_lng" name="longitude" readonly required class="w-full text-sm bg-slate-50 border-slate-200 rounded-xl font-mono text-slate-600 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan / Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="3" placeholder="Jelaskan kondisi awal dampak insiden di lokasi..." class="w-full text-sm border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 p-2.5"></textarea>
            </div>

            <button type="submit" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition-all cursor-pointer">
                🚀 Daftarkan Insiden ke Pusat Komando
            </button>
        </div>

        <!-- SISI KANAN: Interactive Leaflet GIS Picker (7 COL) -->
        <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    📍 Plotting Titik Koordinat Bencana
                </h3>
                <span class="text-xs text-indigo-600 font-semibold bg-indigo-50 px-2.5 py-1 rounded-lg">Klik pada peta untuk geser titik</span>
            </div>

            <div id="mapPicker" class="w-full shadow-inner border border-slate-200"></div>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const defaultLat = -7.7956;
        const defaultLng = 110.3695;

        const map = L.map('mapPicker').setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
        updateInputs(defaultLat, defaultLng);

        marker.on('dragend', function() {
            const pos = marker.getLatLng();
            updateInputs(pos.lat, pos.lng);
        });

        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateInputs(e.latlng.lat, e.latlng.lng);
        });

        function updateInputs(lat, lng) {
            document.getElementById('input_lat').value = parseFloat(lat).toFixed(6);
            document.getElementById('input_lng').value = parseFloat(lng).toFixed(6);
        }
    });
</script>
@endpush