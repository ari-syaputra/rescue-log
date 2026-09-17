@props(['permintaanList' => collect()])

@php
    // Routing aman untuk link "Lihat Semua"
    $routePermintaan = '#';
    if (Route::has('admin.permintaan.index')) {
        $routePermintaan = route('admin.permintaan.index');
    } elseif (Route::has('admin.kebutuhan-logistik.index')) {
        $routePermintaan = route('admin.kebutuhan-logistik.index');
    } elseif (Route::has('admin.permintaan')) {
        $routePermintaan = route('admin.permintaan');
    }
@endphp

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 flex flex-col justify-between h-full">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <x-heroicon-s-clipboard-document-list class="w-4 h-4 text-blue-600 shrink-0" />
            <span>Permintaan Logistik Masuk</span>
        </h2>
        
        <a href="{{ $routePermintaan }}" 
           class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="space-y-3 flex-1 flex flex-col justify-between">
        @forelse($permintaanList as $item)
            @php
                // Tentukan warna badge berdasarkan status pengajuan
                $statusClass = match(strtolower($item->status ?? 'menunggu')) {
                    'disetujui', 'approved' => 'text-emerald-700 bg-emerald-100 border-emerald-200',
                    'ditolak', 'rejected'  => 'text-red-700 bg-red-100 border-red-200',
                    'diproses', 'process'   => 'text-blue-700 bg-blue-100 border-blue-200',
                    default                 => 'text-amber-700 bg-amber-100 border-amber-200',
                };
            @endphp

            <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="p-2 bg-orange-100 rounded-lg text-orange-600 shrink-0">
                        <x-heroicon-s-building-office-2 class="w-5 h-5" />
                    </div>
                    <div class="min-w-0">
                        <h4 class="text-xs font-bold text-slate-800 truncate">
                            {{ $item->posko->nama_posko ?? $item->nama_posko ?? 'Posko Lapangan' }}
                        </h4>
                        <p class="text-[10px] text-slate-500 truncate">
                            {{ $item->lokasi ?? $item->posko->lokasi ?? 'Lokasi Posko' }} • 
                            <span class="font-medium text-slate-700">
                                {{ $item->ringkasan_kebutuhan ?? $item->nama_barang ?? 'Logistik Relief' }} 
                                @if(isset($item->total_jumlah))
                                    ({{ number_format($item->total_jumlah, 0, ',', '.') }} item)
                                @endif
                            </span>
                        </p>
                    </div>
                </div>

                <span class="px-2 py-1 text-[10px] font-bold border rounded-md shrink-0 uppercase tracking-wider {{ $statusClass }}">
                    {{ $item->status ?? 'Menunggu' }}
                </span>
            </div>
        @empty
            <div class="flex flex-col items-center justify-center p-6 text-center my-auto">
                <x-heroicon-o-inbox class="w-8 h-8 text-slate-300 mb-1" />
                <p class="text-xs font-medium text-slate-500">Belum ada permintaan masuk</p>
            </div>
        @endforelse
    </div>
</div>