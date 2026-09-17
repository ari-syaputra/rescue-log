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
            // Pada bagian Loop Bencana Aktif:
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

                    // B. Render Poligon Area Terdampak (Parsing Ganda Aman String/Array)
                    let polygonData = item.geojson_polygon;
                    
                    // Melakukan JSON.parse berulang jika data terbungkus sebagai string JSON
                    while (typeof polygonData === 'string') {
                        try { 
                            polygonData = JSON.parse(polygonData); 
                        } catch (e) { 
                            break; 
                        }
                    }

                    if (Array.isArray(polygonData) && polygonData.length >= 3) {
                        // Konversi format [{lat: x, lng: y}] atau [[lat, lng]] secara otomatis
                        const polygonLatLngs = polygonData.map(pt => {
                            if (Array.isArray(pt)) return [parseFloat(pt[0]), parseFloat(pt[1])];
                            return [parseFloat(pt.lat), parseFloat(pt.lng)];
                        });

                        L.polygon(polygonLatLngs, {
                            color: '#dc2626',       // Red-600 (Garis tepi)
                            weight: 2,               // Ketebalan garis
                            fillColor: '#ef4444',    // Red-500 (Warna Isian)
                            fillOpacity: 0.35,       // Transparansi poligon
                            dashArray: '5, 5',       // Garis putus-putus
                            stroke: true
                        }).bindTooltip(`Perkiraan Zona Terdampak: ${item.jenis_bencana}`, {
                            sticky: true,
                            className: 'text-xs font-bold border-0 shadow-md'
                        }).addTo(map);

                        // Masukkan titik poligon ke bounds grup agar ter-fitBounds dengan pas
                        polygonLatLngs.forEach(pt => boundsGroup.push(pt));
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
                        confirmButtonColor: '#1d4ed8', // Blue 700
                        customClass: { 
                            popup: 'rounded-3xl font-sans p-6'
                        }
                    });
                    return false;
                }

                // Tampilkan SweetAlert Loading
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

    // 3. Fungsi Membuka Modal Validasi & Action URL Dinamis
    function openModalValidasi(buttonElement) {
        let data = null;

        if (buttonElement && buttonElement.dataset && buttonElement.dataset.pending) {
            try {
                data = typeof buttonElement.dataset.pending === 'string'
                    ? JSON.parse(buttonElement.dataset.pending)
                    : buttonElement.dataset.pending;
            } catch (e) {
                console.error("Gagal parse dataset pending:", e);
            }
        } else if (typeof buttonElement === 'object') {
            data = buttonElement;
        }

        if (!data || !data.id) {
            Swal.fire({
                icon: 'error',
                title: 'Data Tidak Ditemukan',
                text: 'ID bencana pending tidak valid.',
                confirmButtonColor: '#ef4444',
                customClass: {
                    popup: 'rounded-3xl font-sans'
                }
            });
            return;
        }

        const formValidasi = document.getElementById('formValidasi');
        const modalValidasi = document.getElementById('modalValidasi');

        if (formValidasi && modalValidasi) {
            formValidasi.action = `/admin/bencana/${data.id}/approve`;

            // Reset Input Form
            document.getElementById('input_estimasi_pengungsi').value = '';
            document.getElementById('input_sk_darurat').value = '';

            // Render Data ke Modal UI
            const isManual = String(data.external_id || '').startsWith('MANUAL-');

            if (document.getElementById('valJenis')) document.getElementById('valJenis').innerText = data.jenis_bencana || '-';
            if (document.getElementById('valJenisBadge')) {
                const badge = document.getElementById('valJenisBadge');
                badge.innerText = isManual ? '📝 MANUAL TRC' : '🛰️ BMKG AUTO';
                badge.className = isManual 
                    ? 'px-2.5 py-0.5 font-bold bg-indigo-100 text-indigo-900 rounded-md uppercase text-xs' 
                    : 'px-2.5 py-0.5 font-bold bg-amber-100 text-amber-800 rounded-md uppercase text-xs';
            }
            if (document.getElementById('valWilayah')) document.getElementById('valWilayah').innerText = data.wilayah || data.lokasi || '-';
            if (document.getElementById('valLat')) document.getElementById('valLat').innerText = data.latitude || data.koordinat_lat || '-';
            if (document.getElementById('valLng')) document.getElementById('valLng').innerText = data.longitude || data.koordinat_lng || '-';
            if (document.getElementById('valWaktu')) document.getElementById('valWaktu').innerText = data.waktu_kejadian || '-';

            // Tampilkan Modal
            modalValidasi.classList.remove('hidden');
        }
    }

    // 4. Menutup Modal
    function closeModal() {
        const modalValidasi = document.getElementById('modalValidasi');
        if (modalValidasi) {
            modalValidasi.classList.add('hidden');
        }
    }

    // 5. Konfirmasi Abaikan / Reject Bencana Pending
    function konfirmasiAbaikan(id) {
        Swal.fire({
            title: 'Abaikan Deteksi Bencana?',
            text: "Data insiden ini akan diabaikan dan tidak masuk ke log operasi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1d4ed8', // Blue 700
            cancelButtonColor: '#cbd5e1',  // Slate 300
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

    // 6. Konfirmasi Selesai Operasi Bencana
    function konfirmasiSelesaiOperasi(bencanaId, namaBencana) {
        Swal.fire({
            title: 'Selesaikan Operasi Bencana?',
            html: `Apakah Anda yakin ingin menyelesaikan operasi tanggap darurat <b>(${namaBencana})</b>?<br><br><span class="text-xs text-slate-500">Seluruh Posko terkait akan diubah ke status Standby/Ditutup.</span>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1d4ed8', // Blue 700
            cancelButtonColor: '#cbd5e1',  // Slate 300
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