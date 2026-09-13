@extends('layouts.app')

@section('title', 'Stok Logistik Posko Komando')

@section('content')
<div class="w-full space-y-6 pb-12">

    <!-- JUDUL HALAMAN -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Stok Logistik Posko Komando</h1>
            <p class="text-xs text-slate-500 mt-0.5">Ketersediaan logistik real-time di Posko Komando yang diterima dari BPBD Kab/Kota.</p>
        </div>
        <div>
            <a href="{{ route('komando.pengajuan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Ajukan Tambahan Logistik ke BPBD
            </a>
        </div>
    </div>

    <!-- NOTIFIKASI -->
    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
            <span class="text-xs font-semibold">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif

    <!-- KATALOG RINGKASAN STOK POSKO KOMANDO (12 ITEM) -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <h2 class="text-sm font-bold text-slate-900 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Ketersediaan Stok Logistik Lapangan Saat Ini
        </h2>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Beras</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['beras_kg'] }} <span class="text-xs font-normal text-slate-500">kg</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Air Minum</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['air_minum_dus'] }} <span class="text-xs font-normal text-slate-500">dus</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Makanan Kaleng</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['makanan_kaleng_pack'] }} <span class="text-xs font-normal text-slate-500">pack</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Makanan Bayi</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['makanan_bayi_pack'] }} <span class="text-xs font-normal text-slate-500">pack</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Minyak Goreng</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['minyak_goreng_liter'] }} <span class="text-xs font-normal text-slate-500">liter</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Popok Bayi</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['popok_bayi_pcs'] }} <span class="text-xs font-normal text-slate-500">pcs</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Popok Dewasa</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['popok_dewasa_pcs'] }} <span class="text-xs font-normal text-slate-500">pcs</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Pembalut Wanita</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['pembalut_wanita_pack'] }} <span class="text-xs font-normal text-slate-500">pack</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Hygiene Kit</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['hygiene_kit_paket'] }} <span class="text-xs font-normal text-slate-500">paket</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Selimut</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['selimut_pcs'] }} <span class="text-xs font-normal text-slate-500">pcs</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Matras / Terpal</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['matras_terpal_pcs'] }} <span class="text-xs font-normal text-slate-500">pcs</span></span>
            </div>
            <div class="p-3.5 bg-slate-50 border border-slate-200 rounded-xl">
                <span class="text-[11px] font-semibold text-slate-500 block">Obat P3K</span>
                <span class="text-lg font-extrabold text-slate-900 mt-0.5 block">{{ $stokLogistik['obat_p3k_paket'] }} <span class="text-xs font-normal text-slate-500">paket</span></span>
            </div>
        </div>
    </div>

    <!-- TABEL RIWAYAT SUPLAI DARI BPBD -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="p-4 border-b border-slate-200/80 bg-slate-50/50 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Riwayat Permintaan & Suplai Masuk BPBD</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6">KODE PENGAJUAN</th>
                        <th class="py-3.5 px-6">BENCANA</th>
                        <th class="py-3.5 px-6">TANGGAL DIAJUKAN</th>
                        <th class="py-3.5 px-6">STATUS BPBD</th>
                        <th class="py-3.5 px-6">RINCIAN SUPLAI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($riwayatSuplai as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-bold text-blue-600 whitespace-nowrap">
                                {{ $item->kode_pengajuan }}
                            </td>
                            <td class="py-4 px-6 font-medium text-slate-900">
                                {{ $item->bencana->nama_bencana 
                                    ?? $item->bencana->jenis_bencana 
                                    ?? $item->posko->bencana->nama_bencana 
                                    ?? $item->posko->bencana->jenis_bencana 
                                    ?? '-' }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap text-slate-500">
                                {{ \Carbon\Carbon::parse($item->tanggal_pengajuan ?? $item->created_at)->format('d M Y, H:i') }}
                            </td>
                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($item->status == 'pending')
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-amber-100 text-amber-800 rounded-full border border-amber-200">Menunggu BPBD</span>
                                @elseif(in_array($item->status, ['disetujui', 'dalam_pengiriman', 'selesai']))
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-emerald-100 text-emerald-800 rounded-full border border-emerald-200">Masuk Stok Posko</span>
                                @elseif($item->status == 'dieskalasi_provinsi')
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-purple-100 text-purple-800 rounded-full border border-purple-200">Dieskalasi Prov</span>
                                @else
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-rose-100 text-rose-800 rounded-full border border-rose-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1 text-[11px]">
                                    @if($item->beras_kg > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">Beras: {{ $item->beras_kg }} kg</span> @endif
                                    @if($item->air_minum_dus > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">Air: {{ $item->air_minum_dus }} dus</span> @endif
                                    @if($item->makanan_kaleng_pack > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">Mak Kaleng: {{ $item->makanan_kaleng_pack }} pk</span> @endif
                                    @if($item->obat_p3k_paket > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">P3K: {{ $item->obat_p3k_paket }} pkt</span> @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 italic">Belum ada riwayat suplai dari BPBD.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($riwayatSuplai->hasPages())
            <div class="p-4 border-t border-slate-200/80 bg-slate-50">
                {{ $riwayatSuplai->links() }}
            </div>
        @endif
    </div>

</div>
@endsection