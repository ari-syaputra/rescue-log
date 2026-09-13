@extends('layouts.app')

@section('title', 'Validasi Logistik Sub-Posko')

@section('content')
<div class="w-full space-y-6 pb-12">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Validasi Logistik Sub-Posko</h1>
        <p class="text-xs text-slate-500 mt-0.5">Konfirmasi dan setujui pengajuan kebutuhan logistik dari Sub-Posko Lapangan.</p>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center justify-between shadow-xs">
            <span class="text-xs font-semibold">{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 font-bold">&times;</button>
        </div>
    @endif

    @if (session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between shadow-xs">
            <span class="text-xs font-semibold">{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 font-bold">&times;</button>
        </div>
    @endif

    <!-- TABEL PENGAJUAN SUB-POSKO -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6">NO. PENGAJUAN</th>
                        <th class="py-3.5 px-6">SUB POSKO</th>
                        <th class="py-3.5 px-6">BENCANA</th>
                        <th class="py-3.5 px-6">STATUS</th>
                        <th class="py-3.5 px-6">RINGKASAN ITEM</th>
                        <th class="py-3.5 px-6 text-right">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($pengajuans as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $item->kode_pengajuan }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">{{ $item->posko->nama_posko ?? $item->user->name ?? '-' }}</td>
                            <td class="py-4 px-6 font-medium text-slate-800">
                                {{ $item->bencana->nama_bencana ?? $item->posko->bencana->nama_bencana ?? '-' }}
                            </td>
                            <td class="py-4 px-6">
                                @if($item->status == 'pending')
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-200">Menunggu Validasi</span>
                                @elseif($item->status == 'disetujui')
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200">Disetujui</span>
                                @elseif($item->status == 'selesai')
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-teal-50 text-teal-700 rounded-full border border-teal-200">Diterima Sub-Posko</span>
                                @else
                                    <span class="px-2.5 py-1 text-[11px] font-bold bg-rose-50 text-rose-700 rounded-full border border-rose-200">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1 text-[11px]">
                                    @if($item->beras_kg > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">Beras: {{ $item->beras_kg }} kg</span> @endif
                                    @if($item->air_minum_dus > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">Air: {{ $item->air_minum_dus }} dus</span> @endif
                                    @if($item->obat_p3k_paket > 0) <span class="bg-slate-100 px-2 py-0.5 rounded border">P3K: {{ $item->obat_p3k_paket }} pkt</span> @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right">
                                @if($item->status == 'pending')
                                    <form action="{{ route('komando.validasi.approve', $item->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini dan mengalokasikan stok Posko Komando?')" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition cursor-pointer">
                                            Setujui & Atur Armada
                                        </button>
                                    </form>
                                @elseif($item->status == 'disetujui')
                                    <a href="{{ route('komando.distribusi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl shadow-xs transition animate-pulse">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        Atur Armada & Rute
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 italic">Belum ada pengajuan masuk dari Sub-Posko.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection