@extends('layouts.app')

@section('title', 'Kelola Eskalasi Logistik - BPBD Provinsi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Manajemen Eskalasi Logistik Regional</h1>
            <p class="text-xs text-slate-500 mt-1">Verifikasi & Penyaluran Suplai Cadangan Bencana Provinsi ke BPBD Kab/Kota</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px]">
                    <tr>
                        <th class="p-4">Kode & Tanggal</th>
                        <th class="p-4">Pengaju / BPBD</th>
                        <th class="p-4">Status Pengajuan</th>
                        <th class="p-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($eskalasiList as $item)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="p-4">
                                <span class="font-bold text-blue-600 block">{{ $item->kode_pengajuan }}</span>
                                <span class="text-[10px] text-slate-400">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y H:i') }}</span>
                            </td>
                            <td class="p-4">
                                <span class="font-bold text-slate-800 block">{{ $item->user->name ?? 'BPBD Kabupaten' }}</span>
                                <span class="text-[11px] text-slate-500">{{ $item->posko->nama_posko ?? '-' }}</span>
                            </td>
                            <td class="p-4">
                                @if ($item->status === 'disetujui')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-[10px] font-bold rounded-md">
                                        Disetujui Provinsi
                                    </span>
                                @elseif($item->status === 'dieskalasi_provinsi')
                                    <span class="px-2.5 py-1 bg-indigo-100 text-indigo-800 text-[10px] font-bold rounded-md">
                                        Diteruskan ke BNPB
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-[10px] font-bold rounded-md">
                                        Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <form action="{{ route('provinsi.eskalasi.approve', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-[11px] transition cursor-pointer">
                                            ACC Stok Provinsi
                                        </button>
                                    </form>
                                    <form action="{{ route('provinsi.eskalasi.bnpb', $item->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg text-[11px] transition cursor-pointer">
                                            Eskalasi BNPB
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-6 text-center text-slate-400 italic">
                                Belum ada riwayat pengajuan eskalasi logistik.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection