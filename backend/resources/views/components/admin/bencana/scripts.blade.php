<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Inisialisasi Peta Leaflet Dashboard
        const mapContainer = document.getElementById('mapBencana');
        if (mapContainer) {
            const map = L.map('mapBencana').setView([-7.8000, 110.3700], 9);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            setTimeout(() => {
                map.invalidateSize();
            }, 300);

            // Marker Bencana Pending
            const pendingData = @json($pendingDisasters ?? []);
            pendingData.forEach(item => {
                const lat = item.latitude || item.koordinat_lat;
                const lng = item.longitude || item.koordinat_lng;
                if (lat && lng) {
                    L.circleMarker([lat, lng], {
                        color: '#d97706',
                        fillColor: '#f59e0b',
                        fillOpacity: 0.8,
                        radius: 8
                    }).addTo(map).bindPopup(`<b>[Pending] ${item.jenis_bencana || 'Bencana'}</b><br>${item.wilayah || item.lokasi}`);
                }
            });

            // Marker Bencana Aktif
            const activeData = @json($activeDisasters ?? []);
            activeData.forEach(item => {
                if (item.koordinat_operasional_lat && item.koordinat_operasional_lng) {
                    L.circleMarker([item.koordinat_operasional_lat, item.koordinat_operasional_lng], {
                        color: '#dc2626',
                        fillColor: '#ef4444',
                        fillOpacity: 0.9,
                        radius: 10
                    }).addTo(map).bindPopup(`<b>[Aktif] ${item.jenis_bencana}</b><br>${item.lokasi_bencana}`);
                }
            });
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
                        confirmButtonColor: '#d97706',
                        customClass: { popup: 'rounded-2xl' }
                    });
                    return false;
                }

                // Tampilkan SweetAlert Loading saat mengunggah & memproses stok
                Swal.fire({
                    title: 'Memproses Validasi TRC...',
                    text: 'Menyiapkan rekomendasi buffer stok otomatis & mengalokasikan Posko Komando.',
                    allowOutsideClick: false,
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
                confirmButtonColor: '#ef4444'
            });
            return;
        }

        const formValidasi = document.getElementById('formValidasi');
        const modalValidasi = document.getElementById('modalValidasi');

        if (formValidasi && modalValidasi) {
            // Sesuai route: POST /admin/bencana/{id}/approve
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
                    ? 'px-2 py-0.5 font-bold bg-indigo-100 text-indigo-900 rounded uppercase text-[10px]' 
                    : 'px-2 py-0.5 font-bold bg-amber-100 text-amber-800 rounded uppercase text-[10px]';
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
            confirmButtonColor: '#6b7280',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Abaikan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-4 py-2 rounded-xl text-sm font-semibold',
                cancelButton: 'px-4 py-2 rounded-xl text-sm font-semibold'
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
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Selesaikan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl font-sans',
                confirmButton: 'px-4 py-2 rounded-xl text-xs font-semibold shadow-sm',
                cancelButton: 'px-4 py-2 rounded-xl text-xs font-semibold shadow-sm'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses Penutupan Operasi...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
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