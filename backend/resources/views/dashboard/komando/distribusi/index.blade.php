@extends('layouts.app')

@section('title', 'Control Center Distribusi & Rute - SiGap BPBD')

@push('styles')
    <!-- Leaflet CSS untuk Visualisasi Peta Live -->
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

        <!-- Flash Notification Success / Error -->
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

        <!-- HEADER & TITLE SECTION -->
        <x-komando.distribusi.header />

        <!-- BANNER AI OPTIMISASI RUTE DISTRIBUSI -->
        <x-komando.distribusi.ai-banner :active-kendala-count="$kendalaJalans->where('is_active', true)->count()" />

        <!-- STATISTIK RINGKASAN DATA -->
        <x-komando.distribusi.stats :siap-kirim-count="$pengajuanSiapKirim->count()" :dalam-perjalanan-count="$pengirimans->whereIn('status_pengiriman', ['dijadwalkan', 'dalam_perjalanan'])->count()" :armada-count="$armadas->count()" :hambatan-count="$kendalaJalans->where('is_active', true)->count()" />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <div class="lg:col-span-5 space-y-4">
                <x-komando.distribusi.active-shipments :pengirimans="$pengirimans" :pengajuan-siap-kirim="$pengajuanSiapKirim" :armadas="$armadas" />
            </div>

            <x-komando.distribusi.peta-situasi class="lg:col-span-7" />

        </div>

        <!-- TABEL DATA LAPORAN KENDALA JALAN -->
        <x-komando.distribusi.kendala-table :kendala-jalans="$kendalaJalans" />

    </div>

    <!-- MODAL POPUP LAPOR KENDALA JALAN -->
    <x-komando.distribusi.modal-kendala />

    <!-- MODAL POPUP REGISTRASI ARMADA BARU -->
<x-komando.distribusi.modal-registrasi-armada />

@endsection

@push('scripts')
    <!-- CDN Leaflet JS & Leaflet Routing Machine -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>

    <script>
        let mainMap, modalMap, pickerMarker;
        let routingControl = null;
        let hazardPolyline = null;

        // Controls Modal Registrasi Armada
        function openArmadaModal() {
            const modal = document.getElementById('armadaModal');
            if (modal) modal.classList.remove('hidden');
        }

        function closeArmadaModal() {
            const modal = document.getElementById('armadaModal');
            if (modal) modal.classList.add('hidden');
        }

        // Default Koordinat Posko Utama Komando
        const defaultLat = -7.7956;
        const defaultLng = 110.3695;

        // Data Kendala Jalan Aktif dari Backend
        const kendalaData = @json($kendalaJalans);

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
         * 🧠 HYBRID SMART ROUTING SYSTEM (OPTIMIZED DETOUR)
         */
        function calculateSmartRoute(startLat, startLng, destLat, destLng, namaPoskoTujuan) {
            if (!mainMap) return;

            // Bersihkan rute & layer lama
            if (routingControl) mainMap.removeControl(routingControl);
            if (hazardPolyline) mainMap.removeLayer(hazardPolyline);

            const startLatLng = L.latLng(startLat, startLng);
            const destLatLng = L.latLng(destLat, destLng);

            // Parsing & Klasifikasi Kendala Jalan dengan Radius Deteksi Presisi
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
                        radius: isBlocked ? 0.003 : 0.002
                    };
                });

            const tempRouter = L.Routing.osrmv1({
                serviceUrl: 'https://router.project-osrm.org/route/v1',
                profile: 'car'
            });

            // 1. Hitung Rute Langsung Terlebih Dahulu (Direct Path)
            tempRouter.route([{
                    latLng: startLatLng
                },
                {
                    latLng: destLatLng
                }
            ], function(err, routes) {
                if (err || !routes || routes.length === 0) {
                    alert("Gagal menghitung rute dasar.");
                    return;
                }

                const directRoute = routes[0];
                const initialDistance = directRoute.summary.totalDistance; // meter
                const initialTime = directRoute.summary.totalTime; // detik

                let hardHazardHit = null;
                let softHazardHit = null;
                let hazardCoords = [];

                // Evaluasi Setiap Koordinat Rute terhadap titik-titik bencana
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

                // 🔴 KONDISI 1: ADA JEMBATAN PUTUS / LONGSOR TOTAL -> MEMUTAR PRESISI
                if (hardHazardHit) {
                    isDetoured = true;

                    const dLat = destLat - startLat;
                    const dLng = destLng - startLng;
                    const offsetDistance = 0.0025;

                    const offsetLat = hardHazardHit.lat + (-dLng * offsetDistance);
                    const offsetLng = hardHazardHit.lng + (dLat * offsetDistance);

                    waypoints = [
                        startLatLng,
                        L.latLng(offsetLat, offsetLng),
                        destLatLng
                    ];
                }

                // 🟡 KONDISI 2: HANYA JALAN RUSAK
                if (!hardHazardHit && softHazardHit && hazardCoords.length > 0) {
                    hazardPolyline = L.polyline(hazardCoords, {
                        color: '#f59e0b',
                        weight: 12,
                        opacity: 0.45,
                        dashArray: '8, 8',
                        lineCap: 'round'
                    }).addTo(mainMap).bindPopup(`
                    <div class="p-1 font-sans">
                        <span class="text-[10px] font-bold text-amber-600 uppercase">⚠️ Segmen Jalan Rusak</span>
                        <h4 class="font-bold text-xs text-slate-800 mt-0.5">${softHazardHit.nama}</h4>
                        <p class="text-[11px] text-slate-600 mt-1">Status: <b>Dapat Dilalui (Lambat)</b></p>
                    </div>
                `);
                }

                // Render Rute Akhir pada Peta
                routingControl = L.Routing.control({
                    waypoints: waypoints,
                    router: tempRouter,
                    lineOptions: {
                        styles: [{
                                color: '#ffffff',
                                opacity: 0.9,
                                weight: 8
                            },
                            {
                                color: isDetoured ? '#dc2626' : (softHazardHit ? '#d97706' : '#2563eb'),
                                opacity: softHazardHit && !isDetoured ? 0.7 : 0.9,
                                weight: 5
                            }
                        ]
                    },
                    addWaypoints: true,
                    draggableWaypoints: true,
                    fitSelectedRoutes: true,
                    show: false,
                    createMarker: function(i, wp, n) {
                        if (i === 0) {
                            return L.marker(wp.latLng, {
                                draggable: true
                            }).bindPopup("<b>🚩 Titik Awal:</b> Posko Utama");
                        }
                        if (i === n - 1) {
                            return L.marker(wp.latLng, {
                                draggable: true
                            }).bindPopup(`<b>📦 Tujuan:</b> ${namaPoskoTujuan}`);
                        }
                        return L.marker(wp.latLng, {
                            draggable: true,
                            icon: L.divIcon({
                                className: 'custom-waypoint-icon',
                                html: '<div style="background-color: #dc2626; width: 14px; height: 14px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 6px rgba(0,0,0,0.4);"></div>',
                                iconSize: [14, 14],
                                iconAnchor: [7, 7]
                            })
                        }).bindPopup(
                            "<b>🔀 Titik Pengalihan Rute (Detour)</b><br><small>Geser titik ini jika ingin menyesuaikan persimpangan</small>"
                            );
                    }
                }).addTo(mainMap);

                // Callback setelah Rute Ditemukan & Dihitung
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

                    updateRouteInfoPanel(
                        distanceKm,
                        timeMin,
                        isDetoured,
                        hardHazardHit,
                        softHazardHit,
                        namaPoskoTujuan,
                        diffDistanceKm,
                        diffTimeMin,
                        timePenaltyMin
                    );

                    let popupHtml = '';
                    if (isDetoured) {
                        popupHtml = `
                        <div class="p-2 bg-red-50 rounded-lg border border-red-300 text-left">
                            <div class="text-red-800 font-bold text-xs flex items-center gap-1">
                                🚫 RUTE DIALIHKAN (ELEM DETOUR)
                            </div>
                            <p class="text-[11px] text-red-700 mt-1">
                                Menghindari <b>${hardHazardHit.nama}</b>. Jalur terputus total!
                            </p>
                            <p class="text-[10px] text-red-600 mt-0.5 italic">+${diffDistanceKm > 0 ? diffDistanceKm : 0} km dari rute utama (+${diffTimeMin > 0 ? diffTimeMin : 0} menit)</p>
                        </div>
                    `;
                    } else if (softHazardHit) {
                        popupHtml = `
                        <div class="p-2 bg-amber-50 rounded-lg border border-amber-300 text-left">
                            <div class="text-amber-800 font-bold text-xs flex items-center gap-1">
                                ⚠️ RUTE BERISIKO (JALAN RUSAK)
                            </div>
                            <p class="text-[11px] text-amber-700 mt-1">
                                Melintasi <b>${softHazardHit.nama}</b>. Tetap dapat dilalui dengan kecepatan rendah.
                            </p>
                            <p class="text-[10px] text-amber-800 mt-0.5 font-semibold">⏱️ Estimasi Terlambat: +${timePenaltyMin} Menit</p>
                        </div>
                    `;
                    } else {
                        popupHtml = `
                        <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-300 text-center">
                            <span class="text-xs font-bold text-emerald-800">⚡ Rute Aman & Lancar</span>
                        </div>
                    `;
                    }

                    L.popup()
                        .setLatLng(waypoints[Math.floor(waypoints.length / 2)])
                        .setContent(`
                        <div class="font-sans text-center max-w-[260px]">
                            ${popupHtml}
                            <div class="mt-2 text-xs text-slate-700 flex justify-around border-t pt-2 border-slate-200">
                                <div>Total Jarak: <b>${distanceKm} km</b></div>
                                <div>Total Waktu: <b>${timeMin} mnt</b></div>
                            </div>
                        </div>
                    `)
                        .openOn(mainMap);
                });
            });
        }

        /**
         * Update Dashboard Card Info di Bawah Peta
         */
        function updateRouteInfoPanel(distKm, timeMin, isDetoured, hardHazard, softHazard, namaPosko, diffDistKm,
            diffTimeMin, penaltyMin) {
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
                badge.className =
                    "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-red-100 text-red-800 uppercase tracking-wider";
                badge.textContent = `🚫 Memutar (${hardHazard.jenis})`;

                icon.className = "p-2.5 rounded-lg bg-red-100 text-red-600";

                valDiffDist.textContent = `+${diffDistKm} km`;
                valDiffTime.textContent = `+${diffTimeMin} mnt (Memutar)`;
                wrapperDiff.classList.remove('hidden');
            } else if (softHazard) {
                badge.className =
                    "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-800 uppercase tracking-wider";
                badge.textContent = `⚠️ Jalan Rusak (${softHazard.jenis})`;

                icon.className = "p-2.5 rounded-lg bg-amber-100 text-amber-700";

                valDiffDist.textContent = "Jalur Sama";
                valDiffTime.textContent = `+${penaltyMin} mnt (Delay Jalan Rusak)`;
                wrapperDiff.classList.remove('hidden');
            } else {
                badge.className =
                    "inline-block px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-700 uppercase tracking-wider";
                badge.textContent = "⚡ Rute Normal & Aman";

                icon.className = "p-2.5 rounded-lg bg-emerald-100 text-emerald-600";
                wrapperDiff.classList.add('hidden');
            }

            if (typeof lucide !== 'undefined') lucide.createIcons();
        }

        // Wrapper Tombol Hitung Rute
        function drawDeliveryRoute(latAsal, longAsal, latTujuan, longTujuan, namaPoskoTujuan) {
            const startLat = parseFloat(latAsal) || defaultLat;
            const startLng = parseFloat(longAsal) || defaultLng;
            const destLat = parseFloat(latTujuan);
            const destLng = parseFloat(longTujuan);

            if (isNaN(destLat) || isNaN(destLng)) {
                alert("Koordinat tujuan pengiriman tidak valid.");
                return;
            }

            calculateSmartRoute(startLat, startLng, destLat, destLng, namaPoskoTujuan);
        }

        // Modal Controls Kendala Jalan
        function openKendalaModal(lat = null, lng = null) {
            const modal = document.getElementById('kendalaModal');
            if (!modal) return;

            modal.classList.remove('hidden');

            const targetLat = lat || defaultLat;
            const targetLng = lng || defaultLng;

            setTimeout(() => {
                if (!modalMap) {
                    modalMap = L.map('modalMap').setView([targetLat, targetLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap'
                    }).addTo(modalMap);

                    pickerMarker = L.marker([targetLat, targetLng], {
                        draggable: true
                    }).addTo(modalMap);

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

        function getCurrentGPSLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        if (modalMap && pickerMarker) {
                            modalMap.setView([lat, lng], 15);
                            pickerMarker.setLatLng([lat, lng]);
                            updateCoordinatesInput(lat, lng);
                        }
                    },
                    (error) => alert("Gagal mendapatkan lokasi GPS: " + error.message)
                );
            } else {
                alert("Browser Anda tidak mendukung fitur Geolokasi.");
            }
        }

        // Inisialisasi Utama Saat Halaman Dimuat
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // 1. Inisialisasi Peta Utama
            mainMap = L.map('map').setView([defaultLat, defaultLng], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap'
            }).addTo(mainMap);

            // 2. Render Posko Utama
            L.circleMarker([defaultLat, defaultLng], {
                radius: 12,
                fillColor: '#2563eb',
                color: '#ffffff',
                weight: 3,
                fillOpacity: 1
            }).addTo(mainMap).bindPopup("<b>Posko Utama Komando BPBD</b>");

            // 3. Render Marker Kendala Jalan
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

                    const statusLabel = isBlocked ? '⛔ JALAN TERPUTUS (MEMUTAR)' :
                        '⚠️ JALAN RUSAK (DAPAT DILALUI)';

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

            // 4. Render Marker Pengiriman
            const pengirimans = @json($pengirimans);
            pengirimans.forEach(p => {
                const rawLat = p.lat_tujuan || p.pengajuan?.user?.posko?.latitude;
                const rawLng = p.long_tujuan || p.pengajuan?.user?.posko?.longitude;

                const latTujuan = parseFloat(rawLat);
                const longTujuan = parseFloat(rawLng);
                const namaPosko = p.pengajuan?.user?.posko?.nama_posko || p.pengajuan?.user?.name ||
                    'Posko Lapangan';

                if (!isNaN(latTujuan) && !isNaN(longTujuan)) {
                    const markerPengiriman = L.marker([latTujuan, longTujuan]).addTo(mainMap);
                    const statusFormatted = p.status_pengiriman ? p.status_pengiriman.replace(/_/g, ' ') :
                        'Proses';
                    const safeNamaPosko = namaPosko.replace(/'/g, "\\'");

                    markerPengiriman.bindPopup(`
                    <div class="p-1 font-sans">
                        <span class="text-[10px] font-bold text-blue-600 uppercase">Pengiriman #${p.kode_pengiriman}</span>
                        <h4 class="font-bold text-sm text-slate-800">${namaPosko}</h4>
                        <p class="text-xs text-slate-600 mt-0.5">Status: <b class="capitalize text-amber-600">${statusFormatted}</b></p>
                        <p class="text-[11px] text-slate-500">Armada: <b>${p.armada?.nama_armada || '-'} (${p.armada?.plat_nomor || '-'})</b></p>
                        <button onclick="drawDeliveryRoute(${parseFloat(p.lat_asal) || defaultLat}, ${parseFloat(p.long_asal) || defaultLng}, ${latTujuan}, ${longTujuan}, '${safeNamaPosko}')" 
                                class="mt-2 text-xs bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold px-2.5 py-1.5 rounded-lg w-full transition-colors cursor-pointer shadow-sm flex items-center justify-center gap-1">
                            🚀 Analisis & Rekomendasi Rute
                        </button>
                    </div>
                `);
                }
            });

            // Event Klik Peta untuk Tambah Kendala
            mainMap.on('click', function(e) {
                openKendalaModal(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
@endpush
