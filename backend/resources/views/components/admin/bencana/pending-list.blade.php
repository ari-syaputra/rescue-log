<div class="flex items-center justify-between mb-4">
    <div>
        <h2 class="text-base font-bold text-slate-900">Tinjauan Insiden Bencana</h2>
        <p class="text-xs text-slate-500">Data deteksi otomatis BMKG & laporan manual TRC</p>
    </div>
    <span class="px-2.5 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">
        {{ count($pendingDisasters ?? []) }} Pending
    </span>
</div>

<div class="space-y-3 overflow-y-auto max-h-[26rem] pr-2 custom-scrollbar">
    @forelse($pendingDisasters ?? [] as $pending)
        @php
            $isManual = str_starts_with($pending->external_id ?? '', 'MANUAL-');
        @endphp
        <div class="p-4 rounded-xl border transition-colors {{ $isManual ? 'border-indigo-200 bg-indigo-50/40 hover:bg-indigo-50' : 'border-amber-200 bg-amber-50/40 hover:bg-amber-50' }}">
            
            <div class="flex justify-between items-start gap-2">
                <!-- Badge Sumber Insiden -->
                <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase tracking-wider flex items-center gap-1.5 {{ $isManual ? 'bg-indigo-100 text-indigo-900 border border-indigo-200' : 'bg-amber-200 text-amber-900 border border-amber-300' }}">
                    @if($isManual)
                        <x-heroicon-s-document-text class="w-3 h-3 text-indigo-700" />
                        <span>Laporan TRC / Manual</span>
                    @else
                        <x-heroicon-s-radio class="w-3 h-3 text-amber-700 animate-pulse" />
                        <span>BMKG Auto-Detect</span>
                    @endif
                </span>
                
                <span class="text-[10px] text-slate-400 font-medium whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($pending->waktu_kejadian ?? $pending->created_at)->diffForHumans() }}
                </span>
            </div>

            <div class="flex items-center justify-between mt-3">
                <h4 class="font-bold text-slate-900 text-sm">{{ $pending->jenis_bencana ?? 'BENCANA' }}</h4>
                
                {{-- Menampilkan Badge Magnitudo jika ada data Gempa --}}
                @if(isset($pending->magnitude) || str_contains(strtolower($pending->jenis_bencana ?? ''), 'gempa'))
                    <span class="px-2 py-0.5 bg-rose-100 text-rose-700 font-extrabold text-xs rounded-md border border-rose-200">
                        {{ $pending->magnitude ?? '5.0' }} SR / M
                    </span>
                @endif
            </div>

            <div class="text-xs text-slate-600 mt-2.5 space-y-1.5">
                <p><strong class="text-slate-700">Lokasi:</strong> {{ $pending->wilayah ?? $pending->lokasi }}</p>
                <p><strong class="text-slate-700">Waktu:</strong> {{ $pending->waktu_kejadian ? \Carbon\Carbon::parse($pending->waktu_kejadian)->translatedFormat('d M Y, H:i') : '-' }}</p>
                @if($pending->deskripsi)
                    <p class="text-[11px] text-slate-500 italic mt-1.5 line-clamp-2">{{ $pending->deskripsi }}</p>
                @endif
            </div>

            <div class="flex items-center gap-2 mt-4 pt-3 border-t {{ $isManual ? 'border-indigo-200/60' : 'border-amber-200/60' }}">
                
                <!-- TOMBOL TINJAU DAN AKTIFKAN DIUBAH KE ORANGE 600 -->
                <button type="button" 
                    data-pending="{{ json_encode($pending) }}"
                    onclick="openModalValidasi(this)"
                    class="flex-1 py-2 px-3 bg-orange-500 hover:bg-orange-600 text-white text-xs font-semibold rounded-lg text-center transition-colors shadow-sm cursor-pointer">
                    Tinjau dan Aktifkan
                </button>

                <!-- Tombol Abaikan -->
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
            <x-heroicon-s-check-circle class="w-8 h-8 text-slate-300 mx-auto mb-2" />
            <p class="text-sm font-semibold text-slate-500">Tidak ada deteksi insiden baru</p>
            <p class="text-xs text-slate-400 mt-0.5">Seluruh laporan insiden telah ditinjau</p>
        </div>
    @endforelse
</div>