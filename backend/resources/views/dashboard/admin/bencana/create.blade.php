@extends('layouts.app')

@section('title', 'Inisiasi & Validasi Spasial Bencana - BPBD')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />
<style>
    #mapPicker {
        height: 480px;
        border-radius: 1rem;
        z-index: 10;
    }
    .leaflet-draw-toolbar {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
        <div class="flex items-start gap-3">
            <a href="{{ route('admin.bencana') }}"
                class="p-2 rounded-xl bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all shadow-xs shrink-0 mt-0.5"
                title="Kembali ke Pusat Komando Bencana">
                <x-heroicon-s-arrow-left class="w-5 h-5" />
            </a>

            <div class="space-y-1">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    {{ isset($pending) ? 'Validasi Deteksi Bencana BMKG' : 'Inisiasi Laporan Bencana Manual' }}
                </h1>
                <p class="text-xs text-slate-500">
                    {{ isset($pending) ? 'Unggah SK darurat & gambar poligon zona terdampak untuk mengaktifkan operasi bencana.' : 'Daftarkan kejadian bencana lokal & gambar poligon dampak untuk kalkulasi demografi otomatis.' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2 self-start md:self-auto">
            @if(isset($pending))
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 mr-2 bg-amber-500 rounded-full animate-pulse"></span>
                    BMKG Auto-Detected Item
                </span>
            @else
                <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                    <span class="w-2 h-2 mr-2 bg-indigo-500 rounded-full animate-pulse"></span>
                    GIS Spatial Estimator Active
                </span>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.bencana.store-manual') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        @csrf

        @if(isset($pending))
            <input type="hidden" name="pending_id" value="{{ $pending->id }}">
        @endif

        <input type="hidden" id="input_geojson_polygon" name="geojson_polygon">

        <!-- Card Informasi Insiden Bencana (Sisi Kiri) -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
            <div class="flex flex-col h-full">
                
                <div class="border-b border-slate-100 pb-4 mb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5 text-indigo-600" />
                            Informasi Insiden Bencana
                        </h2>
                        <p class="text-xs text-slate-500 mt-0.5">Detail identitas dan dokumen legalitas bencana</p>
                    </div>
                    @if(isset($pending))
                        <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-md">BMKG AUTO</span>
                    @endif
                </div>

                <div class="space-y-4 flex-1 flex flex-col">
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Jenis Bencana <span class="text-rose-500">*</span>
                            </label>
                            @if(isset($pending))
                                <input type="text" name="jenis_bencana" value="{{ $pending->jenis_bencana }}" readonly 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100/80 text-sm font-bold text-slate-700 cursor-not-allowed">
                            @else
                                <select name="jenis_bencana" required 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 transition-all bg-slate-50/50 cursor-pointer">
                                    <option value="">-- Pilih Jenis Bencana --</option>
                                    <option value="Kebakaran Pemukiman">Kebakaran Pemukiman</option>
                                    <option value="Banjir Bandang">Banjir Bandang</option>
                                    <option value="Tanah Longsor">Tanah Longsor</option>
                                    <option value="Puting Beliung">Puting Beliung</option>
                                    <option value="Kegagalan Teknologi / Industri">Kegagalan Teknologi / Industri</option>
                                    <option value="Kekeringan Ekstrem">Kekeringan Ekstrem</option>
                                    <option value="Gempabumi Lokal">Gempabumi Lokal</option>
                                </select>
                            @endif
                            @error('jenis_bencana')
                                <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Sumber Informasi <span class="text-rose-500">*</span>
                            </label>
                            @if(isset($pending))
                                <input type="text" name="sumber_laporan" value="Integrasi Otomatis BMKG API" readonly 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100/80 text-sm font-bold text-slate-700 cursor-not-allowed">
                            @else
                                <select name="sumber_laporan" required 
                                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 transition-all bg-slate-50/50 cursor-pointer">
                                    <option value="Laporan TRC Lapangan">Laporan TRC Lapangan</option>
                                    <option value="Call Center 112 / Emergency">Call Center 112 / Emergency</option>
                                    <option value="Laporan Perangkat Desa / Camat">Laporan Perangkat Desa / Camat</option>
                                    <option value="Laporan Warga">Laporan Warga</option>
                                </select>
                            @endif
                            @error('sumber_laporan')
                                <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Wilayah Kejadian <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="wilayah" value="{{ old('wilayah', $pending->wilayah ?? '') }}" required 
                            placeholder="Contoh: Dusun Sukamaju, Kecamatan Sleman" 
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all">
                        @error('wilayah')
                            <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Latitude Pusat
                            </label>
                            <input type="text" id="input_lat" name="latitude" value="{{ old('latitude', $pending->latitude ?? '') }}" readonly required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100/70 text-sm font-mono font-bold text-slate-700">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                                Longitude Pusat
                            </label>
                            <input type="text" id="input_lng" name="longitude" value="{{ old('longitude', $pending->longitude ?? '') }}" readonly required 
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-slate-100/70 text-sm font-mono font-bold text-slate-700">
                        </div>
                    </div>

<div>
    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5 cursor-pointer">
        Dokumen SK Status Darurat (PDF/Gambar) <span class="text-rose-500">*</span>
    </label>
    <input type="file" name="sk_status_darurat" required accept=".pdf,.jpg,.jpeg,.png" 
        class="w-full text-xs text-slate-500 border border-slate-200 rounded-xl file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer bg-slate-50/50 p-1.5 cursor-pointer">
    @error('sk_status_darurat')
        <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
    @enderror
</div>

                    <!-- Textarea Catatan -->
                    <div class="flex-1 flex flex-col min-h-[100px]">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Catatan / Deskripsi Singkat
                        </label>
                        <textarea name="deskripsi" placeholder="Jelaskan kondisi awal dampak insiden di lokasi..." 
                            class="w-full h-full flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-500/20 text-sm font-medium text-slate-800 placeholder-slate-400 transition-all resize-none">{{ old('deskripsi', $pending->deskripsi ?? '') }}</textarea>
                        @error('deskripsi')
                            <span class="text-xs text-rose-500 mt-1.5 block font-medium">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                            <x-heroicon-s-paper-airplane class="w-4 h-4" />
                            <span>{{ isset($pending) ? 'Validasi SK & Aktifkan Posko' : 'Daftarkan Insiden ke Pusat Komando' }}</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Section Peta & Demografi Spasial (Sisi Kanan) -->
        <div class="lg:col-span-7 space-y-4 flex flex-col justify-between">
            
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            <x-heroicon-o-map-pin class="w-5 h-5 text-indigo-600" />
                            Plotting Titik dan Area Bencana
                        </h3>
                        <p id="instructionText" class="text-[11px] font-semibold text-blue-600 mt-0.5">
                            {{ isset($pending) ? 'Langkah 2: Titik BMKG dikunci. Klik tombol "Gambar Poligon Area".' : 'Langkah 1: Klik pada peta untuk tentukan lokasi pusat bencana.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <button type="button" id="btnLockPoint" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl {{ isset($pending) ? 'hidden' : 'flex' }} items-center gap-2 transition-all cursor-pointer shadow-xs">
                            <x-heroicon-s-lock-closed class="w-4 h-4" />
                            Kunci Titik Bencana
                        </button>

                        <button type="button" id="btnStartDraw" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl {{ isset($pending) ? 'flex' : 'hidden' }} items-center gap-2 transition-all cursor-pointer shadow-xs">
                            <x-heroicon-o-pencil-square class="w-4 h-4" />
                            <span id="btnStartDrawText">Gambar Poligon Area</span>
                        </button>

                        <button type="button" id="btnFinishDraw" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl hidden items-center gap-2 transition-all cursor-pointer shadow-xs">
                            <x-heroicon-s-check-circle class="w-4 h-4" />
                            Selesai Poligon
                        </button>

                        <button type="button" id="btnResetMap" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl hidden items-center gap-2 transition-all cursor-pointer">
                            <x-heroicon-o-arrow-path class="w-4 h-4" />
                            Reset Peta
                        </button>
                    </div>
                </div>

                <div id="mapPicker" class="w-full shadow-inner border border-slate-200/90 rounded-xl overflow-hidden"></div>
            </div>

            <div id="cardDemografi" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <x-heroicon-o-cpu-chip class="w-4 h-4 text-indigo-600" />
                        Smart Demographics Estimator
                    </h4>
                    <span id="badgeSpatialStatus" class="text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 px-2.5 py-1 rounded-full">
                        Menunggu Titik & Poligon...
                    </span>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center pt-1">
                    <div class="bg-slate-50/80 border border-slate-200/60 p-3 rounded-xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Luas Dampak</span>
                        <p class="text-lg font-black text-emerald-600 mt-0.5"><span id="valLuasArea">0</span> <span class="text-xs font-normal text-slate-500">km²</span></p>
                    </div>

                    <div class="bg-slate-50/80 border border-slate-200/60 p-3 rounded-xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Estimasi Jiwa</span>
                        <p class="text-lg font-black text-indigo-600 mt-0.5"><span id="valTotalJiwa">0</span> <span class="text-xs font-normal text-slate-500">Jiwa</span></p>
                    </div>

                    <div class="bg-slate-50/80 border border-slate-200/60 p-3 rounded-xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Estimasi KK</span>
                        <p class="text-lg font-black text-amber-600 mt-0.5"><span id="valTotalKK">0</span> <span class="text-xs font-normal text-slate-500">KK</span></p>
                    </div>

                    <div class="bg-slate-50/80 border border-slate-200/60 p-3 rounded-xl">
                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block">Estimasi Bangunan</span>
                        <p class="text-lg font-black text-rose-600 mt-0.5"><span id="valTotalBangunan">0</span> <span class="text-xs font-normal text-slate-500">Unit</span></p>
                    </div>
                </div>
            </div>

        </div>

    </form>

</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const isPendingFromBmkg = @json(isset($pending));
        
        const defaultLat = isPendingFromBmkg ? {{ $pending->latitude ?? -7.7956 }} : -7.7956;
        const defaultLng = isPendingFromBmkg ? {{ $pending->longitude ?? 110.3695 }} : 110.3695;

        const map = L.map('mapPicker').setView([defaultLat, defaultLng], isPendingFromBmkg ? 14 : 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng], { draggable: !isPendingFromBmkg }).addTo(map);
        updateInputs(defaultLat, defaultLng);

        let isPointLocked = isPendingFromBmkg;

        marker.on('dragend', function() {
            if (!isPointLocked) {
                const pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            }
        });

        map.on('click', function(e) {
            if (!isPointLocked) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            }
        });

        function updateInputs(lat, lng) {
            document.getElementById('input_lat').value = parseFloat(lat).toFixed(6);
            document.getElementById('input_lng').value = parseFloat(lng).toFixed(6);
        }

        const drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        let polygonDrawer = null;

        const instructionText = document.getElementById('instructionText');
        const btnLockPoint    = document.getElementById('btnLockPoint');
        const btnStartDraw   = document.getElementById('btnStartDraw');
        const btnStartDrawText = document.getElementById('btnStartDrawText');
        const btnFinishDraw  = document.getElementById('btnFinishDraw');
        const btnResetMap    = document.getElementById('btnResetMap');

        btnLockPoint.addEventListener('click', () => {
            isPointLocked = true;
            marker.dragging.disable();
            
            instructionText.textContent = "Langkah 2: Titik dikunci. Klik 'Gambar Poligon Area' untuk menandai kawasan terdampak.";
            instructionText.className = "text-[11px] font-semibold text-emerald-600 mt-0.5";

            btnLockPoint.classList.add('hidden');
            btnLockPoint.classList.remove('flex');

            btnStartDraw.classList.remove('hidden');
            btnStartDraw.classList.add('flex');
            
            btnResetMap.classList.remove('hidden');
            btnResetMap.classList.add('flex');
        });

        btnStartDraw.addEventListener('click', () => {
            drawnItems.clearLayers();
            
            polygonDrawer = new L.Draw.Polygon(map, {
                allowIntersection: false,
                showArea: true,
                shapeOptions: {
                    color: '#ef4444',
                    fillColor: '#ef4444',
                    fillOpacity: 0.3
                }
            });

            polygonDrawer.enable();

            instructionText.textContent = "Langkah 3: Klik pada peta untuk membentuk sudut poligon. Klik 'Selesai Poligon' jika sudah membentuk area.";
            instructionText.className = "text-[11px] font-semibold text-amber-600 mt-0.5 animate-pulse";

            btnStartDraw.classList.add('hidden');
            btnStartDraw.classList.remove('flex');

            btnFinishDraw.classList.remove('hidden');
            btnFinishDraw.classList.add('flex');
        });

        btnFinishDraw.addEventListener('click', () => {
            if (polygonDrawer) {
                polygonDrawer.completeShape();
            }
        });

        map.on(L.Draw.Event.CREATED, function (e) {
            const layer = e.layer;
            drawnItems.clearLayers();
            drawnItems.addLayer(layer);

            const geoJsonData = layer.toGeoJSON();
            document.getElementById('input_geojson_polygon').value = JSON.stringify(geoJsonData.geometry);

            instructionText.textContent = "✓ Selesai: Titik lokasi dan poligon area terdampak berhasil dibuat!";
            instructionText.className = "text-[11px] font-bold text-emerald-600 mt-0.5";

            btnFinishDraw.classList.add('hidden');
            btnFinishDraw.classList.remove('flex');

            btnStartDraw.classList.remove('hidden');
            btnStartDraw.classList.add('flex');
            if (btnStartDrawText) btnStartDrawText.textContent = "Gambar Ulang Poligon";

            calculateSpatialData(geoJsonData.geometry);
        });

        // Event listener saat tombol Reset Peta diklik
        btnResetMap.addEventListener('click', () => {
            isPointLocked = false;
            marker.dragging.enable();

            drawnItems.clearLayers();
            document.getElementById('input_geojson_polygon').value = '';

            if (polygonDrawer) polygonDrawer.disable();

            resetDemographicsCard();

            instructionText.textContent = "Langkah 1: Klik pada peta untuk tentukan lokasi pusat bencana.";
            instructionText.className = "text-[11px] font-semibold text-blue-600 mt-0.5";

            // Munculkan kembali tombol orange "Kunci Titik Bencana"
            btnLockPoint.classList.remove('hidden');
            btnLockPoint.classList.add('flex');

            // Sembunyikan tombol lainnya
            btnStartDraw.classList.add('hidden');
            btnStartDraw.classList.remove('flex');

            btnFinishDraw.classList.add('hidden');
            btnFinishDraw.classList.remove('flex');

            btnResetMap.classList.add('hidden');
            btnResetMap.classList.remove('flex');

            if (btnStartDrawText) btnStartDrawText.textContent = "Gambar Poligon Area";
        });

        function calculateSpatialData(geoJsonGeometry) {
            document.getElementById('badgeSpatialStatus').textContent = "⚡ Menghitung Spasial...";
            document.getElementById('badgeSpatialStatus').className = "text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full";

            fetch("{{ route('admin.bencana.calculate-spatial') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ geojson: geoJsonGeometry })
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {
                    const d = res.data;
                    document.getElementById('valLuasArea').textContent = d.luas_area_km2;
                    document.getElementById('valTotalJiwa').textContent = d.total_jiwa_terdampak.toLocaleString('id-ID');
                    document.getElementById('valTotalKK').textContent = d.total_kk_terdampak.toLocaleString('id-ID');
                    document.getElementById('valTotalBangunan').textContent = d.total_bangunan_terdampak.toLocaleString('id-ID');

                    document.getElementById('badgeSpatialStatus').textContent = "✓ Spasial Terkalkulasi";
                    document.getElementById('badgeSpatialStatus').className = "text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full";
                }
            })
            .catch(err => console.error("Spatial calculation error:", err));
        }

        function resetDemographicsCard() {
            document.getElementById('valLuasArea').textContent = '0';
            document.getElementById('valTotalJiwa').textContent = '0';
            document.getElementById('valTotalKK').textContent = '0';
            document.getElementById('valTotalBangunan').textContent = '0';

            document.getElementById('badgeSpatialStatus').textContent = "Menunggu Titik & Poligon...";
            document.getElementById('badgeSpatialStatus').className = "text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200/80 px-2.5 py-1 rounded-full";
        }
    });
</script>
@endpush