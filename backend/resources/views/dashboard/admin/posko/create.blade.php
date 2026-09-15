@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-sebaran { height: 380px; width: 100%; border-radius: 0.75rem; z-index: 10; }
    #map-placement { height: 300px; width: 100%; border-radius: 0.75rem; z-index: 10; }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Flash Alert Success & Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">✅ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold hover:text-emerald-700">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-semibold flex items-center justify-between shadow-sm">
            <span class="flex items-center gap-2">⚠️ {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 font-bold hover:text-rose-700">&times;</button>
        </div>
    @endif

    <!-- Header Halaman & Action Button -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Aktivasi Posko Komando</h1>
            <p class="text-sm text-slate-500 mt-0.5">Kelola dan atur status aktivasi Posko Komando Utama dalam penanganan bencana.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="openModalCreate()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2 cursor-pointer">
                <span>➕</span>
                <span>Tambah Posko Komando</span>
            </button>
        </div>
    </div>

    <!-- Stat Cards Metrik -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Total Posko</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ count($availablePosko) }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Semua Posko Terdaftar</p>
            </div>
            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-building-office-2 class="w-5 h-5" />
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Posko Aktif</p>
                <h3 class="text-2xl font-black text-emerald-600 mt-1">{{ $availablePosko->where('status', 'aktif')->count() }}</h3>
                <p class="text-[11px] text-emerald-500 font-medium mt-1">
                    {{ count($availablePosko) > 0 ? round(($availablePosko->where('status', 'aktif')->count() / count($availablePosko)) * 100) : 0 }}% dari total posko
                </p>
            </div>
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-check-circle class="w-5 h-5" />
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Standby / Siaga</p>
                <h3 class="text-2xl font-black text-amber-500 mt-1">{{ $availablePosko->where('status', '!=', 'aktif')->count() }}</h3>
                <p class="text-[11px] text-slate-400 mt-1">Siap dialokasikan</p>
            </div>
            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-clock class="w-5 h-5" />
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-400">Target Bencana</p>
                <h3 class="text-sm font-bold text-slate-900 mt-1 truncate max-w-[120px]">{{ $bencana->jenis_bencana ?? 'Tidak Ada' }}</h3>
                <p class="text-[11px] text-slate-400 mt-1 truncate max-w-[120px]">{{ $bencana->lokasi_bencana ?? 'Operasi Standby' }}</p>
            </div>
            <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center shrink-0">
                <x-fas-house-crack class="w-4 h-4" />
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="relative flex-1">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <x-heroicon-s-magnifying-glass class="w-4 h-4" />
            </div>
            <input type="text" id="searchPosko" onkeyup="filterPoskoTable()" placeholder="Cari posko..." 
                   class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">
        </div>

        <div class="flex items-center gap-2">
            <select id="filterStatus" onchange="filterPoskoTable()" class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-3 py-2 outline-none focus:bg-white focus:ring-2 focus:ring-indigo-500">
                <option value="all">Semua Status</option>
                <option value="aktif">Aktif Operasi</option>
                <option value="standby">Standby / Nonaktif</option>
            </select>
            <button type="button" onclick="resetFilter()" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl transition flex items-center gap-1">
                <span>🔄</span> Reset Filter
            </button>
        </div>
    </div>

    <!-- Split Grid: Table (Left) & Live GIS Map (Right) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Daftar Posko Komando</h3>
                <span class="text-xs text-slate-400 font-semibold">{{ count($availablePosko) }} Posko</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="poskoTable">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-3 px-4 text-center w-10">NO</th>
                            <th class="py-3 px-4">NAMA POSKO</th>
                            <th class="py-3 px-4">PENANGGUNG JAWAB</th>
                            <th class="py-3 px-4">AKUN LOGIN</th>
                            <th class="py-3 px-4 text-center">STATUS</th>
                            <th class="py-3 px-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($availablePosko as $index => $posko)
                            <tr class="hover:bg-slate-50/80 transition-all posko-row" data-status="{{ $posko->status }}">
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-400">{{ $index + 1 }}</td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 block">{{ $posko->nama_posko }}</span>
                                    <span class="text-[10px] text-slate-400 block mt-0.5">📍 {{ Str::limit($posko->lokasi ?? 'Lokasi Belum Diatur', 25) }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-slate-800 block">{{ $posko->penanggung_jawab }}</span>
                                    <span class="text-[10px] text-slate-400">📞 {{ $posko->kontak_hp ?? '-' }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-mono text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded text-[11px]">
                                        {{ $posko->user->email ?? 'Belum terikat' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($posko->status === 'aktif')
                                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded-full">
                                            AKTIF
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 font-bold text-[10px] rounded-full">
                                            STANDBY
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <!-- Tombol Detail Posko -->
                                        <a href="{{ route('admin.posko.show', $posko->id) }}" 
                                        class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-[11px] rounded-xl transition flex items-center gap-1 cursor-pointer">
                                            <span>👁️</span> Detail
                                        </a>

                                        @if($bencana && $posko->status !== 'aktif')
                                            <button type="button" 
                                                    onclick="openModalPlacement({{ $posko->id }}, '{{ addslashes($posko->nama_posko) }}', '{{ addslashes($posko->lokasi ?? '') }}', {{ $posko->latitude ?? ($bencana->koordinat_operasional_lat ?? -7.8893) }}, {{ $posko->longitude ?? ($bencana->koordinat_operasional_lng ?? 110.3288) }})"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[11px] rounded-xl transition shadow-sm cursor-pointer">
                                                📍 Beroperasi
                                            </button>
                                        @elseif($posko->status === 'aktif')
                                            <span class="text-[11px] text-emerald-600 font-bold bg-emerald-50 px-2.5 py-1 rounded-xl">Beroperasi</span>
                                        @else
                                            <span class="text-[11px] text-slate-400 font-medium">Standby</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-4 text-center">
                                    <div class="max-w-xs mx-auto space-y-2">
                                        <div class="w-10 h-10 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
                                            <x-heroicon-s-inbox class="w-5 h-5" />
                                        </div>
                                        <h4 class="text-xs font-bold text-slate-800">Belum ada Posko Komando</h4>
                                        <p class="text-[11px] text-slate-400">Silakan tambahkan data posko komando baru.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Live GIS Map Card -->
        <div class="lg:col-span-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Peta Sebaran Posko</h3>
                <span class="text-[11px] text-slate-400">Live GIS Layer</span>
            </div>
            <div id="map-sebaran" class="border border-slate-200 shadow-inner"></div>
            <div class="flex items-center justify-around text-[11px] font-semibold text-slate-600 pt-1">
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full inline-block"></span>
                    <span>Aktif: {{ $availablePosko->where('status', 'aktif')->count() }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 bg-amber-500 rounded-full inline-block"></span>
                    <span>Siaga: {{ $availablePosko->where('status', '!=', 'aktif')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- INCLUDE PARTIAL MODALS -->
@include('dashboard.admin.posko.modals.create-modal')
@include('dashboard.admin.posko.modals.placement-modal')

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function openModalCreate() { document.getElementById('modalCreatePosko').classList.remove('hidden'); }
    function closeModalCreate() { document.getElementById('modalCreatePosko').classList.add('hidden'); }

    function filterPoskoTable() {
        const searchInput = document.getElementById('searchPosko').value.toLowerCase();
        const filterStatus = document.getElementById('filterStatus').value;
        const rows = document.querySelectorAll('.posko-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            const status = row.getAttribute('data-status');
            const matchesSearch = text.includes(searchInput);
            const matchesStatus = (filterStatus === 'all') || 
                                  (filterStatus === 'aktif' && status === 'aktif') || 
                                  (filterStatus === 'standby' && status !== 'aktif');
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    function resetFilter() {
        document.getElementById('searchPosko').value = '';
        document.getElementById('filterStatus').value = 'all';
        filterPoskoTable();
    }

    document.addEventListener("DOMContentLoaded", function() {
        // 1. Kunci Titik Pusat Tetap di Kantor Gudang Utama BPBD Bantul
        const bpbdLat = {{ $bpbd?->latitude ?? -7.8893 }};
        const bpbdLng = {{ $bpbd?->longitude ?? 110.3288 }};

        const mapSebaran = L.map('map-sebaran').setView([bpbdLat, bpbdLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(mapSebaran);

        setTimeout(() => { mapSebaran.invalidateSize(); }, 300);

        // ==========================================
        // 1. LAYER BPBD KABUPATEN BANTUL (Gudang Utama)
        // ==========================================
        const bpbdNama = "{{ addslashes($bpbd->nama_kabupaten_kota ?? 'BPBD Kabupaten Bantul') }}";
        const bpbdAlamat = "{{ addslashes($bpbd->alamat_kantor ?? 'Jl. Jend. A. Yani No. 1, Badegan, Bantul') }}";

        const bpbdIcon = L.divIcon({
            className: 'custom-bpbd-icon',
            html: `<div class="w-7 h-7 bg-amber-600 rounded-full border-2 border-white shadow-xl flex items-center justify-center text-white text-[11px] font-bold">🏛️</div>`,
            iconSize: [28, 28],
            iconAnchor: [14, 14]
        });

        L.marker([bpbdLat, bpbdLng], { icon: bpbdIcon })
            .addTo(mapSebaran)
            .bindPopup(`<b>🏛️ ${bpbdNama}</b><br><span class="text-xs text-slate-500">Gudang Logistik Induk BPBD</span><br><small>${bpbdAlamat}</small>`);

        // ==========================================
        // 2. LAYER POSKO KOMANDO UTAMA (HANYA YANG AKTIF)
        // ==========================================
        const komandoIcon = L.divIcon({
            className: 'custom-komando-icon',
            html: `<div class="w-6 h-6 bg-indigo-600 rounded-full border-2 border-white shadow-md flex items-center justify-center text-white text-[10px] font-bold">🏢</div>`,
            iconSize: [24, 24], 
            iconAnchor: [12, 12]
        });

        @foreach($availablePosko as $p)
            @if($p->status === 'aktif' && $p->latitude && $p->longitude)
                L.marker([{{ $p->latitude }}, {{ $p->longitude }}], { icon: komandoIcon })
                    .addTo(mapSebaran)
                    .bindPopup("<b>🏢 {{ addslashes($p->nama_posko) }} (Posko Komando)</b><br>PJ: {{ addslashes($p->penanggung_jawab) }}<br>Status: AKTIF OPERASI");
            @endif
        @endforeach

        // ==========================================
        // 3. LAYER SUB-POSKO LAPANGAN (HIJAU)
        // ==========================================
        const subIcon = L.divIcon({
            className: 'custom-sub-icon',
            html: `<div class="w-5 h-5 bg-emerald-600 rounded-full border-2 border-white shadow-md flex items-center justify-center text-white text-[9px] font-bold">⛺</div>`,
            iconSize: [20, 20], 
            iconAnchor: [10, 10]
        });

        @foreach($subPoskoList ?? [] as $sp)
            @if($sp->latitude && $sp->longitude)
                L.marker([{{ $sp->latitude }}, {{ $sp->longitude }}], { icon: subIcon })
                    .addTo(mapSebaran)
                    .bindPopup("<b>⛺ {{ addslashes($sp->nama_posko) }} (Sub-Posko Lapangan)</b><br>PJ: {{ addslashes($sp->penanggung_jawab) }}<br>Petugas: {{ $sp->jumlah_petugas ?? 0 }} Jiwa");
            @endif
        @endforeach

        @if ($errors->any())
            openModalCreate();
        @endif
    });

    let mapPlacement, placementMarker;

    function openModalPlacement(poskoId, poskoName, lokasi, lat, lng) {
        document.getElementById('place_posko_id').value = poskoId;
        document.getElementById('modal_posko_title').innerText = poskoName;
        document.getElementById('place_lokasi').value = lokasi;
        document.getElementById('place_lat').value = lat;
        document.getElementById('place_lng').value = lng;
        document.getElementById('modalPlacement').classList.remove('hidden');

        setTimeout(() => {
            if (!mapPlacement) {
                mapPlacement = L.map('map-placement').setView([lat, lng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(mapPlacement);
                placementMarker = L.marker([lat, lng], { draggable: true }).addTo(mapPlacement);
                
                placementMarker.on('dragend', function (e) {
                    let position = placementMarker.getLatLng();
                    document.getElementById('place_lat').value = position.lat.toFixed(7);
                    document.getElementById('place_lng').value = position.lng.toFixed(7);
                });
                
                mapPlacement.on('click', function (e) {
                    placementMarker.setLatLng(e.latlng);
                    document.getElementById('place_lat').value = e.latlng.lat.toFixed(7);
                    document.getElementById('place_lng').value = e.latlng.lng.toFixed(7);
                });
            } else {
                mapPlacement.invalidateSize();
                mapPlacement.setView([lat, lng], 13);
                placementMarker.setLatLng([lat, lng]);
            }
        }, 200);
    }
    function closeModalPlacement() { document.getElementById('modalPlacement').classList.add('hidden'); }
</script>
@endpush