<div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Riwayat Operasi Bencana Selesai</h2>
            <p class="text-xs text-slate-500">Arsip penanganan bencana yang telah dinyatakan ditutup/nonaktif.</p>
        </div>
        <span class="px-3 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full">
            {{ count($completedDisasters ?? []) }} Selesai
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider bg-slate-200/90">
                    <th class="p-3">Jenis Bencana</th>
                    <th class="p-3">Lokasi / Wilayah</th>
                    <th class="p-3">Tanggal Aktivasi</th>
                    <th class="p-3">Waktu Selesai</th>
                    <th class="p-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($completedDisasters ?? [] as $selesai)
                    <tr class="hover:bg-slate-50/60 transition-colors">
                        <td class="p-3 font-semibold text-slate-800">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                                {{ $selesai->jenis_bencana }}
                            </span>
                        </td>
                        <td class="p-3 text-slate-600">{{ $selesai->lokasi_bencana }}</td>
                        <td class="p-3 text-slate-500 text-xs">
                            {{ \Carbon\Carbon::parse($selesai->tanggal_aktivasi)->translatedFormat('d M Y, H:i') }}
                        </td>
                        <td class="p-3 text-slate-500 text-xs">
                            {{ $selesai->updated_at ? \Carbon\Carbon::parse($selesai->updated_at)->translatedFormat('d M Y, H:i') : '-' }}
                        </td>
                        <td class="p-3">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                                ✅ Penanganan Selesai
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-slate-400 text-sm">
                            Belum ada riwayat bencana yang diselesaikan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>