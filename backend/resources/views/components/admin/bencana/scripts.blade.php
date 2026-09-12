<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const map = L.map('mapBencana').setView([-7.8000, 110.3700], 8);

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
    });

    // 1. Fungsi Modal Validasi / Approve
    function openModalValidasi(data) {
        // PERBAIKAN: URL disesuaikan dengan route /admin/bencana/{id}/approve
        document.getElementById('formValidasi').action = `/admin/bencana/${data.id}/approve`;

        document.getElementById('valJenis').innerText = data.jenis_bencana || '-';
        document.getElementById('valJenisBadge').innerText = data.jenis_bencana || 'Bencana';
        document.getElementById('valWilayah').innerText = data.wilayah || data.lokasi || '-';
        document.getElementById('valLat').innerText = data.latitude || data.koordinat_lat || '-';
        document.getElementById('valLng').innerText = data.longitude || data.koordinat_lng || '-';
        document.getElementById('valWaktu').innerText = data.waktu_kejadian || '-';
        
        document.getElementById('modalValidasi').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modalValidasi').classList.add('hidden');
    }

    // 2. Konfirmasi Abaikan / Reject
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

    // 3. Konfirmasi Selesai Operasi / Finish
    function konfirmasiSelesaiOperasi(id, jenisBencana) {
        Swal.fire({
            title: 'Selesaikan Operasi Tanggap Darurat?',
            text: `Operasi untuk bencana "${jenisBencana}" akan ditutup dan statusnya diubah menjadi Selesai.`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Selesaikan',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'px-4 py-2 rounded-xl text-sm font-semibold',
                cancelButton: 'px-4 py-2 rounded-xl text-sm font-semibold'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`form-selesai-${id}`).submit();
            }
        });
    }
</script>