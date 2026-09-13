@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-placement { 
        height: 300px; 
        width: 100%; 
        border-radius: 0.75rem; 
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6 py-4 px-4 sm:px-6">

    <!-- Flash Alert Success & Error -->
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 rounded-xl text-xs font-semibold flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-rose-50 border border-rose-300 text-rose-800 rounded-xl text-xs font-semibold flex items-center justify-between">
            <span>⚠️ {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 font-bold">&times;</button>
        </div>
    @endif

    <!-- Header Halaman -->
    <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-900">Manajemen & Aktivasi Posko Komando Utama</h1>
            <p class="text-xs text-slate-500 mt-0.5">Kelola daftar Posko Komando Utama terdaftar dan hubungkan ke operasi bencana aktif.</p>
        </div>
        <button type="button" onclick="openModalCreate()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-1.5 cursor-pointer">
            <span>➕</span> Tambah Posko Komando Baru
        </button>
    </div>

    <!-- Banner Info Bencana Aktif -->
    @if($bencana)
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 text-white p-5 rounded-2xl shadow-md border border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-400 text-slate-900 uppercase tracking-wider">Target Bencana Perlu Setup</span>
                <h2 class="text-lg font-bold text-white mt-1">{{ $bencana->jenis_bencana }} — <span class="text-slate-300 font-normal">{{ $bencana->lokasi_bencana }}</span></h2>
            </div>
            <div class="text-xs bg-white/10 px-4 py-2 rounded-xl border border-white/10">
                <span>Estimasi Pengungsi: <strong>{{ number_format($bencana->estimasi_pengungsi_awal) }} Jiwa</strong></span>
            </div>
        </div>
    @endif

    <!-- Daftar Posko Komando Terdaftar -->
    <div class="space-y-4">
        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">
            Daftar Posko Komando Terdaftar ({{ count($availablePosko) }})
        </h3>

        @forelse($availablePosko as $posko)
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-indigo-300 transition-all">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 bg-indigo-100 text-indigo-800 font-bold text-[10px] rounded uppercase">POSKO KOMANDO</span>
                        @if($posko->status === 'aktif')
                            <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded uppercase">AKTIF OPERASI</span>
                        @else
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold text-[10px] rounded uppercase">STANDBY</span>
                        @endif
                    </div>
                    
                    <div>
                        <h4 class="text-base font-bold text-slate-900">{{ $posko->nama_posko }}</h4>
                        <p class="text-xs text-slate-500">📍 {{ $posko->lokasi ?? 'Lokasi Belum Diatur' }}</p>
                    </div>

                    <!-- Informasi Akun User Komandan dari DB -->
                    <div class="flex flex-wrap items-center gap-3 text-xs">
                        <span class="bg-slate-100 px-2.5 py-1 rounded-lg text-slate-700">
                            👤 Komandan: <strong>{{ $posko->penanggung_jawab }}</strong> ({{ $posko->kontak_hp ?? '-' }})
                        </span>
                        <span class="bg-indigo-50 px-2.5 py-1 rounded-lg text-indigo-700 font-mono border border-indigo-100">
                            ✉️ Email Login: <strong>{{ $posko->user->email ?? 'Belum ada akun' }}</strong>
                        </span>
                    </div>
                </div>

                <!-- Tombol Pilih Penempatan & Aktifkan (Muncul jika ada bencana) -->
                @if($bencana && $posko->status !== 'aktif')
                    <button type="button" 
                            onclick="openModalPlacement({{ $posko->id }}, '{{ addslashes($posko->nama_posko) }}', '{{ addslashes($posko->lokasi ?? '') }}', {{ $posko->latitude ?? ($bencana->koordinat_operasional_lat ?? -7.7956) }}, {{ $posko->longitude ?? ($bencana->koordinat_operasional_lng ?? 110.3695) }})"
                            class="w-full md:w-auto px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer">
                        📍 Pilih Penempatan GIS & Aktifkan
                    </button>
                @endif
            </div>
        @empty
            <div class="bg-white p-8 rounded-2xl border border-dashed border-slate-300 text-center space-y-3">
                <span class="text-3xl">🏛️</span>
                <h4 class="text-sm font-bold text-slate-800">Belum Ada Posko Komando Terdaftar</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    Silakan daftarkan Posko Komando Utama pertama beserta akun login Komandannya.
                </p>
                <button type="button" onclick="openModalCreate()" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl cursor-pointer">
                    + Tambah Posko Komando Baru
                </button>
            </div>
        @endforelse
    </div>

</div>

<!-- MODAL 1: REGISTRASI POSKO KOMANDO & AKUN KOMANDAN BARU -->
<div id="modalCreatePosko" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b pb-3">
            <h3 class="text-base font-bold text-slate-900">Registrasi Posko Komando & Akun Komandan Baru</h3>
            <button type="button" onclick="closeModalCreate()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.posko.store') }}" method="POST" class="space-y-4">
            @csrf
            @if($bencana)
                <input type="hidden" name="bencana_id" value="{{ $bencana->id }}">
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Posko Komando *</label>
                    <input type="text" name="nama_posko" value="{{ old('nama_posko') }}" required placeholder="Posko Komando Lapangan Sleman" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Nama Komandan / Penanggung Jawab *</label>
                    <input type="text" name="penanggung_jawab" value="{{ old('penanggung_jawab') }}" required placeholder="Mayor Budi Santoso" class="w-full px-3 py-2 border rounded-xl text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 bg-indigo-50/50 p-3.5 rounded-xl border border-indigo-100">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">No. HP / WA Darurat *</label>
                    <input type="text" name="kontak_hp" value="{{ old('kontak_hp') }}" required placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 bg-white border rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-900 uppercase mb-1">Email Login *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="komando@rescuelog.id" class="w-full px-3 py-2 bg-white border border-indigo-300 rounded-xl text-xs font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-indigo-900 uppercase mb-1">Password Login *</label>
                    <input type="password" name="password" required placeholder="Password Akun" class="w-full px-3 py-2 bg-white border border-indigo-300 rounded-xl text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Fisik Posko *</label>
                <textarea name="lokasi" required rows="2" placeholder="Alamat lengkap posko..." class="w-full px-3 py-2 border rounded-xl text-xs">{{ old('lokasi', $bpbd->alamat_kantor ?? '') }}</textarea>
            </div>

            <div class="pt-3 border-t flex justify-end gap-2">
                <button type="button" onclick="closeModalCreate()" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl cursor-pointer">Simpan Posko Komando</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL 2: PILIH PENEMPATAN GIS & AKTIFKAN BENCANA -->
@if($bencana)
<div id="modalPlacement" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full p-6 space-y-4 shadow-2xl max-h-[95vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900">Atur Penempatan GIS Posko</h3>
                <p class="text-xs text-slate-500">Posko: <strong id="modal_posko_title"></strong></p>
            </div>
            <button type="button" onclick="closeModalPlacement()" class="text-slate-400 hover:text-slate-600 font-bold">✕</button>
        </div>

        <form action="{{ route('admin.posko.activate-existing') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="bencana_id" value="{{ $bencana->id }}">
            <input type="hidden" name="posko_id" id="place_posko_id">

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Alamat Deskriptif Lokasi Posko *</label>
                <textarea name="lokasi" id="place_lokasi" required rows="2" class="w-full px-3 py-2 border rounded-xl text-xs"></textarea>
            </div>

            <!-- Leaflet Interactive Map GIS -->
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Pilih Titik Koordinat (Klik / Geser Marker Pada Peta)</label>
                <div id="map-placement"></div>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase">Latitude (Lat)</label>
                    <input type="text" name="latitude" id="place_lat" readonly required class="w-full bg-transparent border-0 p-0 text-xs font-mono font-bold text-slate-800">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase">Longitude (Lng)</label>
                    <input type="text" name="longitude" id="place_lng" readonly required class="w-full bg-transparent border-0 p-0 text-xs font-mono font-bold text-slate-800">
                </div>
            </div>

            <div class="pt-3 border-t flex justify-end gap-2">
                <button type="button" onclick="closeModalPlacement()" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl cursor-pointer">Konfirmasi & Aktifkan Posko</button>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    function openModalCreate() {
        document.getElementById('modalCreatePosko').classList.remove('hidden');
    }
    function closeModalCreate() {
        document.getElementById('modalCreatePosko').classList.add('hidden');
    }

    // PENANGANAN OTOMATIS JIKA PROSES SIMPAN GAGAL / ACCIDENTAL VALIDATION ERROR
    document.addEventListener("DOMContentLoaded", function() {
        @if ($errors->any())
            openModalCreate();
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan Posko',
                    html: '<ul class="text-left text-xs text-red-600 space-y-1">' +
                        @foreach ($errors->all() as $error)
                            '<li>• {{ $error }}</li>' +
                        @endforeach
                        '</ul>',
                    confirmButtonColor: '#4f46e5'
                });
            }
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

        // Render Leaflet Map
        setTimeout(() => {
            if (!mapPlacement) {
                mapPlacement = L.map('map-placement').setView([lat, lng], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(mapPlacement);

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

    function closeModalPlacement() {
        document.getElementById('modalPlacement').classList.add('hidden');
    }
</script>
@endpush