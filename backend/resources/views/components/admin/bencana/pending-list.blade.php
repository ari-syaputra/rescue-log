<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-base font-bold text-slate-900">Deteksi Otomatis</h2>
        <p class="text-xs text-slate-500">Data API BMKG perlu tinjauan</p>
    </div>
    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
        {{ count($pendingDisasters ?? []) }} Pending
    </span>
</div>

<div class="space-y-3 overflow-y-auto max-h-105 pr-2 custom-scrollbar">
    @forelse($pendingDisasters ?? [] as $pending)
        <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40 hover:bg-amber-50 transition-colors">
            <div class="flex justify-between items-start">
                <span class="px-2 py-0.5 text-[10px] font-bold bg-amber-200 text-amber-900 rounded uppercase tracking-wider">
                    {{ $pending->jenis_bencana ?? 'BENCANA' }}
                </span>
            </div>

            <h4 class="font-bold text-slate-900 text-sm mt-2">{{ $pending->wilayah ?? $pending->lokasi }}</h4>

            <div class="text-xs text-slate-600 mt-2 space-y-1">
                <p><strong>Waktu:</strong> {{ $pending->waktu_kejadian ?? '-' }}</p>
                <p><strong>Sumber:</strong> {{ $pending->sumber ?? 'BMKG' }}</p>
            </div>

            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-amber-200/60">
                <button type="button" 
                    data-pending="{{ json_encode($pending) }}"
                    onclick="openModalValidasi(this)"
                    class="flex-1 py-2 px-3 bg-amber-600 hover:bg-amber-700 text-white text-xs font-semibold rounded-lg text-center transition-colors shadow-sm cursor-pointer">
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
            <p class="text-sm">Tidak ada deteksi bencana baru dari API.</p>
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