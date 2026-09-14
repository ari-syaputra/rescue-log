<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-base font-bold text-slate-900">Tinjauan Insiden Bencana</h2>
        <p class="text-xs text-slate-500">Data deteksi otomatis BMKG & laporan manual TRC</p>
    </div>
    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
        {{ count($pendingDisasters ?? []) }} Pending
    </span>
</div>

<div class="space-y-3 overflow-y-auto max-h-105 pr-2 custom-scrollbar">
    @forelse($pendingDisasters ?? [] as $pending)
        @php
            $isManual = str_starts_with($pending->external_id ?? '', 'MANUAL-');
        @endphp
        <div class="p-4 rounded-xl border transition-colors {{ $isManual ? 'border-indigo-200 bg-indigo-50/40 hover:bg-indigo-50' : 'border-amber-200 bg-amber-50/40 hover:bg-amber-50' }}">
            <div class="flex justify-between items-start gap-2">
                <!-- Badge Sumber Insiden -->
                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase tracking-wider {{ $isManual ? 'bg-indigo-100 text-indigo-900 border border-indigo-200' : 'bg-amber-200 text-amber-900 border border-amber-300' }}">
                    {{ $isManual ? '📝 Laporan TRC / Manual' : '🛰️ BMKG Auto-Detect' }}
                </span>
                
                <span class="text-[10px] text-slate-400 font-medium">
                    {{ \Carbon\Carbon::parse($pending->waktu_kejadian ?? $pending->created_at)->diffForHumans() }}
                </span>
            </div>

            <h4 class="font-bold text-slate-900 text-sm mt-2">{{ $pending->jenis_bencana ?? 'BENCANA' }}</h4>

            <div class="text-xs text-slate-600 mt-2 space-y-1">
                <p><strong>Lokasi:</strong> {{ $pending->wilayah ?? $pending->lokasi }}</p>
                <p><strong>Waktu:</strong> {{ $pending->waktu_kejadian ? \Carbon\Carbon::parse($pending->waktu_kejadian)->translatedFormat('d M Y, H:i') : '-' }}</p>
                @if($pending->deskripsi)
                    <p class="text-[11px] text-slate-500 italic mt-1 line-clamp-2">{{ $pending->deskripsi }}</p>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-3 pt-3 border-t {{ $isManual ? 'border-indigo-200/60' : 'border-amber-200/60' }}">
                <button type="button" 
                    data-pending="{{ json_encode($pending) }}"
                    onclick="openModalValidasi(this)"
                    class="flex-1 py-2 px-3 {{ $isManual ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-amber-600 hover:bg-amber-700' }} text-white text-xs font-semibold rounded-lg text-center transition-colors shadow-sm cursor-pointer">
                    Tinjau & Aktifkan
                </button>

                <form id="form-abaikan-{{ $pending->id }}" action="{{ route('admin.bencana.reject', $pending->id) }}" method="POST">
                    @csrf
                    <button type="button" onclick="konfirmasiAbaikan({{ $pending->id }})"
                        class="py-2 px-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs font-semibold rounded-lg transition-colors cursor-pointer">
                        Abaikan
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-slate-400">
            <svg class="w-8 h-8 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm font-semibold">Tidak ada deteksi insiden baru</p>
            <p class="text-xs text-slate-400 mt-0.5">Seluruh laporan insiden telah ditinjau</p>
        </div>
    @endforelse
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>