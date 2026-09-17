@props(['distribusiList' => collect()])

@php
    // Safe route lookup
    $routeDistribusi = '#';
    if (Route::has('admin.distribusi.index')) {
        $routeDistribusi = route('admin.distribusi.index');
    } elseif (Route::has('admin.distribusi')) {
        $routeDistribusi = route('admin.distribusi');
    }
@endphp

<div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 h-full flex flex-col justify-between">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-bold text-slate-900 flex items-center gap-2">
            <x-heroicon-s-truck class="w-4 h-4 text-blue-600 shrink-0" />
            <span>Distribusi Terakhir</span>
        </h2>
        
        <a href="{{ $routeDistribusi }}" 
           class="text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline transition-colors">
            Lihat Semua &rarr;
        </a>
    </div>

    <div class="overflow-x-auto flex-1">
        <table class="w-full text-xs text-left">
            <thead class="bg-slate-50 text-slate-500 font-bold uppercase border-b border-slate-100">
                <tr>
                    <th class="py-2.5 px-3">Tanggal</th>
                    <th class="py-2.5 px-3">Posko Tujuan</th>
                    <th class="py-2.5 px-3">Jenis Logistik</th>
                    <th class="py-2.5 px-3">Jumlah</th>
                    <th class="py-2.5 px-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                @forelse($distribusiList as $item)
                    @php
                        // Format Tanggal
                        $tanggal = isset($item->created_at) 
                            ? \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') 
                            : ($item->tanggal ?? '-');

                        // Badge Styling berdasarkan Status
                        $statusClass = match(strtolower($item->status ?? 'dikirim')) {
                            'terkirim', 'selesai', 'delivered' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'proses', 'dalam perjalanan', 'shipping' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'batal', 'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-blue-100 text-blue-700 border-blue-200',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="py-2.5 px-3 whitespace-nowrap text-slate-500">{{ $tanggal }}</td>
                        <td class="py-2.5 px-3 font-semibold text-slate-900">
                            {{ $item->posko->nama_posko ?? $item->nama_posko ?? 'Posko Lapangan' }}
                        </td>
                        <td class="py-2.5 px-3">
                            {{ $item->jenis_logistik ?? $item->nama_barang ?? 'Logistik Relief' }}
                        </td>
                        <td class="py-2.5 px-3 whitespace-nowrap">
                            {{ number_format($item->jumlah ?? $item->total_jumlah ?? 0, 0, ',', '.') }} {{ $item->satuan ?? 'paket' }}
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="px-2 py-0.5 text-[10px] font-bold border rounded uppercase tracking-wider {{ $statusClass }}">
                                {{ $item->status ?? 'Proses' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            <div class="flex flex-col items-center justify-center gap-1">
                                <x-heroicon-o-truck class="w-7 h-7 text-slate-300" />
                                <span>Belum ada riwayat distribusi logistik</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>