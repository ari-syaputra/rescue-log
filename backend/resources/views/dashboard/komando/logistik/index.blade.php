@extends('layouts.app')

@section('title', 'Data Logistik & Pengajuan')

@section('content')
<div class="w-full space-y-6 pb-12">

    <!-- JUDUL & PENJELASAN HALAMAN (FONT DISESUAIKAN) -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Logistik & Pengajuan</h1>
        <p class="text-xs text-slate-500 mt-0.5">Kelola, pantau, dan konfirmasi pengajuan kebutuhan logistik dari posko-posko lapangan.</p>
    </div>

    {{-- Pesan Eror Tangkapan Exception --}}
    @if (session('error'))
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center shrink-0 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-xs font-semibold">{{ session('error') }}</div>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-600 font-bold">&times;</button>
        </div>
    @endif

    {{-- Pesan Eror Validasi Form --}}
    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl shadow-xs">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center shrink-0 text-rose-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h4 class="text-xs font-bold">Gagal Memproses Data! Harap periksa input berikut:</h4>
            </div>
            <ul class="list-disc pl-11 text-xs space-y-1 text-rose-700 font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BANNER AI PREDIKSI STOK LOGISTIK (TYPOGRAPHY DISESUAIKAN) -->
    <div class="relative overflow-hidden bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-800 rounded-2xl p-6 text-white shadow-md flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>

        <div class="flex items-start gap-4 z-10">
            <!-- Icon Box AI -->
            <div class="w-11 h-11 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center shrink-0 text-amber-300 shadow-inner">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-bold tracking-tight text-white">AI Prediksi Stok Logistik</h2>
                    <span class="text-[10px] bg-amber-400 text-slate-900 px-2 py-0.5 rounded-md uppercase font-bold tracking-wider">AKTIF</span>
                </div>
                <p class="text-blue-100 text-xs leading-relaxed max-w-3xl mt-1 font-normal">
                    Sistem Machine Learning memantau tren pengajuan dari seluruh posko lapangan secara real-time untuk memastikan akurasi distribusi logistik bantuan bencana.
                </p>
            </div>
        </div>
    </div>

    <!-- 4 KOTAK STATISTIK RINGKASAN DATA (TYPOGRAPHY & UKURAN HARMONIS) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card Total -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">TOTAL PENGAJUAN</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $pengajuans->total() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>

        <!-- Card Disetujui -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">DISETUJUI</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $pengajuans->whereIn('status', ['disetujui', 'disetujui_sebagian', 'dalam_pengiriman', 'selesai'])->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card Menunggu ACC -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">MENUNGGU ACC</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $pengajuans->where('status', 'pending')->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <!-- Card Ditolak -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">DITOLAK</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $pengajuans->where('status', 'ditolak')->count() }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    <!-- FILTER & PENCARIAN -->
    <form method="GET" action="{{ route('komando.logistik.index') }}" class="bg-white p-4 rounded-2xl shadow-xs border border-slate-200/80 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-1">
            <!-- Search Field -->
            <div class="relative min-w-[280px] flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor pengajuan atau posko..." class="w-full pl-10 pr-4 py-2 text-xs font-normal bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-blue-600 focus:bg-white transition">
            </div>

            <!-- Dropdown Status -->
            <select name="status" class="px-3.5 py-2 text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl text-slate-700 focus:outline-none cursor-pointer hover:bg-slate-100 transition">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui Full</option>
                <option value="disetujui_sebagian" {{ request('status') == 'disetujui_sebagian' ? 'selected' : '' }}>Disetujui Sebagian</option>
                <option value="dalam_pengiriman" {{ request('status') == 'dalam_pengiriman' ? 'selected' : '' }}>Dalam Pengiriman</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <!-- Submit Button Filter -->
        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl transition shadow-xs cursor-pointer">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter Data
        </button>
    </form>

    <!-- TABEL DATA PENGAJUAN -->
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200/80">
                        <th class="py-3.5 px-6">NO. PENGAJUAN</th>
                        <th class="py-3.5 px-6">POSKO LAPANGAN</th>
                        <th class="py-3.5 px-6">WAKTU PENGAJUAN</th>
                        <th class="py-3.5 px-6">STATUS</th>
                        <th class="py-3.5 px-6">RINGKASAN KEBUTUHAN</th>
                        <th class="py-3.5 px-6 text-right">AKSI KONFIRMASI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                    @forelse($pengajuans as $item)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-4 px-6 font-bold text-slate-900">
                                {{ $item->kode_pengajuan }}
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-900">{{ $item->user->name ?? 'Posko Lapangan' }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $item->user->email ?? '-' }}</div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-800">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="text-[11px] text-slate-400 font-normal">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>

                            <td class="py-4 px-6">
                                @if($item->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-200/60">
                                        Menunggu ACC
                                    </span>
                                @elseif($item->status == 'disetujui')
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-emerald-50 text-emerald-700 rounded-full border border-emerald-200/60">
                                        Disetujui Full
                                    </span>
                                @elseif($item->status == 'disetujui_sebagian')
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-blue-50 text-blue-700 rounded-full border border-blue-200/60">
                                        Sebagian
                                    </span>
                                @elseif($item->status == 'dalam_pengiriman' || $item->status == 'pengiriman')
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                                        Pengiriman
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-teal-50 text-teal-700 rounded-full border border-teal-200/60">
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 text-[11px] font-bold bg-rose-50 text-rose-700 rounded-full border border-rose-200/60">
                                        Ditolak
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex flex-wrap items-center gap-1.5 text-xs">
                                    @if($item->beras_kg > 0)
                                        <span class="bg-slate-100/80 text-slate-700 px-2.5 py-1 rounded-md text-[11px] font-medium">Beras: {{ $item->beras_kg }} kg</span>
                                    @endif
                                    @if($item->air_minum_dus > 0)
                                        <span class="bg-slate-100/80 text-slate-700 px-2.5 py-1 rounded-md text-[11px] font-medium">Air: {{ $item->air_minum_dus }} Dus</span>
                                    @endif
                                    @if($item->makanan_kaleng_pack > 0)
                                        <span class="bg-slate-100/80 text-slate-700 px-2.5 py-1 rounded-md text-[11px] font-medium">Mkn Kaleng: {{ $item->makanan_kaleng_pack }} Pk</span>
                                    @endif
                                    
                                    <button onclick="openDetailModal(
                                        '{{ $item->kode_pengajuan }}', 
                                        '{{ $item->user->name ?? 'Posko Lapangan' }}', 
                                        '{{ $item->created_at->format('d M Y, H:i') }} WIB', 
                                        '{{ $item->beras_kg ?? 0 }}', 
                                        '{{ $item->makanan_kaleng_pack ?? 0 }}',
                                        '{{ $item->makanan_bayi_pack ?? 0 }}',
                                        '{{ $item->minyak_goreng_liter ?? 0 }}',
                                        '{{ $item->air_minum_dus ?? 0 }}',
                                        '{{ $item->popok_bayi_pcs ?? 0 }}',
                                        '{{ $item->popok_dewasa_pcs ?? 0 }}',
                                        '{{ $item->pembalut_wanita_pack ?? 0 }}',
                                        '{{ $item->hygiene_kit_paket ?? 0 }}',
                                        '{{ $item->selimut_pcs ?? 0 }}',
                                        '{{ $item->matras_terpal_pcs ?? 0 }}',
                                        '{{ $item->obat_p3k_paket ?? 0 }}',
                                        '{{ $item->catatan_posko ?? '-' }}'
                                    )" class="text-blue-600 hover:text-blue-800 font-bold text-xs block mt-1 cursor-pointer">
                                        Detail
                                    </button>
                                </div>
                            </td>

                            <!-- AKSI KONFIRMASI -->
                            <td class="py-4 px-6 text-right">
                                @if($item->status == 'pending')
                                    <button type="button" 
                                        onclick="openActionModal(
                                            '{{ $item->id }}', 
                                            '{{ $item->kode_pengajuan }}', 
                                            '{{ $item->user->name ?? 'Posko Lapangan' }}',
                                            '{{ route('komando.logistik.approve', $item->id) }}',
                                            '{{ route('komando.logistik.reject', $item->id) }}',
                                            {{ json_encode($item) }}
                                        )" 
                                        class="px-3.5 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-xl text-xs font-semibold inline-flex items-center gap-1.5 transition shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Proses Aksi</span>
                                    </button>

                                @elseif(($item->status == 'disetujui' || $item->status == 'disetujui_sebagian') && !$item->pengiriman)
                                    <button type="button"
                                        onclick="openScheduleModal(
                                            '{{ $item->id }}',
                                            '{{ $item->kode_pengajuan }}',
                                            '{{ $item->user->name ?? 'Posko Lapangan' }}'
                                        )"
                                        class="px-3.5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-xs font-bold inline-flex items-center gap-1.5 transition shadow-xs cursor-pointer animate-pulse">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        <span>Kirim Sekarang</span>
                                    </button>

                                @elseif($item->status == 'dalam_pengiriman' || $item->status == 'pengiriman' || $item->pengiriman)
                                    <div class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-emerald-700 bg-emerald-50 rounded-xl border border-emerald-200/80 leading-tight">
                                        Dalam<br>Pengiriman
                                    </div>

                                @elseif($item->status == 'selesai')
                                    <span class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold text-teal-700 bg-teal-50 rounded-xl border border-teal-200">
                                        Selesai
                                    </span>
                                @else
                                    <span class="text-xs text-slate-400 font-medium italic">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                    <p class="text-xs font-medium">Belum ada data pengajuan logistik dari posko lapangan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200/80 bg-slate-50">
            {{ $pengajuans->links() }}
        </div>
    </div>

</div>

<!-- MEMANGGIL KOMPONEN MODAL -->
<x-komando.logistik.action-modal />
<x-komando.logistik.partial-modal />
<x-komando.logistik.detail-modal />
<x-komando.logistik.schedule-modal :armadas="$armadas ?? []" />

<!-- SCRIPT PENGENDALI MODAL -->
<script>
    let activeItemData = null;

    function openActionModal(id, kode, poskoNama, approveUrl, rejectUrl, itemData) {
        activeItemData = itemData;

        const modal = document.getElementById('actionModal');
        document.getElementById('actionModalSubtitle').innerText = `No: ${kode} (${poskoNama})`;

        document.getElementById('formApproveFull').action = approveUrl;
        document.getElementById('formReject').action = rejectUrl;

        modal.classList.remove('hidden');
    }

    function closeActionModal() {
        document.getElementById('actionModal').classList.add('hidden');
    }

    function switchToPartialModal() {
        closeActionModal();
        if (activeItemData) {
            openPartialModal(activeItemData);
        }
    }

    function openPartialModal(item) {
        const modal = document.getElementById('partialModal');
        const form = document.getElementById('partialForm');
        const subTitle = document.getElementById('modalSubTitle');
        
        form.action = `/komando/logistik/${item.id}/approve-partial`;
        subTitle.innerText = `Nomor Pengajuan: ${item.kode_pengajuan}`;
        
        if(document.getElementById('part_beras_kg')) document.getElementById('part_beras_kg').value = item.beras_kg || 0;
        if(document.getElementById('part_makanan_kaleng_pack')) document.getElementById('part_makanan_kaleng_pack').value = item.makanan_kaleng_pack || 0;
        if(document.getElementById('part_makanan_bayi_pack')) document.getElementById('part_makanan_bayi_pack').value = item.makanan_bayi_pack || 0;
        if(document.getElementById('part_minyak_goreng_liter')) document.getElementById('part_minyak_goreng_liter').value = item.minyak_goreng_liter || 0;
        if(document.getElementById('part_air_minum_dus')) document.getElementById('part_air_minum_dus').value = item.air_minum_dus || 0;
        if(document.getElementById('part_popok_bayi_pcs')) document.getElementById('part_popok_bayi_pcs').value = item.popok_bayi_pcs || 0;
        if(document.getElementById('part_popok_dewasa_pcs')) document.getElementById('part_popok_dewasa_pcs').value = item.popok_dewasa_pcs || 0;
        if(document.getElementById('part_pembalut_wanita_pack')) document.getElementById('part_pembalut_wanita_pack').value = item.pembalut_wanita_pack || 0;
        if(document.getElementById('part_hygiene_kit_paket')) document.getElementById('part_hygiene_kit_paket').value = item.hygiene_kit_paket || 0;
        if(document.getElementById('part_selimut_pcs')) document.getElementById('part_selimut_pcs').value = item.selimut_pcs || 0;
        if(document.getElementById('part_matras_terpal_pcs')) document.getElementById('part_matras_terpal_pcs').value = item.matras_terpal_pcs || 0;
        if(document.getElementById('part_obat_p3k_paket')) document.getElementById('part_obat_p3k_paket').value = item.obat_p3k_paket || 0;

        modal.classList.remove('hidden');
    }

    function closePartialModal() {
        document.getElementById('partialModal').classList.add('hidden');
    }

    function openScheduleModal(pengajuanId, kodePengajuan, poskoNama) {
        document.getElementById('schedule_pengajuan_id').value = pengajuanId;
        document.getElementById('scheduleModalSubtitle').innerText = `Pengajuan: ${kodePengajuan} (${poskoNama})`;
        
        document.getElementById('scheduleModal').classList.remove('hidden');
    }

    function closeScheduleModal() {
        document.getElementById('scheduleModal').classList.add('hidden');
    }

    function openDetailModal(kode, posko, waktu, beras, mknKaleng, mknBayi, minyak, air, popokBayi, popokDewasa, pembalut, hygiene, selimut, matras, obat, catatan) {
        const modal = document.getElementById('detailModal');
        
        document.getElementById('detailKodePengajuan').innerText = `No. Pengajuan: ${kode}`;
        document.getElementById('detailPoskoNama').innerText = posko;
        document.getElementById('detailWaktu').innerText = waktu;

        const tbody = document.getElementById('detailTabelBarang');
        tbody.innerHTML = `
            <tr><td class="p-2.5">Beras</td><td class="p-2.5 text-right font-bold text-slate-900">${beras} Kg</td></tr>
            <tr><td class="p-2.5">Makanan Kaleng</td><td class="p-2.5 text-right font-bold text-slate-900">${mknKaleng} Pack</td></tr>
            <tr><td class="p-2.5">Makanan Bayi</td><td class="p-2.5 text-right font-bold text-slate-900">${mknBayi} Pack</td></tr>
            <tr><td class="p-2.5">Minyak Goreng</td><td class="p-2.5 text-right font-bold text-slate-900">${minyak} Liter</td></tr>
            <tr><td class="p-2.5">Air Minum</td><td class="p-2.5 text-right font-bold text-slate-900">${air} Dus</td></tr>
            <tr><td class="p-2.5">Popok Bayi</td><td class="p-2.5 text-right font-bold text-slate-900">${popokBayi} Pcs</td></tr>
            <tr><td class="p-2.5">Popok Dewasa</td><td class="p-2.5 text-right font-bold text-slate-900">${popokDewasa} Pcs</td></tr>
            <tr><td class="p-2.5">Pembalut Wanita</td><td class="p-2.5 text-right font-bold text-slate-900">${pembalut} Pack</td></tr>
            <tr><td class="p-2.5">Hygiene Kit</td><td class="p-2.5 text-right font-bold text-slate-900">${hygiene} Paket</td></tr>
            <tr><td class="p-2.5">Selimut</td><td class="p-2.5 text-right font-bold text-slate-900">${selimut} Pcs</td></tr>
            <tr><td class="p-2.5">Matras / Terpal</td><td class="p-2.5 text-right font-bold text-slate-900">${matras} Pcs</td></tr>
            <tr><td class="p-2.5">Obat P3K</td><td class="p-2.5 text-right font-bold text-slate-900">${obat} Paket</td></tr>
        `;

        if(catatan && catatan !== '-') {
            tbody.innerHTML += `
                <tr class="bg-slate-50"><td colspan="2" class="p-2.5 text-xs text-slate-600">
                    <span class="font-bold text-slate-800">Catatan Posko:</span> ${catatan}
                </td></tr>
            `;
        }

        modal.classList.remove('hidden');
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }
</script>
@endsection