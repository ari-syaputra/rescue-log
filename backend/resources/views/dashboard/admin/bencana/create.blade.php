@extends('layouts.app')

@section('title', 'Inisiasi Bencana Manual - BPBD')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapPicker {
            height: 100%;
            min-height: 420px;
            border-radius: 0.75rem;
            z-index: 10;
        }
    </style>
@endpush

@section('content')
    <div class="w-full space-y-6 pb-12">

        {{-- Alert Notification: Session Error --}}
        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800">&times;</button>
            </div>
        @endif

        {{-- Alert Notification: Validation Errors --}}
        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-medium space-y-1">
                <div class="flex items-center justify-between font-bold text-xs uppercase tracking-wider">
                    <span class="flex items-center gap-1.5">
                        <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-rose-600" />
                        Form Belum Lengkap / Gagal Validasi:
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="text-rose-500 hover:text-rose-800">&times;</button>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Header Page & Tombol Kembali --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-2">
            <div class="flex items-start gap-3">
                <a href="{{ route('admin.bencana') }}"
                    class="p-2 rounded-xl bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all shadow-2xs shrink-0 mt-0.5"
                    title="Kembali ke Pusat Komando Bencana">
                    <x-heroicon-s-arrow-left class="w-5 h-5" />
                </a>

                <div class="space-y-1">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Inisiasi Laporan Bencana Manual</h1>
                    <p class="text-xs text-slate-500">Daftarkan kejadian bencana lokal (kebakaran, banjir, longsor) yang
                        tidak terdeteksi otomatis oleh API BMKG.</p>
                </div>
            </div>
        </div>

        {{-- Form Inisiasi Bencana --}}
        <form action="{{ route('admin.bencana.store-manual') }}" method="POST">
            @csrf

            {{-- Container Utama Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

                {{-- CARD KIRI: Form Detail + Tombol Action (5 Col) --}}
                <div
                    class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between">
                    <div class="flex flex-col h-full space-y-6">
                        <div>
<div class="border-b border-slate-100 pb-4 mb-6">
    <div class="flex items-center gap-2">
        <x-heroicon-s-document-text class="w-5 h-5 text-indigo-600" />
        <h2 class="text-base font-bold text-slate-900">Informasi Insiden Bencana</h2>
    </div>
    <p class="text-xs text-slate-500 mt-1">Lengkapi kategori, sumber laporan, serta deskripsi awal kejadian</p>
</div>

                            <div class="space-y-4">
<div>
    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
        Jenis Bencana <span class="text-rose-500">*</span>
    </label>
    <select name="jenis_bencana" required
        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 transition-all cursor-pointer">
        <option value="" class="cursor-pointer">-- Pilih Jenis Bencana --</option>
        <option value="Kebakaran Pemukiman" class="cursor-pointer" {{ old('jenis_bencana') == 'Kebakaran Pemukiman' ? 'selected' : '' }}>Kebakaran Pemukiman</option>
        <option value="Banjir Bandang" class="cursor-pointer" {{ old('jenis_bencana') == 'Banjir Bandang' ? 'selected' : '' }}>Banjir Bandang</option>
        <option value="Tanah Longsor" class="cursor-pointer" {{ old('jenis_bencana') == 'Tanah Longsor' ? 'selected' : '' }}>Tanah Longsor</option>
        <option value="Puting Beliung" class="cursor-pointer" {{ old('jenis_bencana') == 'Puting Beliung' ? 'selected' : '' }}>Puting Beliung</option>
        <option value="Kegagalan Teknologi / Industri" class="cursor-pointer" {{ old('jenis_bencana') == 'Kegagalan Teknologi / Industri' ? 'selected' : '' }}>Kegagalan Teknologi / Industri</option>
        <option value="Kekeringan Ekstrem" class="cursor-pointer" {{ old('jenis_bencana') == 'Kekeringan Ekstrem' ? 'selected' : '' }}>Kekeringan Ekstrem</option>
        <option value="Gempabumi Lokal" class="cursor-pointer" {{ old('jenis_bencana') == 'Gempabumi Lokal' ? 'selected' : '' }}>Gempabumi Lokal</option>
    </select>
    @error('jenis_bencana')
        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
    @enderror
</div>

  <div>
    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
        Sumber Informasi Laporan <span class="text-rose-500">*</span>
    </label>
    <select name="sumber_laporan" required
        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 transition-all cursor-pointer">
        <option value="Laporan TRC Lapangan" class="cursor-pointer"
            {{ old('sumber_laporan') == 'Laporan TRC Lapangan' ? 'selected' : '' }}>Laporan
            TRC Lapangan</option>
        <option value="Call Center 112 / Emergency" class="cursor-pointer"
            {{ old('sumber_laporan') == 'Call Center 112 / Emergency' ? 'selected' : '' }}>
            Call Center 112 / Emergency</option>
        <option value="Laporan Perangkat Desa / Camat" class="cursor-pointer"
            {{ old('sumber_laporan') == 'Laporan Perangkat Desa / Camat' ? 'selected' : '' }}>
            Laporan Perangkat Desa / Camat</option>
        <option value="Laporan Warga" class="cursor-pointer"
            {{ old('sumber_laporan') == 'Laporan Warga' ? 'selected' : '' }}>Laporan Warga
        </option>
    </select>
    @error('sumber_laporan')
        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
    @enderror
</div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Lokasi / Wilayah Kejadian <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" name="wilayah" value="{{ old('wilayah') }}" required
                                        placeholder="Contoh: Dusun Sukamaju, Kecamatan Sleman"
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                                    @error('wilayah')
                                        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude</label>
                                        <input type="text" id="input_lat" name="latitude" value="{{ old('latitude') }}"
                                            readonly required
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-medium text-slate-600">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude</label>
                                        <input type="text" id="input_lng" name="longitude"
                                            value="{{ old('longitude') }}" readonly required
                                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-medium text-slate-600">
                                    </div>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan
                                        / Deskripsi Singkat</label>
                                    <textarea name="deskripsi" rows="3" placeholder="Jelaskan kondisi awal dampak insiden di lokasi..."
                                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all resize-none">{{ old('deskripsi') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons: Berada di bawah Card Kiri --}}
                        {{-- Action Buttons: Full Width & Proposional --}}
                        <div class="pt-4 grid grid-cols-2 gap-3 border-t border-slate-100 shrink-0 mt-auto">
                            <a href="{{ route('admin.bencana') }}"
                                class="w-full h-11 inline-flex items-center justify-center bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-sm font-semibold rounded-xl focus:ring-4 focus:ring-slate-100 transition shadow-xs cursor-pointer">
                                Batal
                            </a>

                            <button type="submit"
                                class="w-full h-11 inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl hover:shadow-md focus:ring-4 focus:ring-indigo-200 transition shadow-sm cursor-pointer">
                                Daftarkan Insiden
                            </button>
                        </div>
                    </div>
                </div>

                {{-- CARD KANAN: Plotting Interactive Map (7 Col) --}}
                <div
                    class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col space-y-4">
<div class="border-b border-slate-100 pb-4">
    <div class="flex items-center gap-2">
        <x-heroicon-s-map-pin class="w-5 h-5 text-indigo-600" />
        <h2 class="text-base font-bold text-slate-900">Titik Koordinat Bencana</h2>
    </div>
    <p class="text-xs text-slate-500 mt-1">Geser marker atau klik pada peta untuk menentukan posisi presisi</p>
</div>

                    <div class="rounded-xl overflow-hidden border border-slate-200 flex-1 relative min-h-[450px]">
                        <div id="mapPicker"></div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const defaultLat = {{ old('latitude', -7.7956) }};
            const defaultLng = {{ old('longitude', 110.3695) }};

            const map = L.map('mapPicker').setView([defaultLat, defaultLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([defaultLat, defaultLng], {
                draggable: true
            }).addTo(map);
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
