@extends('layouts.app')

@push('styles')
<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
    #map-distribusi {
        height: 100%;
        min-height: 480px;
        width: 100%;
        border-radius: 1rem;
        z-index: 1;
    }
</style>
@endpush

@section('content')
<div x-data="{
    modalApprove: false,
    modalEskalasi: false,
    selectedPengajuan: null
}">

    <!-- Header Section -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pusat Distribusi & Eskalasi BPBD</h1>
            <p class="text-base text-gray-600 mt-1">Verifikasi permintaan logistik dari Posko Komando, alokasi stok gudang utama, dan eskalasi ke BPBD Provinsi.</p>
        </div>
    </div>

    <!-- Notifications -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border-2 border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
        <span class="font-medium text-sm">{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-green-600 font-bold text-lg cursor-pointer">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-red-50 border-2 border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center justify-between shadow-sm">
        <span class="font-medium text-sm">{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-red-600 font-bold text-lg cursor-pointer">&times;</button>
    </div>
    @endif

    <!-- STATISTIK RINGKASAN -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500">Permintaan Menunggu Approval</p>
                <h3 class="text-3xl font-extrabold text-amber-600 mt-1">
                    {{ $pengajuanMasuk->where('status', 'pending')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500">Disetujui BPBD</p>
                <h3 class="text-3xl font-extrabold text-green-600 mt-1">
                    {{ $pengajuanMasuk->whereIn('status', ['disetujui', 'dalam_pengiriman', 'selesai'])->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-gray-500">Dieskalasi ke BPBD Provinsi</p>
                <h3 class="text-3xl font-extrabold text-purple-600 mt-1">
                    {{ $pengajuanMasuk->where('status', 'dieskalasi_provinsi')->count() }}
                </h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT: GRID 2 KOLOM (TABEL KIRI | PETA KANAN) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        
        <!-- KOLOM KIRI: TABEL PERMINTAAN LOGISTIK POSKO KOMANDO (span 7) -->
        <div class="lg:col-span-7 bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Pengajuan Logistik Posko Komando</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Daftar permintaan kebutuhan tambahan dari lapangan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100/80 text-gray-600 uppercase text-xs font-bold tracking-wider">
                                <th class="py-3.5 px-4">Kode / Tanggal</th>
                                <th class="py-3.5 px-4">Posko Peminta</th>
                                <th class="py-3.5 px-4">Item Diminta</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 text-center">Aksi / Keputusan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-sm">
                            @forelse($pengajuanMasuk as $item)
                                <tr class="hover:bg-gray-50/80 transition">
                                    <td class="py-3.5 px-4 font-medium text-gray-700 whitespace-nowrap">
                                        <span class="font-bold text-gray-900 block">{{ $item->kode_pengajuan }}</span>
                                        <span class="text-xs text-gray-400">{{ $item->created_at->format('d M Y, H:i') }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 font-semibold text-blue-700">
                                        {{ $item->posko->nama_posko ?? 'Posko Lapangan' }}
                                        <span class="block text-xs text-gray-500 font-normal">{{ $item->bencana->nama_bencana ?? '-' }}</span>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-700 text-xs">
                                        <div class="flex flex-wrap gap-1">
                                            @if($item->beras_kg > 0) <span class="bg-gray-100 px-2 py-0.5 rounded border">Beras: {{ $item->beras_kg }} kg</span> @endif
                                            @if($item->air_minum_dus > 0) <span class="bg-gray-100 px-2 py-0.5 rounded border">Air: {{ $item->air_minum_dus }} dus</span> @endif
                                            @if($item->makanan_kaleng_pack > 0) <span class="bg-gray-100 px-2 py-0.5 rounded border">Mak. Kaleng: {{ $item->makanan_kaleng_pack }} pack</span> @endif
                                            @if($item->obat_p3k_paket > 0) <span class="bg-gray-100 px-2 py-0.5 rounded border">P3K: {{ $item->obat_p3k_paket }} paket</span> @endif
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 whitespace-nowrap">
                                        @if($item->status === 'pending')
                                            <span class="px-2.5 py-1 text-xs font-bold bg-amber-100 text-amber-800 rounded-full border border-amber-200">Pending</span>
                                        @elseif($item->status === 'disetujui')
                                            <span class="px-2.5 py-1 text-xs font-bold bg-green-100 text-green-800 rounded-full border border-green-200">Disetujui</span>
                                        @elseif($item->status === 'dieskalasi_provinsi')
                                            <span class="px-2.5 py-1 text-xs font-bold bg-purple-100 text-purple-800 rounded-full border border-purple-200">Eskalasi Prov</span>
                                        @else
                                            <span class="px-2.5 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full border border-blue-200">{{ ucfirst($item->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                        @if(in_array($item->status, ['pending', 'dieskalasi_provinsi']))
                                            <div class="flex items-center justify-center space-x-1.5">
                                                <button 
                                                    @click="selectedPengajuan = {{ json_encode($item) }}; modalApprove = true;"
                                                    class="px-2.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold text-xs transition cursor-pointer shadow-sm"
                                                >
                                                    Setujui
                                                </button>

                                                @if($item->status !== 'dieskalasi_provinsi')
                                                <button 
                                                    @click="selectedPengajuan = {{ json_encode($item) }}; modalEskalasi = true;"
                                                    class="px-2.5 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold text-xs transition cursor-pointer shadow-sm"
                                                >
                                                    Eskalasi
                                                </button>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400 font-medium italic">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-400 italic">Belum ada pengajuan logistik dari Posko Komando.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PETA JALUR DISTRIBUSI REGIONAL (span 5) -->
        <div class="lg:col-span-5 bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden p-6 flex flex-col">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Peta Rute Distribusi</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Rute jalanan nyata dari Gudang BPBD ke Posko Komando.</p>
                </div>
                <div class="flex items-center gap-3 text-xs font-bold">
                    <span class="flex items-center gap-1 text-blue-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-600 inline-block"></span> BPBD
                    </span>
                    <span class="flex items-center gap-1 text-red-600">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-600 inline-block"></span> Posko
                    </span>
                </div>
            </div>
            
            <div class="flex-1 w-full min-h-[480px]">
                <div id="map-distribusi"></div>
            </div>
        </div>
    </div>

    <!-- MODAL CONFIRM APPROVAL -->
    <div x-show="modalApprove" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Setujui Logistik</h3>
            <p class="text-sm text-gray-600 mb-4">Sistem akan secara otomatis memotong stok barang di Gudang Utama BPBD Kab/Kota sesuai permintaan kode <span class="font-bold text-gray-900" x-text="selectedPengajuan?.kode_pengajuan"></span>.</p>
            
            <form :action="'{{ url('/admin/distribusi/approve') }}/' + selectedPengajuan?.id" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Catatan Tambahan BPBD (Opsional)</label>
                    <textarea name="catatan_komando" rows="2" class="w-full border rounded-xl p-2.5 text-sm" placeholder="Contoh: Barang siap dijemput/dikirim armada komando."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modalApprove = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-xl text-xs font-bold cursor-pointer hover:bg-green-700 transition">Setujui & Potong Stok</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL ESKALASI PROVINSI -->
    <div x-show="modalEskalasi" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4" x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl">
            <h3 class="text-lg font-bold text-purple-900 mb-2">Eskalasi ke BPBD Provinsi</h3>
            <p class="text-sm text-gray-600 mb-4">Gunakan fitur ini jika stok Gudang Kab/Kota tidak mencukupi untuk memenuhi permintaan <span class="font-bold text-gray-900" x-text="selectedPengajuan?.kode_pengajuan"></span>.</p>
            
            <form :action="'{{ url('/admin/distribusi/eskalasi') }}/' + selectedPengajuan?.id" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-700 mb-1">Alasan / Catatan Eskalasi <span class="text-red-500">*</span></label>
                    <textarea name="catatan_eskalasi" required rows="3" class="w-full border rounded-xl p-2.5 text-sm" placeholder="Contoh: Stok beras & obat P3K di gudang Kab/Kota menipis/habis. Membutuhkan bantuan suplai Provinsi."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="modalEskalasi = false" class="px-4 py-2 border rounded-xl text-xs font-bold text-gray-600 cursor-pointer">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold cursor-pointer hover:bg-purple-700 transition">Teruskan ke Provinsi</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Koordinat Gudang Utama BPBD
    const bpbdLat = {{ $bpbd->latitude ?? -7.7956 }};
    const bpbdLng = {{ $bpbd->longitude ?? 110.3695 }};
    const bpbdNama = "{{ addslashes($bpbd->nama_bpbd ?? 'Gudang Utama BPBD') }}";

    // Data Posko Komando dari Backend
    const poskoList = @json($poskoKomandoList ?? []);

    // 2. Inisialisasi Peta
    const map = L.map('map-distribusi').setView([bpbdLat, bpbdLng], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // 3. Custom Marker Icon BPBD & Posko
    const bpbdIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color:#2563eb; width:22px; height:22px; border-radius:50%; border:3px solid white; box-shadow:0 0 8px rgba(0,0,0,0.4);"></div>`,
        iconSize: [22, 22],
        iconAnchor: [11, 11]
    });

    const poskoIcon = L.divIcon({
        className: 'custom-div-icon',
        html: `<div style="background-color:#dc2626; width:18px; height:18px; border-radius:50%; border:2px solid white; box-shadow:0 0 6px rgba(0,0,0,0.4);"></div>`,
        iconSize: [18, 18],
        iconAnchor: [9, 9]
    });

    // 4. Marker Asal (BPBD)
    L.marker([bpbdLat, bpbdLng], { icon: bpbdIcon })
        .addTo(map)
        .bindPopup(`<b>${bpbdNama}</b><br><span style="font-size:12px;color:#666;">Pusat Logistik Regional</span>`);

    // Force map resize adjustment after load
    setTimeout(() => { map.invalidateSize(); }, 300);

    // 5. Autodraw Rute Jalanan (OSRM Engine)
    if (poskoList.length > 0) {
        const bounds = L.latLngBounds([[bpbdLat, bpbdLng]]);

        poskoList.forEach(posko => {
            const poskoLat = parseFloat(posko.latitude);
            const poskoLng = parseFloat(posko.longitude);

            if (!isNaN(poskoLat) && !isNaN(poskoLng)) {
                // Marker Posko Tujuan
                L.marker([poskoLat, poskoLng], { icon: poskoIcon })
                    .addTo(map)
                    .bindPopup(`<b>${posko.nama_posko}</b><br><span style="font-size:12px;color:#666;">Posko Komando Lapangan</span>`);

                bounds.extend([poskoLat, poskoLng]);

                // Query API OSRM untuk menggambar jalanan otomatis
                const osrmUrl = `https://router.project-osrm.org/route/v1/driving/${bpbdLng},${bpbdLat};${poskoLng},${poskoLat}?overview=full&geometries=geojson`;

                fetch(osrmUrl)
                    .then(response => response.json())
                    .then(data => {
                        if (data.routes && data.routes.length > 0) {
                            const routeCoordinates = data.routes[0].geometry.coordinates;
                            const latLngs = routeCoordinates.map(coord => [coord[1], coord[0]]);

                            // Gambar Rute Jalan Asli
                            L.polyline(latLngs, {
                                color: '#2563eb',
                                weight: 4,
                                opacity: 0.85,
                                lineJoin: 'round'
                            }).addTo(map);
                        }
                    })
                    .catch(err => {
                        console.warn('Gagal memuat rute jalan OSRM, fallback ke garis lurus:', err);
                        L.polyline([[bpbdLat, bpbdLng], [poskoLat, poskoLng]], {
                            color: '#ef4444',
                            weight: 3,
                            dashArray: '5, 5'
                        }).addTo(map);
                    });
            }
        });

        // Autofit Peta ke semua titik lokasi
        map.fitBounds(bounds, { padding: [40, 40] });
    }
});
</script>
@endpush