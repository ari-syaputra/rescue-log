@extends('layouts.app')

@section('title', 'Pusat Komando & Eskalasi Regional - BPBD Provinsi')

@section('content')
<div class="space-y-5">

    <!-- 1. Header Command Center -->
    <div class="bg-slate-900 rounded-2xl p-4 sm:p-5 text-white shadow-xl flex flex-col lg:flex-row lg:items-center justify-between gap-4 border border-slate-800">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center shrink-0">
                <img src="{{ asset('img/Rescue-log.png') }}" alt="Logo" class="w-8 h-8 object-contain">
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-black text-white tracking-tight">Pusat Komando & Eskalasi Regional</h1>
                </div>
                <p class="text-xs text-slate-400 mt-0.5">Badan Nasional Penanggulangan Bencana - BPBD Provinsi D.I. Yogyakarta</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <div class="px-3 py-1.5 bg-rose-500/20 border border-rose-500/40 rounded-xl flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                <span class="text-[11px] font-black uppercase text-rose-300 tracking-wider">STATUS OPERASIONAL: TANGGAP DARURAT</span>
            </div>

            <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-800 rounded-xl text-[11px] text-slate-300 border border-slate-700">
                <x-heroicon-o-arrow-path class="w-4 h-4 text-blue-400 animate-spin" />
                <span>Sinkronisasi: {{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
            </div>

            <span class="px-3 py-1.5 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 text-[11px] font-bold rounded-xl flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Sistem Online
            </span>
        </div>
    </div>

    <!-- 2. Ringkasan KPI Metrics Cards (6 Grid Layout) -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        
        <!-- Metric 1: Bencana Aktif -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-exclamation-triangle class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Bencana Aktif</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-slate-800">{{ number_format($totalBencanaRegional) }}</h3>
                    <span class="text-[10px] font-bold text-rose-600">↑ 1</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Kab/Kota Terdampak -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-map class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Kab/Kota Terdampak</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-slate-800">1 <span class="text-xs font-normal text-slate-400">/ {{ $totalKabKota }}</span></h3>
                </div>
            </div>
        </div>

        <!-- Metric 3: Posko Aktif -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-building-office-2 class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Posko Evakuasi</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-slate-800">{{ number_format($totalPoskoRegional) }}</h3>
                </div>
            </div>
        </div>

        <!-- Metric 4: Pengungsi -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-user-group class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Total Pengungsi</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-slate-800">{{ number_format($totalPengungsiRegional) }}</h3>
                    <span class="text-[10px] font-semibold text-slate-400">Jiwa</span>
                </div>
            </div>
        </div>

        <!-- Metric 5: Permintaan Eskalasi -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-arrow-up-tray class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Eskalasi Masuk</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-purple-600">{{ $eskalasiMasuk->count() }}</h3>
                    <span class="text-[10px] font-semibold text-slate-400">Usulan</span>
                </div>
            </div>
        </div>

        <!-- Metric 6: Armada Lapangan -->
        <div class="bg-white rounded-2xl p-3.5 border border-slate-100 shadow-xs flex items-center gap-3">
            <div class="w-10 h-10 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center shrink-0">
                <x-heroicon-s-truck class="w-5 h-5" />
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider truncate">Aset Lapangan</p>
                <div class="flex items-baseline gap-1.5">
                    <h3 class="text-lg font-black text-slate-800">12</h3>
                    <span class="text-[10px] font-semibold text-slate-400">Armada</span>
                </div>
            </div>
        </div>

    </div>

    <!-- 3. Section Tengah: Peta GIS Situasi (Kiri) + Widget Situational Awareness (Kanan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- Column Kiri (2/3): Peta GIS Situasi Bencana Regional -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden flex flex-col">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-map class="w-5 h-5 text-blue-600" />
                    <h3 class="font-bold text-slate-800 text-sm">Peta Situasi Bencana - Provinsi D.I. Yogyakarta</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Live Map
                    </span>
                </div>
            </div>

            <!-- Leaflet Container -->
            <div class="relative flex-1 min-h-[380px] bg-slate-900" id="mapProvinsi">
            </div>
        </div>

        <!-- Column Kanan (1/3): Situational Awareness & Kejadian Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-xs p-4 flex flex-col space-y-4">
            <div class="border-b border-slate-100 pb-3 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <x-heroicon-s-viewfinder-circle class="w-5 h-5 text-indigo-600" />
                    <h3 class="font-bold text-slate-800 text-sm">Situational Awareness</h3>
                </div>
            </div>

            <!-- Alert Alert Ringkasan Bencana -->
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl">
                <div class="flex items-start gap-2.5">
                    <x-heroicon-s-exclamation-circle class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" />
                    <div>
                        <h4 class="font-bold text-xs text-rose-800">1 Bencana Aktif Berlangsung</h4>
                        <p class="text-[11px] text-rose-700 mt-0.5 leading-snug">Gempa Bumi & Tanah Longsor berdampak di wilayah Kabupaten Bantul.</p>
                    </div>
                </div>
            </div>

            <!-- Kartu Statistik Korban -->
            <div class="grid grid-cols-2 gap-2">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Penduduk Terdampak</p>
                    <p class="text-base font-black text-slate-800 mt-0.5">1.250 <span class="text-[10px] font-normal text-slate-500">Jiwa</span></p>
                </div>
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="text-[10px] font-bold text-slate-400 uppercase">Status Penanganan</p>
                    <span class="inline-block mt-1 px-2 py-0.5 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">
                        Dalam Proses
                    </span>
                </div>
            </div>

            <!-- Daftar Kejadian Terbaru per Kabupaten -->
            <div class="flex-1 space-y-2">
                <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider">Kejadian Terdata</h4>
                <div class="space-y-2">
                    @forelse ($bencanaRegional as $bencana)
                        <div class="p-2.5 rounded-xl border border-slate-100 bg-slate-50/60 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-bold text-slate-800 block">{{ $bencana->jenis_bencana }}</span>
                                <span class="text-[10px] text-slate-500">{{ $bencana->lokasi_bencana }}</span>
                            </div>
                            <span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded-md">
                                Darurat
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 italic">Tidak ada bencana aktif.</p>
                    @endforelse
                </div>
            </div>

            <!-- Widget Ringkas Ketersediaan Armada -->
            <div class="pt-2 border-t border-slate-100">
                <h4 class="font-bold text-xs text-slate-700 uppercase tracking-wider mb-2">Armada & Sumber Daya</h4>
                <div class="space-y-1.5 text-xs text-slate-600">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px]">Truk Logistik</span>
                        <span class="font-bold text-slate-800">4 / 8 Unit</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-600 h-full w-[50%]"></div>
                    </div>

                    <div class="flex justify-between items-center pt-1">
                        <span class="text-[11px]">Ambulans Reaksi Cepat</span>
                        <span class="font-bold text-slate-800">3 / 6 Unit</span>
                    </div>
                    <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-emerald-600 h-full w-[50%]"></div>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- 4. Section Bawah: Tabel Eskalasi Logistik Kab/Kota -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-bold text-slate-800 text-sm flex items-center gap-2">
                    <x-heroicon-s-archive-box class="w-5 h-5 text-amber-500" />
                    <span>Permintaan Eskalasi Logistik Kab/Kota</span>
                </h3>
                <p class="text-xs text-slate-500 mt-0.5">Pengajuan bantuan logistik tambahan dari BPBD Kabupaten/Kota saat persediaan daerah kritis</p>
            </div>
            <a href="{{ route('provinsi.eskalasi.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition text-center">
                Kelola Semua Eskalasi →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px]">
                    <tr>
                        <th class="p-3.5">No / Kode</th>
                        <th class="p-3.5">Kabupaten / Kota</th>
                        <th class="p-3.5">Status Pengajuan</th>
                        <th class="p-3.5 text-center">Aksi Respon Provinsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($eskalasiMasuk as $index => $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-3.5 font-bold text-slate-700">
                                {{ $index + 1 }}. <span class="text-blue-600">{{ $item->kode_pengajuan }}</span>
                            </td>
                            <td class="p-3.5">
                                <span class="font-bold text-slate-800 block">{{ $item->user->name ?? 'BPBD Kabupaten' }}</span>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}</span>
                            </td>
                            <td class="p-3.5">
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">
                                    Menunggu Persetujuan Provinsi
                                </span>
                            </td>
                            <td class="p-3.5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('provinsi.eskalasi.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[11px] transition cursor-pointer shadow-xs">
                                            ACC Stok Provinsi
                                        </button>
                                    </form>
                                    <form action="{{ route('provinsi.eskalasi.bnpb', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-[11px] transition cursor-pointer shadow-xs">
                                            Teruskan BNPB
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 italic">
                                Belum ada pengajuan eskalasi masuk dari BPBD Kabupaten/Kota saat ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Leaflet GIS Script Initialization -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi Peta Provinsi D.I. Yogyakarta
        const map = L.map('mapProvinsi').setView([-7.8011945, 110.364917], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Marker Bencana
        @foreach($bencanaRegional as $b)
            @if($b->koordinat_operasional_lat && $b->koordinat_operasional_lng)
                L.marker([{{ $b->koordinat_operasional_lat }}, {{ $b->koordinat_operasional_lng }}])
                    .addTo(map)
                    .bindPopup("<b>{{ $b->jenis_bencana }}</b><br>{{ $b->lokasi_bencana }}");
            @endif
        @endforeach
    });
</script>
@endsection