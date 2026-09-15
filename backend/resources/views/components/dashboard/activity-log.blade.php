<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-5 space-y-4">
    <div class="flex items-center justify-between">
        <h3 class="text-xs font-black text-slate-800 uppercase tracking-wide">Aktivitas & Log Terbaru</h3>
        <a href="{{ Route::has('komando.validasi.index') ? route('komando.validasi.index') : '#' }}" class="text-xs font-bold text-blue-600 hover:underline">
            Lihat Semua
        </a>
    </div>

    <div class="space-y-2.5">
        @forelse($logs ?? [] as $log)
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200/60 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl {{ $log->bg_icon }} flex items-center justify-center shrink-0">
                        @if($log->tipe === 'distribusi')
                            <x-heroicon-s-arrow-path-rounded-square class="w-5 h-5" />
                        @else
                            <x-heroicon-s-document-text class="w-5 h-5" />
                        @endif
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">{{ $log->judul }}</p>
                        <p class="text-[11px] text-slate-500">{{ $log->deskripsi }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 self-end sm:self-center">
                    <span class="text-[11px] text-slate-400">
                        {{ \Carbon\Carbon::parse($log->waktu)->diffForHumans() }}
                    </span>
                    <span class="px-2.5 py-1 {{ $log->bg_status }} font-bold text-[10px] rounded-lg">
                        {{ $log->status_text }}
                    </span>
                </div>
            </div>
        @empty
            <div class="p-4 text-center bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <p class="text-xs text-slate-500">Belum ada aktivitas atau log terbaru.</p>
            </div>
        @endforelse
    </div>
</div>