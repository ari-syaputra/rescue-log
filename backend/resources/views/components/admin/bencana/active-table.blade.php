<div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 sm:p-5 w-full">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-base font-bold text-slate-900">Operasi Tanggap Darurat Aktif</h2>
            <p class="text-xs text-slate-500">Daftar bencana resmi yang sedang dalam penanganan logistik BPBD.</p>
        </div>
    </div>

    <!-- 1. TAMPILAN MOBILE: KARTU -->
    <div class="block sm:hidden space-y-3">
        @forelse($activeDisasters ?? [] as $bencana)
            <div class="p-4 bg-slate-50/80 rounded-xl border border-slate-200/80 flex flex-col gap-3">
                <!-- Header Kartu: Bencana & Status -->
                <div class="flex items-center justify-between border-b border-slate-200/60 pb-2">
                    <span class="font-bold text-slate-900 text-sm">
                        {{ $bencana->jenis_bencana }}
                    </span>
                    <span class="px-2 py-0.5 text-[11px] font-semibold rounded-full bg-rose-100 text-rose-800">
                        Sedang Berjalan
                    </span>
                </div>

                <!-- Detail Lokasi & Tanggal -->
                <div class="grid grid-cols-1 gap-2 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[10px] uppercase font-semibold">Lokasi / Wilayah</span>
                        <span class="font-medium text-slate-800">{{ $bencana->lokasi_bencana }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-1 border-t border-slate-200/40">
                        <div>
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tanggal Aktivasi</span>
                            <span class="font-medium text-slate-600">
                                {{ \Carbon\Carbon::parse($bencana->tanggal_aktivasi)->translatedFormat('d M Y, H:i') }}
                            </span>
                        </div>
                        <div class="text-right">
                            <span class="text-slate-400 block text-[10px] uppercase font-semibold">Koordinat</span>
                            <span class="font-mono text-[11px] text-slate-500">
                                {{ $bencana->koordinat_operasional_lat ?? '-' }}, {{ $bencana->koordinat_operasional_lng ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="pt-2 border-t border-slate-200/60 flex justify-end">
                    <form id="form-selesai-mobile-{{ $bencana->id }}" action="{{ route('admin.bencana.finish', $bencana->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="button" 
                                onclick="konfirmasiSelesaiOperasi({{ $bencana->id }}, '{{ $bencana->jenis_bencana }}')"
                                class="w-full py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer text-center flex items-center justify-center gap-1.5">
                            <!-- Heroicon: check-circle -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Selesaikan Operasi
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-8 text-center text-slate-400 text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                <div class="flex flex-col items-center justify-center space-y-2">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                        <!-- Heroicon: inbox -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <p class="text-xs font-bold text-slate-700">Belum ada operasi aktif</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- 2. TAMPILAN DESKTOP: TABEL -->
<div class="hidden sm:block overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="border-b border-slate-200 text-slate-500 text-xs font-bold uppercase tracking-wider bg-slate-200/90">
                <th class="p-3">
                    Jenis Bencana
                </th>
                <th class="p-3">
                    Lokasi / Wilayah
                </th>
                <th class="p-3">
                    Tanggal Aktivasi
                </th>
                <th class="p-3 text-center">
                    Koordinat
                </th>
                <th class="p-3 text-center">
                    Status
                </th>
                <th class="p-3 text-center">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 text-sm">
            @forelse($activeDisasters ?? [] as $bencana)
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="p-3 font-semibold text-slate-900">
                        {{ $bencana->jenis_bencana }}
                    </td>
                    <td class="p-3 text-slate-700">{{ $bencana->lokasi_bencana }}</td>
                    <td class="p-3 text-slate-600">
                        {{ \Carbon\Carbon::parse($bencana->tanggal_aktivasi)->translatedFormat('d M Y, H:i') }}
                    </td>
                    <td class="p-3 text-xs text-slate-500 font-mono text-center">
                        {{ $bencana->koordinat_operasional_lat ?? '-' }}, {{ $bencana->koordinat_operasional_lng ?? '-' }}
                    </td>
                    <td class="p-3 text-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-rose-100 text-rose-800 inline-block">
                            Sedang Berjalan
                        </span>
                    </td>
                    <td class="p-3 text-center">
                        <form id="form-selesai-{{ $bencana->id }}" action="{{ route('admin.bencana.finish', $bencana->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="button" 
                                    onclick="konfirmasiSelesaiOperasi({{ $bencana->id }}, '{{ $bencana->jenis_bencana }}')"
                                    class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-semibold rounded-lg transition-colors cursor-pointer inline-flex items-center justify-center">
                                Selesaikan Operasi
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-10">
                        <div class="flex flex-col items-center justify-center space-y-2">
                            <p class="text-xs font-bold text-slate-700">Belum ada operasi aktif</p>
                            <p class="text-[11px] text-slate-400">Operasi yang aktif akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>