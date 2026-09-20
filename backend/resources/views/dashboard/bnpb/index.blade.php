@extends('layouts.app')

@section('title', 'National Command Center - BNPB Indonesia')

@section('content')
<div class="space-y-5">

    <!-- 1. Hero Banner Highlight Bencana Utama Skala Nasional -->
    <div class="relative rounded-2xl overflow-hidden bg-slate-900 shadow-2xl border border-slate-800 text-white">
        <!-- Background Image Decorative overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-blue-950 via-slate-900/90 to-blue-900/40 z-10"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542314831-068cd1dbfeeb?auto=format&fit=crop&w=1200&q=80')] bg-cover bg-center opacity-20"></div>

        <div class="relative z-20 p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="space-y-3 max-w-3xl">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-3 py-1 bg-rose-600 text-white font-black text-[10px] uppercase tracking-wider rounded-lg flex items-center gap-1.5 shadow-md">
                        <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                        STATUS DARURAT NASIONAL
                    </span>
                    <span class="text-xs text-blue-200 font-semibold">• Pusdalops PB Indonesia</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                    {{ $bencanaUtama->jenis_bencana ?? 'Siaga Darurat Kebencanaan Nasional' }}
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 font-medium">
                    {{ $bencanaUtama->lokasi_bencana ?? 'Wilayah Republik Indonesia' }} — Pemantauan Terpadu Agregat Tanggap Darurat & Distribusi Logistik Bencana.
                </p>

                <div class="flex flex-wrap items-center gap-4 pt-1 text-xs">
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10">
                        <x-heroicon-s-exclamation-triangle class="w-4 h-4 text-amber-400" />
                        <span>Status: <strong class="text-amber-300">Tanggap Darurat</strong></span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10">
                        <x-heroicon-s-clock class="w-4 h-4 text-sky-400" />
                        <span>Update Terakhir: <strong class="text-sky-300">{{ now()->translatedFormat('d M Y, H:i') }} WIB</strong></span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('bnpb.monitoring') }}" class="px-5 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs uppercase tracking-wider rounded-xl transition shadow-lg flex items-center gap-2 border border-blue-400/30 cursor-pointer">
                    <x-heroicon-s-globe-alt class="w-4 h-4" />
                    <span>Peta GIS Nasional</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. KPI Metrics Cards Nasional (5 Cards Grid) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
        
        <!-- Metric 1: Total Bencana Aktif -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-exclamation-triangle class="w-5 h-5" />
                </div>
                <span class="text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md">Nasional</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Bencana Aktif</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($totalBencanaAktif) }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">Status Tanggap Darurat</p>
            </div>
        </div>

        <!-- Metric 2: Korban & Jiwa Terdampak -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-user-group class="w-5 h-5" />
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-md">Agregat</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jiwa Terdampak</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($bencanaUtama->total_jiwa_terdampak ?? 1250) }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">Terdata di Wilayah Bencana</p>
            </div>
        </div>

        <!-- Metric 3: Total Pengungsi -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-home class="w-5 h-5" />
                </div>
                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">Di Posko</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengungsi</p>
                <h3 class="text-2xl font-black text-amber-600 mt-0.5">{{ number_format($totalPengungsiNasional) }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">Mengungsi di {{ $totalPoskoOperasional }} Posko</p>
            </div>
        </div>

        <!-- Metric 4: Stok Logistik Nasional -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-archive-box class="w-5 h-5" />
                </div>
                <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Gudang Utama</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Stok Logistik Nasional</p>
                <h3 class="text-2xl font-black text-slate-800 mt-0.5">{{ number_format($totalStokNasional) }} <span class="text-xs font-normal text-slate-400">Unit</span></h3>
                <p class="text-[10px] text-slate-400 mt-1">Ready for Dispatch</p>
            </div>
        </div>

        <!-- Metric 5: Permintaan Eskalasi Pusat -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-xs space-y-2">
            <div class="flex items-center justify-between">
                <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                    <x-heroicon-s-arrow-up-tray class="w-5 h-5" />
                </div>
                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded-md">Pusat</span>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Eskalasi Nasional</p>
                <h3 class="text-2xl font-black text-purple-600 mt-0.5">{{ $totalEskalasiBantuan }}</h3>
                <p class="text-[10px] text-slate-400 mt-1">Perlu Intervensi BNPB</p>
            </div>
        </div>

    </div>

    <!-- 3. Section Tengah: Peta Sebaran Bencana Nasional (2/3) + Side Panel (1/3) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Column Kiri (2/3): Peta GIS Indonesia -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-map class="w-5 h-5 text-blue-600" />
                    <h3 class="font-bold text-slate-800 text-sm">Peta Sebaran Bencana Nasional - Indonesia</h3>
                </div>
                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    National Live GIS
                </span>
            </div>

            <!-- Leaflet Container Peta Indonesia -->
            <div class="relative flex-1 min-h-[400px] bg-slate-900" id="mapNasional"></div>
        </div>

        <!-- Column Kanan (1/3): Feed Bencana Terbaru & Rekapitulasi -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex flex-col space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <x-heroicon-s-bell class="w-5 h-5 text-amber-500" />
                    <span>Bencana Terbaru</span>
                </h3>
            </div>

            <!-- List Kejadian Bencana Terbaru -->
            <div class="space-y-2.5 flex-1 overflow-y-auto max-h-[220px] pr-1">
                @forelse ($bencanaList as $b)
                    <div class="p-3 rounded-xl border border-slate-100 bg-slate-50/60 hover:bg-slate-50 transition flex items-center justify-between">
                        <div>
                            <span class="font-bold text-xs text-slate-800 block">{{ $b->jenis_bencana }}</span>
                            <span class="text-[11px] text-slate-500 block mt-0.5">{{ $b->lokasi_bencana }}</span>
                        </div>
                        <span class="px-2 py-1 bg-rose-100 text-rose-700 font-bold text-[10px] rounded-lg">
                            Darurat
                        </span>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 italic text-center py-4">Belum ada kejadian bencana aktif.</p>
                @endforelse
            </div>

            <!-- Rekapitulasi Distribusi Logistik Nasional -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider">Distribusi Logistik Nasional</h4>
                
                <div class="space-y-2 text-xs text-slate-600">
                    <div>
                        <div class="flex justify-between items-center text-[11px] mb-1">
                            <span>Sembako & Makanan</span>
                            <span class="font-bold text-slate-800">75% (Siap Suplai)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-blue-600 h-full w-[75%]"></div>
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center text-[11px] mb-1">
                            <span>Tenda & Hunian Darurat</span>
                            <span class="font-bold text-slate-800">60%</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-amber-500 h-full w-[60%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 4. Section Bawah: Status Bencana Per Provinsi & Peringatan Dini BMKG -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Matriks Status Provinsi (2/3 Span) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">Status Penanggulangan Bencana per Provinsi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Pemantauan Agregat Daerah BPBD Tingkat I</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px]">
                        <tr>
                            <th class="p-3.5">Provinsi / BPBD</th>
                            <th class="p-3.5 text-center">Bencana Aktif</th>
                            <th class="p-3.5 text-center">Pengungsi</th>
                            <th class="p-3.5 text-center">Status Regional</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($provinsiStats as $prov)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="p-3.5 font-bold text-slate-800">
                                    {{ $prov->nama_kabupaten_kota }}
                                </td>
                                <td class="p-3.5 text-center font-bold text-rose-600">
                                    {{ $prov->total_bencana }}
                                </td>
                                <td class="p-3.5 text-center font-bold text-slate-800">
                                    {{ number_format($prov->total_pengungsi) }} Jiwa
                                </td>
                                <td class="p-3.5 text-center">
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-md">
                                        Siaga / Normal
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-slate-400 italic">
                                    Data provinsi belum dikonfigurasi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Panel Informasi Peringatan Dini (1/3 Span) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 space-y-3">
            <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                <x-heroicon-s-megaphone class="w-5 h-5 text-rose-500" />
                <span>Peringatan Dini & Info BMKG</span>
            </h3>

            <div class="space-y-2.5">
                <div class="p-3 bg-rose-50 border border-rose-100 rounded-xl text-xs space-y-1">
                    <span class="font-bold text-rose-800 block">Peringatan Dini Cuaca Ekstrem</span>
                    <p class="text-[11px] text-rose-700 leading-relaxed">Potensi hujan lebat disertai angin kencang di wilayah Pulau Jawa & Sumatera Bagian Selatan.</p>
                </div>

                <div class="p-3 bg-blue-50 border border-blue-100 rounded-xl text-xs space-y-1">
                    <span class="font-bold text-blue-800 block">Update Aktivitas Gunung Api</span>
                    <p class="text-[11px] text-blue-700 leading-relaxed">Aktivitas vulkanik dalam tingkat Awas / Siaga di wilayah timur Indonesia.</p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Leaflet GIS Script untuk Peta Indonesia -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Peta Indonesia (Center di Indonesia: lat -2.5, lng 118)
        const mapNasional = L.map('mapNasional').setView([-2.548926, 118.0148634], 5);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(mapNasional);

        // Render Marker Bencana Seluruh Indonesia
        @foreach($bencanaList as $b)
            @if($b->koordinat_operasional_lat && $b->koordinat_operasional_lng)
                L.marker([{{ $b->koordinat_operasional_lat }}, {{ $b->koordinat_operasional_lng }}])
                    .addTo(mapNasional)
                    .bindPopup("<b>{{ $b->jenis_bencana }}</b><br>{{ $b->lokasi_bencana }}");
            @endif
        @endforeach
    });
</script>
@endsection