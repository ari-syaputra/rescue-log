@extends('layouts.app')

@section('title', 'Control Center Distribusi & Rute - SiGap BPBD')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
        }

        #map {
            height: 520px;
            border-radius: 1rem;
            z-index: 10;
        }

        .custom-pulse-marker {
            position: relative;
        }

        .custom-pulse-marker::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(239, 68, 68, 0.4);
            border-radius: 50%;
            animation: pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                opacity: 0.8;
            }

            100% {
                transform: scale(2.4);
                opacity: 0;
            }
        }

        /* Sembunyikan panel teks instruksi routing bawaan agar peta tetap bersih */
        .leaflet-routing-container {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()"
                    class="text-rose-500 hover:text-rose-800 font-bold">&times;</button>
            </div>
        @endif

        <x-komando.distribusi.header />

        <x-komando.distribusi.ai-banner :active-kendala-count="$kendalaJalans->where('is_active', true)->count()" />

        <x-komando.distribusi.stats :siap-kirim-count="$pengajuanSiapKirim->count()" :dalam-perjalanan-count="$pengirimans->whereIn('status_pengiriman', ['dijadwalkan', 'dalam_perjalanan'])->count()" :armada-count="$armadas->count()" :hambatan-count="$kendalaJalans->where('is_active', true)->count()" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <div class="lg:col-span-5 space-y-4">
                <x-komando.distribusi.active-shipments :pengirimans="$pengirimans" :pengajuan-siap-kirim="$pengajuanSiapKirim" :armadas="$armadas" />
            </div>

            <x-komando.distribusi.peta-situasi class="lg:col-span-7" />

        </div>

        <x-komando.distribusi.kendala-table :kendala-jalans="$kendalaJalans" />

    </div>

    <x-komando.distribusi.modal-kendala />

    <x-komando.distribusi.modal-registrasi-armada />

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        let mainMap, modalMap, pickerMarker;
        let routingControl = null;
        let hazardPolyline = null;
        let fallbackPolyline = null;

        // Data Real dari Backend
        const currentPosko = @json($posko ?? null);
        const bpbdInduk = @json($bpbd ?? null);
        const bencanaAktif = @json($bencana ?? null);
        const subPoskoList = @json($subPoskoList ?? []);
        const kendalaData = @json($kendalaJalans ?? []);

        // KOORDINAT UTAMA POSKO KOMANDO (KUNCI TITIK ASAL)
        const poskoKomandoLat = (currentPosko && currentPosko.latitude) ? parseFloat(currentPosko.latitude) : 
                               ((bpbdInduk && bpbdInduk.latitude) ? parseFloat(bpbdInduk.latitude) : -7.8893);
        const poskoKomandoLng = (currentPosko && currentPosko.longitude) ? parseFloat(currentPosko.longitude) : 
                               ((bpbdInduk && bpbdInduk.longitude) ? parseFloat(bpbdInduk.longitude) : 110.3288);

        function openArmadaModal() {
            const modal = document.getElementById('armadaModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeArmadaModal() {
            const modal = document.getElementById('armadaModal');
            if (modal) modal.classList.add('hidden');
        }

        function isHardBlocker(jenisInput, deskripsiInput) {
            const text = ((jenisInput || '') + ' ' + (deskripsiInput || '')).toLowerCase();
            const hardKeywords = [
                'jembatan_putus', 'jembatan putus', 'putus',
                'longsor_total', 'longsor total', 'terputus',
                'ambrol', 'roboh', 'banjir_bandang', 'jalan_putus', 'jalan putus'
            ];
            return hardKeywords.some(key => text.includes(key));
        }

        /**
         * 🧠 SMART ROUTING ENGINE (HANDLES DETOUR + OSRM FALLBACK SAFE)
         */
        function calculateSmartRoute(startLat, startLng, destLat, destLng, namaPoskoTujuan) {
            if (!mainMap) return;

            // Clean-up rute & layer lama
            if (routingControl) { 
                try { mainMap.removeControl(routingControl); } catch(e){} 
                routingControl = null; 
            }
            if (hazardPolyline) { 
                try { mainMap.removeLayer(hazardPolyline); } catch(e){} 
                hazardPolyline = null; 
            }
            if (fallbackPolyline) { 
                try { mainMap.removeLayer(fallbackPolyline); } catch(e){} 
                fallbackPolyline = null; 
            }

            const sLat = (!startLat || isNaN(parseFloat(startLat)) || parseFloat(startLat) === 0) ? poskoKomandoLat : parseFloat(startLat);
            const sLng = (!startLng || isNaN(parseFloat(startLng)) || parseFloat(startLng) === 0) ? poskoKomandoLng : parseFloat(startLng);
            const dLat = parseFloat(destLat);
            const dLng = parseFloat(destLng);

            const startLatLng = L.latLng(sLat, sLng);
            const destLatLng = L.latLng(dLat, dLng);

            // Filter Kendala Aktif
            const activeHazards = kendalaData
                .filter(item => item.is_active)
                .map(item => {
                    const isBlocked = isHardBlocker(item.jenis_kendala, item.deskripsi);
                    return {
                        lat: parseFloat(item.latitude),
                        lng: parseFloat(item.longitude),
                        nama: item.nama_lokasi,
                        jenis: item.jenis_kendala ? item.jenis_kendala.replace(/_/g, ' ') : 'Kendala Jalan',
                        deskripsi: item.deskripsi || '',
                        isBlocked: isBlocked,
                        radius: isBlocked ? 0.005 : 0.002
                    };
                });

            const tempRouter = L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'car',
                useHints: false
            });

            // 1. Cek Rute Langsung Pertama Kali
            tempRouter.route([
                { latLng: startLatLng },
                { latLng: destLatLng }
            ], function(err, routes) {

                // Jika OSRM Gagal / Timeout -> Gambar Fallback Direct Line
                if (err || !routes || routes.length === 0) {
                    console.warn("OSRM Server tidak merespons atau rute tidak ditemukan. Menggunakan Fallback Direct Route.");
                    renderFallbackDirectRoute(startLatLng, destLatLng, namaPoskoTujuan);
                    return;
                }

                const directRoute = routes[0];
                const initialDistance = directRoute.summary.totalDistance;
                const initialTime = directRoute.summary.totalTime;

                let hardHazardHit = null;
                let softHazardHit = null;
                let hazardCoords = [];

                // Evaluasi apakah rute menembus titik kendala
                for (let coord of directRoute.coordinates) {
                    for (let hazard of activeHazards) {
                        let dist = Math.hypot(coord.lat - hazard.lat, coord.lng - hazard.lng);
                        if (dist < hazard.radius) {
                            if (hazard.isBlocked) {
                                hardHazardHit = hazard;
                            } else {
                                softHazardHit = hazard;
                            }
                            hazardCoords.push([coord.lat, coord.lng]);
                        }
                    }
                }

                let waypoints = [startLatLng, destLatLng];
                let isDetoured = false;

                // 🔴 KONDISI MEMUTAR: RUTE MENEMBUS JALAN TERPUTUS / ZONA MERAH
                if (hardHazardHit) {
                    isDetoured = true;
                    const hLat = hardHazardHit.lat;
                    const hLng = hardHazardHit.lng;

                    // Buat waypoint pengalihan yang lebih rasional ke arah timur
                    const detourPt = L.latLng(hLat + 0.008, hLng + 0.012);

                    waypoints = [
                        startLatLng,
                        detourPt,
                        destLatLng
                    ];
                }

                if (!hardHazardHit && softHazardHit && hazardCoords.length > 0) {
                    hazardPolyline = L.polyline(hazardCoords, {
                        color: '#f59e0b', weight: 12, opacity: 0.45, dashArray: '8, 8'
                    }).addTo(mainMap);
                }

                const namaPoskoKomando = currentPosko ? currentPosko.nama_posko : "Posko Komando Utama";

                // Cobalah Render Waypoints Baru
                try {
                    routingControl = L.Routing.control({
                        waypoints: waypoints,
                        router: tempRouter,
                        lineOptions: {
                            styles: [
                                { color: '#ffffff', opacity: 0.9, weight: 8 },
                                { color: isDetoured ? '#dc2626' : (softHazardHit ? '#d97706' : '#2563eb'), opacity: 0.9, weight: 5 }
                            ]
                        },
                        addWaypoints: true,
                        draggableWaypoints: true,
                        fitSelectedRoutes: true,
                        show: false,
                        createMarker: function(i, wp, n) {
                            if (i === 0) {
                                return L.marker(wp.latLng, { draggable: true }).bindPopup(`<b>🏢 Titik Asal:</b> ${namaPoskoKomando}`);
                            }
                            if (i === n - 1) {
                                return L.marker(wp.latLng, { draggable: true }).bindPopup(`<b>⛺ Tujuan Sub-Posko:</b> ${namaPoskoTujuan}`);
                            }
                            return L.marker(wp.latLng, {
                                draggable: true,
                                icon: L.divIcon({
                                    className: 'custom-waypoint-icon',
                                    html: '<div style="background-color: #dc2626; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.4);"></div>',
                                    iconSize: [14, 14],
                                    iconAnchor: [7, 7]
                                })
                            }).bindPopup(`<b>🔀 Titik Pengalihan Rute (Detour)</b><br><small>Menghindari jalan terputus / zona merah</small>`);
                        }
                    }).addTo(mainMap);

                    routingControl.on('routesfound', function(e) {
                        const summary = e.routes[0].summary;
                        let finalDistanceMeters = summary.totalDistance;
                        let finalTimeSeconds = summary.totalTime;

                        let timePenaltyMin = 0;
                        if (!isDetoured && softHazardHit) {
                            timePenaltyMin = 12;
                            finalTimeSeconds += (timePenaltyMin * 60);
                        }

                        const distanceKm = (finalDistanceMeters / 1000).toFixed(1);
                        const timeMin = Math.round(finalTimeSeconds / 60);
                        const diffDistanceKm = ((finalDistanceMeters - initialDistance) / 1000).toFixed(1);
                        const diffTimeMin = Math.round((finalTimeSeconds - initialTime) / 60);

                        updateRouteInfoPanel(distanceKm, timeMin, isDetoured, hardHazardHit, softHazardHit, namaPoskoTujuan, diffDistanceKm, diffTimeMin, timePenaltyMin);
                    });

                    routingControl.on('routingerror', function() {
                        console.warn("OSRM Routing Waypoint Gagal. Menggunakan Fallback Direct Route.");
                        renderFallbackDirectRoute(startLatLng, destLatLng, namaPoskoTujuan);
                    });

                } catch(e) {
                    renderFallbackDirectRoute(startLatLng, destLatLng, namaPoskoTujuan);
                }
            });
        }

        function renderFallbackDirectRoute(startLatLng, destLatLng, namaPoskoTujuan) {
            if (routingControl) { 
                try { mainMap.removeControl(routingControl); } catch(e){} 
                routingControl = null; 
            }
            if (fallbackPolyline) { 
                try { mainMap.removeLayer(fallbackPolyline); } catch(e){} 
                fallbackPolyline = null; 
            }

            fallbackPolyline = L.polyline([startLatLng, destLatLng], {
                color: '#dc2626',
                weight: 5,
                dashArray: '8, 8',
                opacity: 0.85
            }).addTo(mainMap);

            mainMap.fitBounds(fallbackPolyline.getBounds(), { padding: [50, 50] });

            const distMeters = startLatLng.distanceTo(destLatLng);
            const distKm = (distMeters / 1000).toFixed(1);
            const estTimeMin = Math.round(distKm * 2.5);

            updateRouteInfoPanel(distKm, estTimeMin, true, { jenis: 'Jalan Terputus / Hambatan Akses' }, null, namaPoskoTujuan, 0.5, 5, 0);
        }

        function updateRouteInfoPanel(distKm, timeMin, isDetoured, hardHazard, softHazard, namaPosko, diffDistKm, diffTimeMin, penaltyMin) {
            const panel = document.getElementById('routeInfoPanel');
            const badge = document.getElementById('routeBadgeStatus');
            const icon = document.getElementById('routeStatusIcon');
            const targetName = document.getElementById('routeTargetName');
            const valDistance = document.getElementById('valDistance');
            const valTime = document.getElementById('valTime');
            const wrapperDiff = document.getElementById('wrapperDiff');
            const valDiffDist = document.getElementById('valDiffDist');
            const valDiffTime = document.getElementById('valDiffTime');

            if (!panel) return;

            panel.classList.remove('hidden');
            targetName.textContent = "Pengiriman ke: " + namaPosko;
            valDistance.textContent = distKm;
            valTime.textContent = timeMin;

            if (isDetoured) {
                badge.className = "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-800 uppercase tracking-wider";
                badge.textContent = `🚫 Memutar (${hardHazard ? hardHazard.jenis : 'Jalan Terputus'})`;
                icon.className = "p-2.5 rounded-lg bg-red-100 text-red-600";
                valDiffDist.textContent = `+${diffDistKm > 0 ? diffDistKm : 0.8} km`;
                valDiffTime.textContent = `+${diffTimeMin > 0 ? diffTimeMin : 6} mnt (Memutar)`;
                wrapperDiff.classList.remove('hidden');
            } else if (softHazard) {
                badge.className = "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-800 uppercase tracking-wider";
                badge.textContent = `⚠️ Jalan Rusak (${softHazard.jenis})`;
                icon.className = "p-2.5 rounded-lg bg-amber-100 text-amber-700";
                valDiffDist.textContent = "Jalur Sama";
                valDiffTime.textContent = `+${penaltyMin} mnt (Delay Jalan Rusak)`;
                wrapperDiff.classList.remove('hidden');
            } else {
                badge.className = "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700 uppercase tracking-wider";
                badge.textContent = "⚡ Rute Normal & Aman";
                icon.className = "p-2.5 rounded-lg bg-emerald-100 text-emerald-600";
                wrapperDiff.classList.add('hidden');
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // FUNGSI UTAMA TOMBOL PETA
        function drawDeliveryRoute(latAsal, longAsal, latTujuan, longTujuan, namaPoskoTujuan) {
            const startLat = poskoKomandoLat;
            const startLng = poskoKomandoLng;
            
            const destLat = parseFloat(latTujuan);
            const destLng = parseFloat(longTujuan);

            if (isNaN(destLat) || isNaN(destLng)) {
                alert("Koordinat Sub-Posko tujuan pengiriman tidak valid.");
                return;
            }

            calculateSmartRoute(startLat, startLng, destLat, destLng, namaPoskoTujuan);
        }

        function openKendalaModal(lat = null, lng = null) {
            const modal = document.getElementById('kendalaModal');
            if (!modal) return;
            modal.classList.remove('hidden');

            const targetLat = lat || poskoKomandoLat;
            const targetLng = lng || poskoKomandoLng;

            setTimeout(() => {
                if (!modalMap) {
                    modalMap = L.map('modalMap').setView([targetLat, targetLng], 13);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(modalMap);

                    pickerMarker = L.marker([targetLat, targetLng], { draggable: true }).addTo(modalMap);

                    pickerMarker.on('dragend', function() {
                        const position = pickerMarker.getLatLng();
                        updateCoordinatesInput(position.lat, position.lng);
                    });

                    modalMap.on('click', function(e) {
                        pickerMarker.setLatLng(e.latlng);
                        updateCoordinatesInput(e.latlng.lat, e.latlng.lng);
                    });
                } else {
                    modalMap.invalidateSize();
                    modalMap.setView([targetLat, targetLng], 13);
                    pickerMarker.setLatLng([targetLat, targetLng]);
                }
                updateCoordinatesInput(targetLat, targetLng);
            }, 200);

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        function closeKendalaModal() {
            const modal = document.getElementById('kendalaModal');
            if (modal) modal.classList.add('hidden');
        }

        function updateCoordinatesInput(lat, lng) {
            const inputLat = document.getElementById('input_latitude');
            const inputLng = document.getElementById('input_longitude');
            if (inputLat) inputLat.value = parseFloat(lat).toFixed(6);
            if (inputLng) inputLng.value = parseFloat(lng).toFixed(6);
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // 1. Inisialisasi Peta Utama (Set View Langsung ke Posko Komando)
            mainMap = L.map('map').setView([poskoKomandoLat, poskoKomandoLng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap RESCUE-LOG'
            }).addTo(mainMap);

            setTimeout(() => { mainMap.invalidateSize(); }, 300);

            // ==========================================
            // A. LAYER BPBD KABUPATEN
            // ==========================================
            const bpbdLat = bpbdInduk?.latitude ? parseFloat(bpbdInduk.latitude) : -7.8893;
            const bpbdLng = bpbdInduk?.longitude ? parseFloat(bpbdInduk.longitude) : 110.3288;
            const bpbdNama = bpbdInduk?.nama_kabupaten_kota || 'BPBD Kabupaten Bantul';
            const bpbdAlamat = bpbdInduk?.alamat_kantor || 'Jl. Jend. A. Yani No. 1, Badegan, Bantul';

            const bpbdIcon = L.divIcon({
                className: 'custom-bpbd-icon',
                html: `<div class="w-7 h-7 bg-amber-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white text-[11px] font-bold">🏛️</div>`,
                iconSize: [28, 28],
                iconAnchor: [14, 14]
            });

            L.marker([bpbdLat, bpbdLng], { icon: bpbdIcon })
                .addTo(mainMap)
                .bindPopup(`<b>🏛️ ${bpbdNama}</b><br><span class="text-xs text-slate-500">Gudang Logistik Induk BPBD</span><br><small>${bpbdAlamat}</small>`);

            // ==========================================
            // B. LAYER POSKO KOMANDO UTAMA
            // ==========================================
            if (currentPosko) {
                const pLat = currentPosko.latitude ? parseFloat(currentPosko.latitude) : poskoKomandoLat;
                const pLng = currentPosko.longitude ? parseFloat(currentPosko.longitude) : poskoKomandoLng;

                const komandoIcon = L.divIcon({
                    className: 'custom-komando-icon',
                    html: `<div class="w-8 h-8 bg-indigo-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white text-[12px] font-bold">🏢</div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                L.marker([pLat, pLng], { icon: komandoIcon })
                    .addTo(mainMap)
                    .bindPopup(`<b>🏢 ${currentPosko.nama_posko} (Posko Komando)</b><br>PJ: ${currentPosko.penanggung_jawab || '-'}<br>Status: AKTIF OPERASI`);
            }

            // ==========================================
            // C. LAYER SUB-POSKO LAPANGAN
            // ==========================================
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
                        .addTo(mainMap)
                        .bindPopup(`<b>⛺ ${sp.nama_posko} (Sub-Posko Lapangan)</b><br>PJ: ${sp.penanggung_jawab || '-'}<br>Petugas: ${sp.jumlah_petugas || 0} Jiwa`);
                }
            });

            // ==========================================
            // D. LAYER BENCANA (TITIK MERAH + POLIGON)
            // ==========================================
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
                    .addTo(mainMap)
                    .bindPopup(`<b>⚠️ [Bencana] ${bencanaAktif.jenis_bencana}</b><br>Lokasi: ${bencanaAktif.lokasi_bencana}`);

                let polygonData = bencanaAktif.geojson_polygon;
                if (typeof polygonData === 'string') {
                    try { polygonData = JSON.parse(polygonData); } catch (e) {}
                }

                if (Array.isArray(polygonData) && polygonData.length >= 3) {
                    const polygonLatLngs = polygonData.map(pt => [parseFloat(pt.lat), parseFloat(pt.lng)]);
                    L.polygon(polygonLatLngs, {
                        color: '#dc2626', weight: 2, fillColor: '#ef4444', fillOpacity: 0.25, dashArray: '5, 5'
                    }).bindTooltip(`Zona Terdampak: ${bencanaAktif.jenis_bencana}`, {
                        sticky: true, className: 'text-xs font-bold border-0 shadow-md'
                    }).addTo(mainMap);
                }
            }

            // ==========================================
            // E. LAYER KENDALA JALAN REAL-TIME
            // ==========================================
            kendalaData.forEach(item => {
                const lat = parseFloat(item.latitude);
                const lng = parseFloat(item.longitude);

                if (!isNaN(lat) && !isNaN(lng)) {
                    const isBlocked = isHardBlocker(item.jenis_kendala, item.deskripsi);
                    const markerColor = item.is_active ? (isBlocked ? '#dc2626' : '#f59e0b') : '#10b981';

                    if (item.is_active) {
                        L.circle([lat, lng], {
                            color: markerColor,
                            fillColor: markerColor,
                            fillOpacity: 0.2,
                            radius: isBlocked ? 600 : 400
                        }).addTo(mainMap);
                    }

                    const customMarker = L.circleMarker([lat, lng], {
                        radius: 9,
                        fillColor: markerColor,
                        color: '#ffffff',
                        weight: 2,
                        fillOpacity: 0.9,
                        className: item.is_active ? 'custom-pulse-marker' : ''
                    }).addTo(mainMap);

                    const statusLabel = isBlocked ? '⛔ JALAN TERPUTUS (MEMUTAR)' : '⚠️ JALAN RUSAK (DAPAT DILALUI)';

                    customMarker.bindPopup(`
                        <div class="p-2 font-sans">
                            <span class="text-[10px] uppercase font-bold ${isBlocked ? 'text-red-600' : 'text-amber-600'}">
                                ${statusLabel}
                            </span>
                            <h4 class="font-bold text-sm text-slate-900 mt-1">${item.nama_lokasi}</h4>
                            <p class="text-xs text-slate-600 mt-1">Jenis: <strong class="capitalize">${item.jenis_kendala ? item.jenis_kendala.replace(/_/g, ' ') : '-'}</strong></p>
                            <p class="text-xs text-slate-500 mt-1">${item.deskripsi ?? 'Tidak ada deskripsi'}</p>
                        </div>
                    `);
                }
            });

            // ==========================================
            // F. LAYER MARKER PENGIRIMAN LOGISTIK
            // ==========================================
            const pengirimans = @json($pengirimans);
            pengirimans.forEach(p => {
                const rawLat = p.lat_tujuan || p.pengajuan?.posko?.latitude || p.pengajuan?.user?.posko?.latitude;
                const rawLng = p.long_tujuan || p.pengajuan?.posko?.longitude || p.pengajuan?.user?.posko?.longitude;

                const latTujuan = parseFloat(rawLat);
                const longTujuan = parseFloat(rawLng);
                const namaPosko = p.posko?.nama_posko || p.pengajuan?.posko?.nama_posko || p.pengajuan?.user?.posko?.nama_posko || 'Sub-Posko Lapangan';

                if (!isNaN(latTujuan) && !isNaN(longTujuan)) {
                    const markerPengiriman = L.marker([latTujuan, longTujuan]).addTo(mainMap);
                    const statusFormatted = p.status_distribusi ? p.status_distribusi.replace(/_/g, ' ') : 'Proses';
                    const safeNamaPosko = namaPosko.replace(/'/g, "\\'");

                    markerPengiriman.bindPopup(`
                        <div class="p-1 font-sans">
                            <span class="text-[10px] font-bold text-blue-600 uppercase">Pengiriman #${p.id}</span>
                            <h4 class="font-bold text-sm text-slate-800">${namaPosko}</h4>
                            <p class="text-xs text-slate-600 mt-0.5">Status: <b class="capitalize text-amber-600">${statusFormatted}</b></p>
                            <p class="text-[11px] text-slate-500">Armada: <b>${p.armada?.nama_armada || '-'} (${p.armada?.plat_nomor || '-'})</b></p>
                            <button onclick="drawDeliveryRoute(${poskoKomandoLat}, ${poskoKomandoLng}, ${latTujuan}, ${longTujuan}, '${safeNamaPosko}')" 
                                    class="mt-2 text-xs bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-2.5 py-1.5 rounded-lg w-full transition-colors cursor-pointer shadow-sm flex items-center justify-center gap-1">
                                🚀 Analisis & Rekomendasi Rute
                            </button>
                        </div>
                    `);
                }
            });

            // Klik Peta untuk Lapor Kendala
            mainMap.on('click', function(e) {
                openKendalaModal(e.latlng.lat, e.latlng.lng);
            });

            // AUTO TRIGGER RUTE UNTUK PENGIRIMAN BARU SAAT DIBUAT
            @if (session('active_pengiriman_id'))
                const activeId = {{ session('active_pengiriman_id') }};
                const activeShipment = pengirimans.find(p => p.id === activeId);

                if (activeShipment) {
                    const lAsal = activeShipment.lat_asal || poskoKomandoLat;
                    const lgAsal = activeShipment.long_asal || poskoKomandoLng;
                    const lTuj = activeShipment.lat_tujuan || activeShipment.posko?.latitude || activeShipment.pengajuan?.posko?.latitude || -7.8000;
                    const lgTuj = activeShipment.long_tujuan || activeShipment.posko?.longitude || activeShipment.pengajuan?.posko?.longitude || 110.3800;
                    const targetName = activeShipment.posko?.nama_posko || activeShipment.pengajuan?.posko?.nama_posko || 'Sub-Posko Tujuan';

                    setTimeout(() => {
                        drawDeliveryRoute(lAsal, lgAsal, lTuj, lgTuj, targetName);
                    }, 600);
                }
            @endif
        });
    </script>
@endpush