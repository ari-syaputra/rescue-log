@extends('layouts.app-lapangan')

@section('title', 'Panggilan Ambulans Darurat - Sub Posko')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto font-sans">

    <!-- Flash Message Success -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Flash Message Error -->
    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center justify-between shadow-2xs">
            <span>{{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 font-bold">&times;</button>
        </div>
    @endif

    <!-- Header & Action SOS (Sesuai Layout Gambar 2) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
        <div class="flex items-start sm:items-center gap-3">
            <!-- Tombol Kembali ke Dashboard Utama Lapangan -->
            <a href="{{ route('lapangan.dashboard') }}" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center shrink-0 transition-colors shadow-2xs" title="Kembali ke Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Panggilan Ambulans Darurat</h1>
                <p class="text-xs text-slate-500 mt-0.5">Ajukan evakuasi medis darurat pengungsi ke Posko Komando Utama.</p>
            </div>
        </div>

        <button onclick="openModalSos()" class="px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white font-extrabold text-xs rounded-xl shadow-md transition-all flex items-center justify-center gap-2 cursor-pointer shrink-0">
            <span class="text-base">🆘</span> PANGGIL AMBULANS SEKARANG
        </button>
    </div>

    <!-- Active Request Banner (Jika ada permintaan medis yang sedang berjalan) -->
    @if($activeRequest)
        <div class="bg-gradient-to-r from-rose-600 to-amber-600 text-white p-5 rounded-2xl shadow-md flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 bg-white/20 text-white text-[10px] font-bold rounded uppercase tracking-wider">STATUS AKTIF: {{ str_replace('_', ' ', strtoupper($activeRequest->status)) }}</span>
                    <span class="text-xs font-mono font-bold">{{ $activeRequest->kode_sos }}</span>
                </div>
                <h3 class="text-lg font-extrabold">Pasien: {{ $activeRequest->nama_pasien }}</h3>
                <p class="text-xs text-rose-100">Kondisi Medis: {{ $activeRequest->kondisi_medis }}</p>
                @if($activeRequest->armada)
                    <p class="text-xs font-semibold text-amber-200">🚑 Unit Armada: {{ $activeRequest->armada->nama_armada }} (Driver: {{ $activeRequest->armada->pengemudi ?? '-' }})</p>
                @else
                    <p class="text-xs italic text-rose-200">Menunggu Posko Komando memplot unit ambulans...</p>
                @endif
            </div>

            <div>
                <form action="{{ route('lapangan.ambulans.konfirmasi', $activeRequest->id) }}" method="POST">
                    @csrf
                    <button type="submit" onclick="return confirm('Konfirmasi pasien telah tertangani / sampai di RS Rujukan?')" class="w-full sm:w-auto px-4 py-2.5 bg-white text-rose-700 hover:bg-rose-50 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer text-center">
                        ✅ Konfirmasi Selesai Evakuasi
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Tabel Riwayat Request Ambulans -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
        <h3 class="text-sm font-bold text-slate-800">Riwayat Panggilan Medis Darurat</h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-slate-500 text-xs font-bold uppercase bg-slate-50/80">
                        <th class="p-3">Kode SOS</th>
                        <th class="p-3">Nama Pasien</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3">Kondisi Medis</th>
                        <th class="p-3">Ambulans / RS Rujukan</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Waktu Request</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs font-medium text-slate-700">
                    @forelse($requests as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="p-3 font-mono font-bold text-slate-900">{{ $item->kode_sos }}</td>
                            <td class="p-3 font-semibold text-slate-800">{{ $item->nama_pasien }}</td>
                            <td class="p-3">
                                @if($item->kategori_darurat == 'kritis_nyawa')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">🔴 Kritis Nyawa</span>
                                @elseif($item->kategori_darurat == 'berat')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">🟡 Berat</span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">🟢 Sedang</span>
                                @endif
                            </td>
                            <td class="p-3 max-w-xs truncate" title="{{ $item->kondisi_medis }}">{{ $item->kondisi_medis }}</td>
                            <td class="p-3">
                                <p class="font-semibold text-slate-800">{{ $item->armada->nama_armada ?? 'Belum Ditentukan' }}</p>
                                <p class="text-[10px] text-slate-500">{{ $item->rs_rujukan ?? '-' }}</p>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                                    {{ $item->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : ($item->status == 'menunggu_penanganan' ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ str_replace('_', ' ', $item->status) }}
                                </span>
                            </td>
                            <td class="p-3 text-slate-500">{{ $item->waktu_request->diffForHumans() }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-400">
                                Belum ada panggilan ambulans darurat yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Form Request SOS -->
<div id="modalSos" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-100">
        <div class="bg-rose-600 text-white px-5 py-4 flex justify-between items-center">
            <h3 class="font-bold text-sm flex items-center gap-1.5">
                <span>🆘</span> Form Panggilan Ambulans Darurat
            </h3>
            <button onclick="closeModalSos()" class="text-rose-200 hover:text-white font-bold text-xl cursor-pointer">&times;</button>
        </div>

        <form action="{{ route('lapangan.ambulans.store') }}" method="POST" class="p-5 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Nama Pasien / Korban</label>
                <input type="text" name="nama_pasien" required placeholder="Contoh: Bpk. Slamet / Korban Longsor Anonim" class="w-full text-xs border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tingkat Kegawatdaruratan Medis</label>
                <select name="kategori_darurat" required class="w-full text-xs border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600">
                    <option value="kritis_nyawa">🔴 Kritis Nyawa (Ancaman Jiwa / Pendarahan Hebat)</option>
                    <option value="berat" selected>🟡 Berat (Patah Tulang / Tidak Bisa Berjalan)</option>
                    <option value="sedang">🟢 Sedang (Luka-luka ringan / Demam Tinggi)</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Kondisi Medis & Gejala</label>
                <textarea name="kondisi_medis" rows="3" required placeholder="Jelaskan detail luka atau penderitaan medis korban..." class="w-full text-xs border border-slate-300 rounded-xl p-2.5 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-600"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeModalSos()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl cursor-pointer">Batal</button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-sm cursor-pointer">Kirim Sinyal SOS</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openModalSos() {
        document.getElementById('modalSos').classList.remove('hidden');
    }
    function closeModalSos() {
        document.getElementById('modalSos').classList.add('hidden');
    }
</script>
@endpush