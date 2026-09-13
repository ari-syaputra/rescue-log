@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<style>
    #map-detail { 
        height: 260px; 
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
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posko.create') }}" class="w-9 h-9 bg-white border border-slate-200 text-slate-600 rounded-xl flex items-center justify-center hover:bg-slate-50 transition shadow-sm font-bold">
                ←
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
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Info Detail Posko (8 Cols) -->
        <div class="lg:col-span-8 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6">
            <h3 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                <span>🏛️</span> Informasi Identitas & Penanggung Jawab
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <div class="space-y-3">
                    <div>
                        <span class="text-slate-400 font-medium block">Nama Posko:</span>
                        <span class="font-bold text-slate-900 text-sm">{{ $posko->nama_posko }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Komandan / Penanggung Jawab:</span>
                        <span class="font-bold text-slate-800">{{ $posko->penanggung_jawab }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Kontak No. HP / WA:</span>
                        <span class="font-semibold text-slate-800">📞 {{ $posko->kontak_hp ?? '-' }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div>
                        <span class="text-slate-400 font-medium block">Akun Access Key Login:</span>
                        <span class="font-mono text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg inline-block font-semibold mt-0.5">
                            ✉️ {{ $posko->user->email ?? 'Belum terikat' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Operasi Bencana Terkait:</span>
                        <span class="font-bold text-rose-600 bg-rose-50 px-2.5 py-0.5 rounded inline-block mt-0.5">
                            {{ $posko->bencana->jenis_bencana ?? 'Tidak ada operasi berjalan' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block">Alamat Fisik Posko:</span>
                        <span class="font-semibold text-slate-700">📍 {{ $posko->lokasi ?? 'Belum diatur' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peta GIS Mini (4 Cols) -->
        <div class="lg:col-span-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <h3 class="text-xs font-bold text-slate-900">Titik GIS Posko</h3>
                <span class="text-[10px] text-slate-400 font-mono">{{ $posko->latitude }}, {{ $posko->longitude }}</span>
            </div>
            <div id="map-detail" class="border border-slate-200 shadow-inner"></div>
        </div>

    </div>

    <!-- Tabel Buffer Stok Logistik Posko Komando -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden space-y-4 p-6">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">📦 Inventaris Buffer Stok Logistik Posko</h3>
                <p class="text-xs text-slate-500">Ketersediaan barang yang dikuasai Posko Komando untuk kebutuhan distribusi darurat.</p>
            </div>
            <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-xl border border-indigo-100">
                Total {{ count($posko->stokInventaris) }} Jenis Item
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center w-12">NO</th>
                        <th class="py-3.5 px-4">NAMA BARANG</th>
                        <th class="py-3.5 px-4">KATEGORI</th>
                        <th class="py-3.5 px-4 text-right">JUMLAH STOK</th>
                        <th class="py-3.5 px-4">SATUAN</th>
                        <th class="py-3.5 px-4">KETERANGAN ALOKASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($posko->stokInventaris as $idx => $stok)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $stok->nama_barang }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full font-semibold text-[11px]">
                                    {{ $stok->kategori }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right font-bold text-emerald-600 text-sm">
                                {{ number_format($stok->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-500">{{ $stok->satuan }}</td>
                            <td class="py-3.5 px-4 text-slate-500 italic">{{ $stok->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                                Belum ada alokasi stok logistik pada posko ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
    });
</script>
@endpush