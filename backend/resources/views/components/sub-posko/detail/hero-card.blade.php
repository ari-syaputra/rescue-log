@props(['subPosko'])

<div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
    <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
        {{-- Foto Posko --}}
        <div class="w-full lg:w-44 h-28 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 relative border border-slate-200/60">
            @if($subPosko->foto)
                <img src="{{ asset('storage/' . $subPosko->foto) }}" alt="{{ $subPosko->nama_posko }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                    <svg class="w-7 h-7 stroke-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5m0 0h4m-4 0V11m0 0h4"/>
                    </svg>
                    <span class="text-[10px] mt-1 font-medium">Tanpa Foto</span>
                </div>
            @endif
        </div>

        {{-- Info Judul & Status --}}
        <div class="flex-1 space-y-3 w-full">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold 
                            {{ $subPosko->status == 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $subPosko->status == 'aktif' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                            {{ ucfirst($subPosko->status ?? 'Aktif') }}
                        </span>
                        <span class="text-xs text-slate-400 font-medium">• {{ $subPosko->bencana->jenis_bencana ?? 'Bencana Umum' }}</span>
                    </div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $subPosko->nama_posko }}</h2>
                </div>

                {{-- Kode Akses / Fast Action --}}
                <div class="flex items-center gap-2 bg-slate-50 border border-slate-200/80 px-3 py-1.5 rounded-xl">
                    <span class="text-[11px] text-slate-400 font-medium">Kode Akses:</span>
                    <strong class="font-mono text-xs font-bold text-indigo-600">{{ $subPosko->kode_undangan }}</strong>
                    <button onclick="navigator.clipboard.writeText('{{ $subPosko->kode_undangan }}'); alert('Kode Akses Berhasil Disalin!');" title="Salin Kode" class="text-slate-400 hover:text-indigo-600 transition ml-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    </button>
                </div>
            </div>

            <p class="text-xs text-slate-500 leading-relaxed">{{ $subPosko->lokasi ?? 'Lokasi belum ditentukan' }}</p>

            {{-- Ringkasan Angka Kunci --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-3 border-t border-slate-100 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Total Petugas</span>
                        <strong class="text-slate-800 font-bold text-xs">{{ $subPosko->jumlah_petugas ?? 0 }} Orang</strong>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Kapasitas Maks</span>
                        <strong class="text-slate-800 font-bold text-xs">{{ $subPosko->kapasitas_maksimal ?? 0 }} Pengungsi</strong>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <span class="text-slate-400 font-medium block text-[11px]">Tgl Dibuat</span>
                        <strong class="text-slate-800 font-semibold text-xs">{{ $subPosko->created_at ? $subPosko->created_at->translatedFormat('d M Y') : '-' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>