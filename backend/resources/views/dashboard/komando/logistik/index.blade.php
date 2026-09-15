@extends('layouts.app')

@section('title', 'Stok Logistik Posko Komando')

@section('content')
    <div class="w-full space-y-6 pb-12">

        <!-- JUDUL HALAMAN -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Stok Logistik Posko Komando</h1>
                <p class="text-xs text-slate-500 mt-0.5">Ketersediaan logistik real-time di Posko Komando yang diterima dari
                    BPBD Kab/Kota.</p>
            </div>
            <div>
                <a href="{{ route('komando.pengajuan.index') }}"
                    class="w-full md:w-auto shrink-0 h-10 inline-flex items-center justify-center px-4 py-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-800 text-white text-sm font-medium rounded-lg hover:shadow-md focus:ring-4 focus:ring-blue-200 transition shadow-sm whitespace-nowrap cursor-pointer">
                    <x-heroicon-o-plus class="w-4 h-4 mr-2 stroke-[2.5]" />
                    Tambahan Logistik ke BPBD
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200/80 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <x-heroicon-o-check-circle class="w-5 h-5 text-emerald-600 shrink-0" />
                    <span class="text-xs font-semibold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="text-emerald-500 hover:text-emerald-700 font-bold text-lg leading-none">&times;</button>
            </div>
        @endif

        <!-- KATALOG RINGKASAN STOK POSKO KOMANDO (12 ITEM) -->
        <div class="bg-white p-6 rounded-2xl border border-slate-200/70 shadow-xs">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                        <x-heroicon-o-cube class="w-4 h-4" />
                    </div>
                    Ketersediaan Stok Logistik Lapangan Saat Ini
                </h2>
                <span class="text-[11px] font-medium text-slate-400">12 Kategori Utama</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3.5">
                @php
                    $items = [
                        ['label' => 'Beras', 'val' => $stokLogistik['beras_kg'], 'unit' => 'kg'],
                        ['label' => 'Air Minum', 'val' => $stokLogistik['air_minum_dus'], 'unit' => 'dus'],
                        ['label' => 'Makanan Kaleng', 'val' => $stokLogistik['makanan_kaleng_pack'], 'unit' => 'pack'],
                        ['label' => 'Makanan Bayi', 'val' => $stokLogistik['makanan_bayi_pack'], 'unit' => 'pack'],
                        ['label' => 'Minyak Goreng', 'val' => $stokLogistik['minyak_goreng_liter'], 'unit' => 'liter'],
                        ['label' => 'Popok Bayi', 'val' => $stokLogistik['popok_bayi_pcs'], 'unit' => 'pcs'],
                        ['label' => 'Popok Dewasa', 'val' => $stokLogistik['popok_dewasa_pcs'], 'unit' => 'pcs'],
                        [
                            'label' => 'Pembalut Wanita',
                            'val' => $stokLogistik['pembalut_wanita_pack'],
                            'unit' => 'pack',
                        ],
                        ['label' => 'Hygiene Kit', 'val' => $stokLogistik['hygiene_kit_paket'], 'unit' => 'paket'],
                        ['label' => 'Selimut', 'val' => $stokLogistik['selimut_pcs'], 'unit' => 'pcs'],
                        ['label' => 'Matras / Terpal', 'val' => $stokLogistik['matras_terpal_pcs'], 'unit' => 'pcs'],
                        ['label' => 'Obat P3K', 'val' => $stokLogistik['obat_p3k_paket'], 'unit' => 'paket'],
                    ];
                @endphp

                @foreach ($items as $item)
                    <div
                        class="p-3.5 rounded-xl border transition-all duration-150 {{ $item['val'] > 0 ? 'bg-slate-50/80 border-slate-200/90 hover:border-blue-300 hover:shadow-xs' : 'bg-slate-50/40 border-slate-100 opacity-60' }}">
                        <span class="text-[11px] font-medium text-slate-500 block truncate"
                            title="{{ $item['label'] }}">{{ $item['label'] }}</span>
                        <div class="mt-1 flex items-baseline gap-1">
                            <span
                                class="text-xl font-black tracking-tight {{ $item['val'] > 0 ? 'text-slate-900' : 'text-slate-400' }}">
                                {{ number_format($item['val']) }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $item['unit'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- TABEL RIWAYAT SUPLAI DARI BPBD -->
        <div class="bg-white rounded-2xl shadow-xs border border-slate-200/70 overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-white">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Riwayat Permintaan & Suplai Masuk BPBD</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Log pencatatan distribusi dan verifikasi dari BPBD
                        Kabupaten/Kota</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-200">

                        <tr class="text-slate-700 text-xs font-bold uppercase tracking-wider border-b border-slate-300">

                            <th class="py-4 px-6">KODE PENGAJUAN</th>

                            <th class="py-4 px-6">BENCANA</th>

                            <th class="py-4 px-6">TANGGAL DIAJUKAN</th>

                            <th class="py-4 px-6">STATUS BPBD</th>

                            <th class="py-4 px-6">RINCIAN SUPLAI</th>

                        </tr>

                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($riwayatSuplai as $item)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="py-4 px-6 font-bold text-blue-600 whitespace-nowrap">
                                    <span
                                        class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md text-[11px] border border-blue-100/60">
                                        {{ $item->kode_pengajuan }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-semibold text-slate-800">
                                    {{ $item->bencana->nama_bencana ??
                                        ($item->bencana->jenis_bencana ??
                                            ($item->posko->bencana->nama_bencana ?? ($item->posko->bencana->jenis_bencana ?? '-'))) }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-slate-500 font-medium">
                                    {{ \Carbon\Carbon::parse($item->tanggal_pengajuan ?? $item->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    @if ($item->status == 'pending')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold bg-amber-50 text-amber-700 rounded-lg border border-amber-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Menunggu BPBD
                                        </span>
                                    @elseif(in_array($item->status, ['disetujui', 'dalam_pengiriman', 'selesai']))
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Masuk Stok Posko
                                        </span>
                                    @elseif($item->status == 'dieskalasi_provinsi')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold bg-purple-50 text-purple-700 rounded-lg border border-purple-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                            Dieskalasi Prov
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 text-[11px] font-semibold bg-rose-50 text-rose-700 rounded-lg border border-rose-200/60">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5 text-[11px]">
                                        @if ($item->beras_kg > 0)
                                            <span
                                                class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium border border-slate-200/60">Beras:
                                                {{ $item->beras_kg }} kg</span>
                                        @endif
                                        @if ($item->air_minum_dus > 0)
                                            <span
                                                class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium border border-slate-200/60">Air:
                                                {{ $item->air_minum_dus }} dus</span>
                                        @endif
                                        @if ($item->makanan_kaleng_pack > 0)
                                            <span
                                                class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium border border-slate-200/60">Mak
                                                Kaleng: {{ $item->makanan_kaleng_pack }} pk</span>
                                        @endif
                                        @if ($item->obat_p3k_paket > 0)
                                            <span
                                                class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md font-medium border border-slate-200/60">P3K:
                                                {{ $item->obat_p3k_paket }} pkt</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 italic">
                                    Belum ada riwayat suplai dari BPBD.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($riwayatSuplai->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $riwayatSuplai->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
