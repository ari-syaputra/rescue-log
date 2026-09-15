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

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('admin.bencana') }}" class="text-xs font-semibold text-slate-500 hover:text-indigo-600 flex items-center gap-1 mb-1.5 transition-colors">
                &larr; Kembali ke Pusat Komando Bencana
            </a>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                {{ isset($pending) ? 'Validasi Deteksi Bencana BMKG' : 'Inisiasi Laporan Bencana Manual' }}
            </h1>
            <p class="text-xs font-medium text-slate-500 mt-0.5">
                {{ isset($pending) ? 'Unggah SK darurat & gambar poligon zona terdampak untuk mengaktifkan operasi bencana.' : 'Daftarkan kejadian bencana lokal & gambar poligon dampak untuk kalkulasi demografi otomatis.' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
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

    <form action="{{ route('admin.bencana.store-manual') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf

        @if(isset($pending))
            <input type="hidden" name="pending_id" value="{{ $pending->id }}">
        @endif

        <input type="hidden" id="input_geojson_polygon" name="geojson_polygon">

        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-4">
            <h3 class="text-sm font-extrabold text-slate-900 pb-3 border-b border-slate-100 flex items-center justify-between">
                <span>📋 Informasi Insiden Bencana</span>
                @if(isset($pending))
                    <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2 py-0.5 rounded">BMKG AUTO</span>
                @endif
            </h3>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Bencana <span class="text-rose-500">*</span></label>
                @if(isset($pending))
                    <input type="text" name="jenis_bencana" value="{{ $pending->jenis_bencana }}" readonly class="w-full text-xs font-bold border-slate-200 rounded-xl bg-slate-100/80 text-slate-700 p-2.5">
                @else
                    <select name="jenis_bencana" required class="w-full text-xs font-medium border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 p-2.5 bg-slate-50/50">
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
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Sumber Informasi Laporan <span class="text-rose-500">*</span></label>
                @if(isset($pending))
                    <input type="text" name="sumber_laporan" value="Integrasi Otomatis BMKG API" readonly class="w-full text-xs font-bold border-slate-200 rounded-xl bg-slate-100/80 text-slate-700 p-2.5">
                @else
                    <select name="sumber_laporan" required class="w-full text-xs font-medium border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 p-2.5 bg-slate-50/50">
                        <option value="Laporan TRC Lapangan">Laporan TRC Lapangan</option>
                        <option value="Call Center 112 / Emergency">Call Center 112 / Emergency</option>
                        <option value="Laporan Perangkat Desa / Camat">Laporan Perangkat Desa / Camat</option>
                        <option value="Laporan Warga">Laporan Warga</option>
                    </select>
                @endif
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Lokasi / Wilayah Kejadian <span class="text-rose-500">*</span></label>
                <input type="text" name="wilayah" value="{{ $pending->wilayah ?? '' }}" required placeholder="Contoh: Dusun Sukamaju, Kecamatan Sleman" class="w-full text-xs font-medium border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 p-2.5 bg-slate-50/50">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Latitude Pusat</label>
                    <input type="text" id="input_lat" name="latitude" value="{{ $pending->latitude ?? '' }}" readonly required class="w-full text-xs font-mono font-bold bg-slate-100/70 border-slate-200 rounded-xl text-slate-600 p-2.5">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Longitude Pusat</label>
                    <input type="text" id="input_lng" name="longitude" value="{{ $pending->longitude ?? '' }}" readonly required class="w-full text-xs font-mono font-bold bg-slate-100/70 border-slate-200 rounded-xl text-slate-600 p-2.5">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Dokumen SK Status Darurat (PDF/Gambar) <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="sk_status_darurat" required accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 border border-slate-200 rounded-xl file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 bg-slate-50/50 p-1.5">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Catatan / Deskripsi Singkat</label>
                <textarea name="deskripsi" rows="2" placeholder="Jelaskan kondisi awal dampak insiden di lokasi..." class="w-full text-xs font-medium border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 p-2.5 bg-slate-50/50">{{ $pending->deskripsi ?? '' }}</textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all cursor-pointer flex items-center justify-center gap-2">
                    <span>🚀</span> {{ isset($pending) ? 'Validasi SK & Aktifkan Posko' : 'Daftarkan Insiden ke Pusat Komando' }}
                </button>
            </div>
        </div>

        <div class="lg:col-span-7 space-y-4">
            
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col space-y-3">
                <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                            <span>📍</span> Plotting Titik & Area Bencana
                        </h3>
                        <p id="instructionText" class="text-[11px] font-semibold text-blue-600 mt-0.5">
                            {{ isset($pending) ? 'Langkah 2: Titik BMKG dikunci. Klik tombol "Gambar Poligon Area".' : 'Langkah 1: Klik pada peta untuk tentukan lokasi pusat bencana.' }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" id="btnLockPoint" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl {{ isset($pending) ? 'hidden' : 'flex' }} items-center gap-1.5 transition-all cursor-pointer shadow-2xs">
                            <span>📌</span> Kunci Titik Bencana
                        </button>

                        <button type="button" id="btnStartDraw" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold text-xs rounded-xl {{ isset($pending) ? 'flex' : 'hidden' }} items-center gap-1.5 transition-all cursor-pointer">
                            <span>📐</span> Gambar Poligon Area
                        </button>

                        <button type="button" id="btnFinishDraw" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl hidden items-center gap-1.5 transition-all cursor-pointer shadow-2xs animate-pulse">
                            <span>✅</span> Selesai Poligon
                        </button>

                        <button type="button" id="btnResetMap" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl {{ isset($pending) ? 'flex' : 'hidden' }} transition-all cursor-pointer">
                            🔄 Reset Peta
                        </button>
                    </div>
                </div>

                <div id="mapPicker" class="w-full shadow-inner border border-slate-200/90 rounded-xl overflow-hidden"></div>
            </div>

            <div id="cardDemografi" class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-2">
                        <span>🧠</span> Smart Demographics Estimator (Spatial Overlay)
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
        const btnFinishDraw  = document.getElementById('btnFinishDraw');
        const btnResetMap    = document.getElementById('btnResetMap');

        btnLockPoint.addEventListener('click', () => {
            isPointLocked = true;
            marker.dragging.disable();
            
            instructionText.textContent = "Langkah 2: Titik dikunci. Klik 'Gambar Poligon Area' untuk menandai kawasan terdampak.";
            instructionText.className = "text-[11px] font-semibold text-emerald-600 mt-0.5";

            btnLockPoint.classList.add('hidden');
            btnStartDraw.classList.remove('hidden');
            btnStartDraw.classList.add('flex');
            btnResetMap.classList.remove('hidden');
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

            const latLngs = layer.getLatLngs()[0];
            const coordinatesArray = latLngs.map(point => [point.lat, point.lng]);

            document.getElementById('input_geojson_polygon').value = JSON.stringify(coordinatesArray);

            instructionText.textContent = "✓ Selesai: Titik lokasi dan poligon area terdampak berhasil dibuat!";
            instructionText.className = "text-[11px] font-bold text-emerald-600 mt-0.5";

            btnFinishDraw.classList.add('hidden');
            btnStartDraw.classList.remove('hidden');
            btnStartDraw.textContent = "📐 Gambar Ulang Poligon";

            calculateSpatialData(coordinatesArray);
        });

        btnResetMap.addEventListener('click', () => {
            isPointLocked = isPendingFromBmkg;
            if (!isPendingFromBmkg) marker.dragging.enable();

            drawnItems.clearLayers();
            document.getElementById('input_geojson_polygon').value = '';

            if (polygonDrawer) polygonDrawer.disable();

            resetDemographicsCard();

            if (isPendingFromBmkg) {
                instructionText.textContent = "Langkah 2: Titik BMKG dikunci. Klik tombol 'Gambar Poligon Area'.";
                instructionText.className = "text-[11px] font-semibold text-blue-600 mt-0.5";
                btnStartDraw.classList.remove('hidden');
                btnStartDraw.classList.add('flex');
            } else {
                instructionText.textContent = "Langkah 1: Klik pada peta untuk tentukan lokasi pusat bencana.";
                instructionText.className = "text-[11px] font-semibold text-blue-600 mt-0.5";
                btnLockPoint.classList.remove('hidden');
                btnStartDraw.classList.add('hidden');
            }

            btnFinishDraw.classList.add('hidden');
            btnStartDraw.textContent = "📐 Gambar Poligon Area";
        });

        function calculateSpatialData(coords) {
            document.getElementById('badgeSpatialStatus').textContent = "⚡ Menghitung Spasial...";
            document.getElementById('badgeSpatialStatus').className = "text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-1 rounded-full";

            fetch("{{ route('admin.bencana.calculate-spatial') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ coordinates: coords })
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