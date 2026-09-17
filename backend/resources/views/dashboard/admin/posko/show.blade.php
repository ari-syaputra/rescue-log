@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-detail { 
        width: 100%; 
        border-radius: 0.75rem; 
        z-index: 10;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">

    <!-- Header Navigation & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <a href="{{ url()->previous() }}"
                class="p-2 rounded-xl bg-white border border-slate-300 text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition-all shadow-xs shrink-0 mt-0.5"
                title="Kembali">
                <x-heroicon-s-arrow-left class="w-5 h-5" />
            </a>

            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $posko->nama_posko }}</h1>
                    @if($posko->status === 'aktif')
                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 font-bold text-[10px] rounded-full">AKTIF</span>
                    @else
                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 font-bold text-[10px] rounded-full">STANDBY</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Detail informasi operasional, koordinat GIS, dan buffer stok logistik posko komando.</p>
            </div>
        </div>
    </div>

<!-- Overview Grid Info & Map -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
    
    <!-- Info Detail Posko (7 Cols) -->
<!-- Info Detail Posko (7 Cols) -->
<div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
    <div>
        <!-- Header Card (Tetap Besar dengan Ikon Biru) -->
        <h3 class="text-base font-bold text-slate-900 border-b border-slate-200 pb-3 flex items-center gap-2.5">
            <x-heroicon-s-building-office-2 class="w-5 h-5 text-blue-600 shrink-0" />
            <span>Informasi Identitas & Penanggung Jawab</span>
        </h3>

        <!-- Grid Konten Detail ( pt-4 untuk ruang extra, text-sm untuk isi teks dasar) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mt-4 pt-4 text-sm">
            
            <!-- Kolom Kiri -->
            <div class="space-y-4.5">
                <div>
                    <!-- Label Judul dinaikkan ke text-xs -->
                    <span class="text-slate-400 text-xs font-medium block">Nama Posko:</span>
                    <!-- Isi Penjelasan dinaikkan ke text-base (untuk nama utama) -->
                    <span class="font-bold text-slate-900 text-base leading-tight block mt-1">{{ $posko->nama_posko }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium block">Komandan / Penanggung Jawab:</span>
                    <!-- Isi Penjelasan dinaikkan ke text-sm -->
                    <span class="font-semibold text-slate-900 block mt-1">{{ $posko->penanggung_jawab }}</span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium block">Kontak No. HP / WA:</span>
                    <span class="font-semibold text-slate-900 flex items-center gap-2 mt-1.5">
                        <x-heroicon-s-phone class="w-4 h-4 text-slate-600 shrink-0" />
                        <span>{{ $posko->kontak_hp ?? '-' }}</span>
                    </span>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4.5">
                <div>
                    <span class="text-slate-400 text-xs font-medium block">Akun Access Key Login:</span>
                    <span class="font-mono text-slate-900 flex items-center gap-2 font-medium mt-1.5">
                        <x-heroicon-s-envelope class="w-4 h-4 text-slate-600 shrink-0" />
                        <span class="truncate">{{ $posko->user->email ?? 'Belum terikat' }}</span>
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium block">Operasi Bencana Terkait:</span>
                    <!-- Badge teks juga sedikit disesuaikan ukurannya -->
                    <span class="font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-lg text-xs inline-block mt-1.5 border border-rose-100">
                        {{ $posko->bencana->jenis_bencana ?? 'Tidak ada operasi berjalan' }}
                    </span>
                </div>
                <div>
                    <span class="text-slate-400 text-xs font-medium block">Alamat Fisik Posko:</span>
                    <span class="font-medium text-slate-800 flex items-start gap-2 mt-1.5">
                        <x-heroicon-s-map-pin class="w-4 h-4 text-slate-600 shrink-0 mt-0.5" />
                        <span class="leading-relaxed">{{ $posko->lokasi ?? 'Belum diatur' }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Peta GIS Mini (5 Cols) -->
    <div class="lg:col-span-5 bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <!-- Header Card (Diperbesar & Ikon Biru) -->
        <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-3">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                <x-heroicon-s-map class="w-5 h-5 text-blue-600 shrink-0" />
                <span>Titik GIS Posko</span>
            </h3>
            <span class="text-[10px] text-slate-400 font-mono">{{ $posko->latitude }}, {{ $posko->longitude }}</span>
        </div>
        <div id="map-detail" class="w-full flex-1 min-h-[190px] border border-slate-200 shadow-inner rounded-xl"></div>
    </div>

</div>

<!-- Tabel Buffer Stok Logistik Posko Komando -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <!-- Header dengan Heroicon Biru 600 -->
                <h3 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                    <x-heroicon-s-archive-box class="w-5 h-5 text-blue-600 shrink-0" />
                    <span>Inventaris Buffer Stok Logistik Posko</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Ketersediaan barang yang dikuasai Posko Komando untuk kebutuhan distribusi darurat.</p>
            </div>
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-100">
                Total {{ count($posko->stokInventaris) }} dari 12 Jenis Item Utama Baku
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <!-- Warna Header Tabel Disesuaikan -->
                    <tr class="border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider bg-slate-200/90">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">NAMA BARANG</th>
                        <th class="py-3.5 px-4">KATEGORI</th>
                        <th class="py-3.5 px-4 text-center">JUMLAH STOK</th>
                        <th class="py-3.5 px-4 text-center">SATUAN</th>
                        <th class="py-3.5 px-4 text-center">KETERANGAN ALOKASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($posko->stokInventaris as $idx => $stok)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400 text-xs">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">{{ $stok->nama_barang }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-600 text-xs">
                                {{ $stok->kategori }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-extrabold text-emerald-600 text-base">
                                {{ number_format($stok->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-600 text-xs">{{ $stok->satuan }}</td>
                            <td class="py-3.5 px-4 text-center text-slate-600 italic text-xs leading-relaxed">{{ $stok->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-sm">
                                Belum ada alokasi stok logistik pada posko ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const lat = {{ $posko->latitude ?? -7.7956 }};
        const lng = {{ $posko->longitude ?? 110.3695 }};
        
        const mapDetail = L.map('map-detail').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(mapDetail);

        L.marker([lat, lng])
            .addTo(mapDetail)
            .bindPopup("<b>{{ addslashes($posko->nama_posko) }}</b><br>PJ: {{ addslashes($posko->penanggung_jawab) }}")
            .openPopup();

        // Mencegah peta terdistorsi setelah container disesuaikan ukurannya secara flexbox
        setTimeout(() => {
            mapDetail.invalidateSize();
        }, 200);
    });
</script>
@endpush