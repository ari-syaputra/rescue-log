<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inisialisasi Peta Leaflet Dashboard
        const mapContainer = document.getElementById('mapBencana');
        if (mapContainer) {
            // Default center awal
            const map = L.map('mapBencana').setView([-7.8893, 110.3288], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // Koleksi titik koordinat untuk auto-zoom/fitBounds
            const boundsGroup = [];

            // Marker Bencana Pending (Oranye)
            const pendingData = @json($pendingDisasters ?? []);
            pendingData.forEach(item => {
                const lat = item.latitude || item.koordinat_lat;
                const lng = item.longitude || item.koordinat_lng;
                if (lat && lng) {
                    const latNum = parseFloat(lat);
                    const lngNum = parseFloat(lng);
                    
                    L.circleMarker([latNum, lngNum], {
                        color: '#d97706',
                        fillColor: '#f59e0b',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map).bindPopup(`<b>[Pending] ${item.jenis_bencana || 'Bencana'}</b><br>${item.wilayah || item.lokasi}`);

                    boundsGroup.push([latNum, lngNum]);
                }
            });

            // Marker & Poligon Bencana Aktif (Merah)
            const activeData = @json($activeDisasters ?? []);
            activeData.forEach(item => {
                const lat = parseFloat(item.koordinat_operasional_lat);
                const lng = parseFloat(item.koordinat_operasional_lng);

                if (lat && lng) {
                    // A. Marker Titik Pusat Bencana Aktif (Merah Pulsing)
                    const redPulseIcon = L.divIcon({
                        className: 'custom-bencana-pulse',
                        html: `<div class="w-5 h-5 bg-rose-600 rounded-full border-2 border-white shadow-lg animate-pulse"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    L.marker([lat, lng], { icon: redPulseIcon })
                        .addTo(map)
                        .bindPopup(`<b>[Aktif] ${item.jenis_bencana}</b><br>${item.lokasi_bencana}`);

                    boundsGroup.push([lat, lng]);

                    // B. Render Poligon Area Terdampak (Robust Parser)
                    let polygonData = item.geojson_polygon;
                    
                    // Decode jika terbungkus string JSON berulang
                    while (typeof polygonData === 'string') {
                        try { 
                            polygonData = JSON.parse(polygonData); 
                        } catch (e) { 
                            break; 
                        }
                    }

                    if (polygonData) {
                        let rawCoords = [];

                        // Deteksi struktur GeoJSON / Geometry Object
                        if (typeof polygonData === 'object' && !Array.isArray(polygonData) && polygonData.coordinates) {
                            rawCoords = polygonData.coordinates;
                        } else if (Array.isArray(polygonData)) {
                            rawCoords = polygonData;
                        }

                        // Un-nest ganda (Membongkar array bertingkat hingga menemukan array titik koordinat)
                        while (Array.isArray(rawCoords) && rawCoords.length === 1 && Array.isArray(rawCoords[0]) && typeof rawCoords[0][0] !== 'number') {
                            rawCoords = rawCoords[0];
                        }

                        if (Array.isArray(rawCoords) && rawCoords.length >= 3) {
                            const polygonLatLngs = rawCoords.map(pt => {
                                let ptLat, ptLng;
                                
                                if (Array.isArray(pt)) {
                                    // Deteksi otomatis urutan [lng, lat] (GeoJSON) vs [lat, lng] (Leaflet)
                                    if (Math.abs(parseFloat(pt[0])) > Math.abs(parseFloat(pt[1]))) {
                                        ptLng = parseFloat(pt[0]);
                                        ptLat = parseFloat(pt[1]);
                                    } else {
                                        ptLat = parseFloat(pt[0]);
                                        ptLng = parseFloat(pt[1]);
                                    }
                                } else if (typeof pt === 'object' && pt !== null) {
                                    ptLat = parseFloat(pt.lat ?? pt.latitude);
                                    ptLng = parseFloat(pt.lng ?? pt.longitude);
                                }

                                return [ptLat, ptLng];
                            }).filter(pt => !isNaN(pt[0]) && !isNaN(pt[1]));

                            if (polygonLatLngs.length >= 3) {
                                L.polygon(polygonLatLngs, {
                                    color: '#dc2626',       // Garis Merah (Red-600)
                                    weight: 2,               
                                    fillColor: '#ef4444',    // Isian Merah (Red-500)
                                    fillOpacity: 0.35,       
                                    dashArray: '5, 5',       
                                    stroke: true
                                }).bindTooltip(`Perkiraan Zona Terdampak: ${item.jenis_bencana}`, {
                                    sticky: true,
                                    className: 'text-xs font-bold border-0 shadow-md'
                                }).addTo(map);

                                polygonLatLngs.forEach(pt => boundsGroup.push(pt));
                            }
                        }
                    }
                }
            });

            // Auto-fit zoom peta jika terdapat data koordinat/poligon
            if (boundsGroup.length > 0) {
                map.fitBounds(boundsGroup, { padding: [40, 40] });
            }
        }

        // 2. Intercept Event Submit Form Validasi untuk Menampilkan SweetAlert Loading
        const formValidasi = document.getElementById('formValidasi');
        if (formValidasi) {
            formValidasi.addEventListener('submit', function (e) {
                const estInput = document.getElementById('input_estimasi_pengungsi');
                const skInput = document.getElementById('input_sk_darurat');

                if (!estInput.value || !skInput.files.length) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Belum Lengkap',
                        text: 'Mohon isi estimasi jumlah pengungsi awal dan unggah berkas SK Status Darurat!',
                        confirmButtonColor: '#1d4ed8',
                        customClass: { 
                            popup: 'rounded-3xl font-sans p-6'
                        }
                    });
                    return false;
                }

                Swal.fire({
                    title: 'Memproses Validasi TRC...',
                    text: 'Menyiapkan rekomendasi buffer stok otomatis & mengalokasikan Posko Komando.',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'rounded-3xl font-sans p-6'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }
    });

    // 3. Konfirmasi Abaikan / Reject Bencana Pending
    function konfirmasiAbaikan(id) {
        Swal.fire({
            title: 'Abaikan Deteksi Bencana?',
            text: "Data insiden ini akan diabaikan dan tidak masuk ke log operasi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1d4ed8',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Abaikan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl font-sans p-6',
                actions: 'flex items-center justify-center gap-3 w-full mt-4',
                confirmButton: '!m-0 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm cursor-pointer',
                cancelButton: '!m-0 px-5 py-2.5 rounded-xl text-sm font-bold !text-slate-700 hover:!bg-slate-300 transition cursor-pointer'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formAbaikan = document.getElementById(`form-abaikan-${id}`);
                if (formAbaikan) {
                    formAbaikan.submit();
                }
            }
        });
    }

    // 4. Konfirmasi Selesai Operasi Bencana
    function konfirmasiSelesaiOperasi(bencanaId, namaBencana) {
        Swal.fire({
            title: 'Selesaikan Operasi Bencana?',
            html: `Apakah Anda yakin ingin menyelesaikan operasi tanggap darurat <b>(${namaBencana})</b>?<br><br><span class="text-xs text-slate-500">Seluruh Posko terkait akan diubah ke status Standby/Ditutup.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1d4ed8',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Selesaikan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl font-sans p-6',
                actions: 'flex items-center justify-center gap-3 w-full mt-4',
                confirmButton: '!m-0 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm cursor-pointer',
                cancelButton: '!m-0 px-5 py-2.5 rounded-xl text-sm font-bold !text-slate-700 hover:!bg-slate-300 transition cursor-pointer'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Penutupan Operasi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'rounded-3xl font-sans p-6'
                    },
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const form = document.getElementById(`form-selesai-${bencanaId}`);
                if (form) {
                    form.submit();
                }
            }
        });
    }
</script>