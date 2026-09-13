<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Peta Leaflet
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
    });

    // 1. Fungsi Membuka Modal Validasi & Set Action URL Dinamis
    function openModalValidasi(buttonElement) {
        let data = {};
        
        // Cek apakah parameter yang dikirim adalah elemen HTML (button) atau objek JS
        if (buttonElement && buttonElement.dataset && buttonElement.dataset.pending) {
            try {
                data = JSON.parse(buttonElement.dataset.pending);
            } catch (e) {
                console.error("Gagal parse data-pending:", e);
                return;
            }
        } else if (typeof buttonElement === 'object') {
            data = buttonElement;
        }

        if (!data || !data.id) {
            console.error("Data ID Bencana tidak ditemukan:", data);
            return;
        }

        const formValidasi = document.getElementById('formValidasi');
        const modalValidasi = document.getElementById('modalValidasi');

        if (formValidasi && modalValidasi) {
            // Path URL relatif murni Laravel
            formValidasi.action = `/admin/bencana/${data.id}/approve`;

            // Pengisian data ke elemen UI Modal
            if (document.getElementById('valJenis')) document.getElementById('valJenis').innerText = data.jenis_bencana || '-';
            if (document.getElementById('valJenisBadge')) document.getElementById('valJenisBadge').innerText = data.jenis_bencana || 'Bencana';
            if (document.getElementById('valWilayah')) document.getElementById('valWilayah').innerText = data.wilayah || data.lokasi || '-';
            if (document.getElementById('valLat')) document.getElementById('valLat').innerText = data.latitude || data.koordinat_lat || '-';
            if (document.getElementById('valLng')) document.getElementById('valLng').innerText = data.longitude || data.koordinat_lng || '-';
            if (document.getElementById('valWaktu')) document.getElementById('valWaktu').innerText = data.waktu_kejadian || '-';

            // Tampilkan Modal
            modalValidasi.classList.remove('hidden');
        }
    }

    // 2. Menutup Modal
    function closeModal() {
        const modalValidasi = document.getElementById('modalValidasi');
        if (modalValidasi) {
            modalValidasi.classList.add('hidden');
        }
    }

    // 3. Konfirmasi Abaikan / Reject
    function konfirmasiAbaikan(id) {
        Swal.fire({
            title: 'Abaikan Deteksi Bencana?',
            text: "Data deteksi dari BMKG ini akan diabaikan dan tidak masuk ke log operasi.",
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
                document.getElementById(`form-abaikan-${id}`).submit();
            }
        });
    }
</script>